<?php

namespace App\Http\Controllers;

use App\DTOs\PaymentRequestDTO;
use App\Services\FinansbankPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class PaymentController extends Controller
{
    private FinansbankPaymentService $paymentService;

    public function __construct(FinansbankPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
        // 3D Secure callback'leri için middleware'i devre dışı bırak
        $this->middleware('web')->except(['success', 'failure']);
    }

    public function form()
    {
        return view('payment.form');
    }

    public function initiate(Request $request)
    {
        // Validation ekle
        $request->validate([
            'amount' => 'required|numeric|min:1',
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
        ], [
            'amount.required' => 'Tutar alanı zorunludur.',
            'amount.numeric' => 'Tutar alanı sayı olmalıdır.',
            'amount.min' => 'Tutar en az 1 olmalıdır.',
            'card_number.required' => 'Kart numarası alanı zorunludur.',
            'expiry.required' => 'Son kullanma tarihi alanı zorunludur.',
            'cvv.required' => 'CVV alanı zorunludur.',
            'cardholder_name.required' => 'Kart üzerindeki isim alanı zorunludur.',
        ]);
        try {
            // Tutar formatını düzelt (100.50 -> 10050)
            $amount = number_format((float) $request->input('amount'), 2, '', '');
            
            $dto = new PaymentRequestDTO(
                OrderId: uniqid('ORD'),
                Amount: $amount,
                Pan: str_replace(' ', '', $request->input('card_number')),
                Expiry: str_replace(['/', ' '], '', $request->input('expiry')),
                Cvv2: $request->input('cvv'),
                CardholderName: $request->input('cardholder_name'),
                Email: $request->input('email', 'test@test.com'),
                Tel: $request->input('phone', '5551234567'),
                CustomerIp: $request->ip(),
                InstallmentCount: (int) $request->input('installment_count', 0)
            );

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
        
        return view('payment.failure', [
            'error' => '3D Secure doğrulaması başarısız.',
            'details' => $request->all()
        ]);
    }
} 