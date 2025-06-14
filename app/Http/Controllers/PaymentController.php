<?php

namespace App\Http\Controllers;

use App\DTOs\PaymentRequestDTO;
use App\Models\Payment;
use App\Services\FinansbankPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class PaymentController extends Controller
{
    private FinansbankPaymentService $paymentService;
    private array $config;

    public function __construct(FinansbankPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        $this->config = config('payment.finans');
        $this->middleware('web')->except(['success', 'failure']);
    }

    public function form()
    {
        $testMode = $this->config['test_mode'];
        $testData = null;

        if ($testMode) {
            $testData = [
                'card' => $this->config['test_cards']['visa'],
                'customer' => $this->config['test_customer']
            ];
        }

        return view('payment.form', compact('testMode', 'testData'));
    }

    public function initiate(Request $request)
    {
        $rules = [
            'amount' => 'required|numeric|min:1',
        ];

        $messages = [
            'amount.required' => 'Tutar alanı zorunludur.',
            'amount.numeric' => 'Tutar alanı sayı olmalıdır.',
            'amount.min' => 'Tutar en az 1 olmalıdır.',
        ];

        if (!$this->config['test_mode']) {
            $rules = array_merge($rules, [
                'card_number' => [
                    'required',
                    'string',
                    function($attribute, $value, $fail) {
                        $digits = preg_replace('/\D/', '', $value);
                        if (strlen($digits) !== 16) {
                            $fail('Kart numarası 16 haneli olmalıdır.');
                        }
                    }
                ],
                'expiry' => 'required|string',
                'cvv' => 'required|string',
                'cardholder_name' => 'required|string',
                'email' => 'required|email',
            ]);

            $messages = array_merge($messages, [
                'card_number.required' => 'Kart numarası alanı zorunludur.',
                'expiry.required' => 'Son kullanma tarihi alanı zorunludur.',
                'cvv.required' => 'CVV alanı zorunludur.',
                'cardholder_name.required' => 'Kart üzerindeki isim alanı zorunludur.',
                'email.required' => 'E-posta alanı zorunludur.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            ]);
        }

        $request->validate($rules, $messages);

        try {
            $amount = number_format((float) $request->input('amount'), 2, '', '');

            if ($this->config['test_mode']) {
                $testCard = $this->config['test_cards']['visa'];
                $testCustomer = $this->config['test_customer'];

                $dto = new PaymentRequestDTO(
                    OrderId: uniqid('ORD'),
                    Amount: $amount,
                    Pan: $testCard['number'],
                    Expiry: $testCard['expiry'],
                    Cvv2: $testCard['cvv'],
                    CardholderName: $testCard['holder'],
                    Email: $testCustomer['email'],
                    Tel: $testCustomer['phone'],
                    CustomerIp: $request->ip(),
                    InstallmentCount: 0
                );
            } else {
                $dto = new PaymentRequestDTO(
                    OrderId: uniqid('ORD'),
                    Amount: $amount,
                    Pan: str_replace(' ', '', $request->input('card_number')),
                    Expiry: str_replace(['/', ' '], '', $request->input('expiry')),
                    Cvv2: $request->input('cvv'),
                    CardholderName: $request->input('cardholder_name'),
                    Email: $request->input('email'),
                    Tel: $request->input('phone'),
                    CustomerIp: $request->ip(),
                    InstallmentCount: (int) $request->input('installment_count', 0)
                );
            }

            $payment = Payment::create([
                'order_id' => $dto->OrderId,
                'amount' => $request->input('amount'),
                'card_number' => substr($dto->Pan, -4),
                'card_holder_name' => $dto->CardholderName,
                'email' => $dto->Email,
                'phone' => $dto->Tel,
                'status' => 'pending',
                'ip_address' => $dto->CustomerIp
            ]);

            $form = $this->paymentService->initiate3DSecure($dto);
            
            return response($form);
        } catch (Exception $e) {
            Log::error('Payment initiation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withErrors(['error' => 'Ödeme başlatılırken bir hata oluştu.']);
        }
    }

    public function success(Request $request)
    {
        try {
            Log::info('Success callback received', ['data' => $request->all()]);
            
            $response = $this->paymentService->verify3DResponse($request->all());
            
            $payment = Payment::where('order_id', $response->orderId)->first();
            
            if ($payment) {
                $payment->update([
                    'status' => $response->success ? 'success' : 'failed',
                    'transaction_id' => $response->transactionId,
                    'auth_code' => $response->authCode,
                    'host_ref_num' => $response->hostRefNum,
                    'proc_return_code' => $response->procReturnCode,
                    'error_message' => $response->message,
                    'raw_response' => $response->raw
                ]);

                if ($response->success) {
                    Mail::send('emails.payment-success', ['payment' => $payment], function($message) use ($payment) {
                        $message->to($payment->email)
                                ->subject('Ödeme Onayı - ' . $payment->order_id);
                    });
                }
            }
            
            if ($response->success) {
                return view('payment.success', ['response' => $response]);
            }
            
            return view('payment.failure', [
                'error' => $response->message,
                'details' => $response->raw
            ]);
        } catch (Exception $e) {
            Log::error('Success callback processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return view('payment.failure', [
                'error' => 'Ödeme doğrulama işlemi başarısız.',
                'details' => ['message' => $e->getMessage()]
            ]);
        }
    }

    public function failure(Request $request)
    {
        Log::error('Payment failed', ['data' => $request->all()]);
        
        if (isset($request->OrderId)) {
            $payment = Payment::where('order_id', $request->OrderId)->first();
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'error_message' => '3D Secure doğrulaması başarısız.',
                    'raw_response' => $request->all()
                ]);
            }
        }
        
        return view('payment.failure', [
            'error' => '3D Secure doğrulaması başarısız.',
            'details' => $request->all()
        ]);
    }
} 