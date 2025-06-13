<?php

namespace App\Services;

use App\DTOs\PaymentRequestDTO;
use App\DTOs\PaymentResponseDTO;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class FinansbankPaymentService
{
    private Client $client;
    private array $config;

    public function __construct()
    {
        $this->client = new Client([
            'verify' => false,
            'timeout' => 30,
            'connect_timeout' => 30
        ]);
        $this->config = config('payment.finans');
    }

    public function initiate3DSecure(PaymentRequestDTO $request): string
    {
        try {
            $baseParams = $request->toArray();
            
            $params = [
                'MbrId' => '5', 
                'MerchantID' => $this->config['merchant_id'],
                'MerchantPass' => $this->config['merchant_password'],
                'UserCode' => $this->config['api_user'],
                'UserPass' => $this->config['api_password'],
                'SecureType' => $this->config['secure_type']['3d'],
                'TxnType' => 'Auth',
                'Currency' => '949', 
                'OkUrl' => url($this->config['urls']['success']),
                'FailUrl' => url($this->config['urls']['failure']),
                'OrderId' => $baseParams['OrderId'],
                'PurchAmount' => $baseParams['Amount'],
                'Pan' => $baseParams['Pan'],
                'Expiry' => $baseParams['Expiry'],
                'Cvv2' => $baseParams['Cvv2'],
                'Lang' => 'TR',
                'InstallmentCount' => '0', 
                'TerminalID' => $this->config['merchant_terminal'],
                'Ecommerce' => '1', 
                'MrcCountryCode' => '792',
                'Rnd' => microtime(),
                'BatchNo' => '',
                'TerminalType' => 'WEB', 
                'TransactionDeviceSource' => '0', 
                'MrcSubType' => '0' 
            ];
            
            $hashStr = $params['MbrId'] . 
                      $params['OrderId'] . 
                      $params['PurchAmount'] . 
                      $params['OkUrl'] . 
                      $params['FailUrl'] . 
                      $params['TxnType'] . 
                      $params['InstallmentCount'] . 
                      $params['Rnd'] . 
                      $this->config['merchant_password'];
            
            $params['Hash'] = base64_encode(pack('H*', sha1($hashStr)));

            Log::info('3D Secure payment initiation', ['params' => $params]);

            return $this->generate3DForm($params);
        } catch (Exception $e) {
            Log::error('3D Secure payment initiation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function verify3DResponse(array $response): PaymentResponseDTO
    {
        try {
            Log::info('3D Response received', ['response' => $response]);

            if (isset($response['BankInternalResponseMessage']) && 
                str_contains($response['BankInternalResponseMessage'], 'Terminal HizmetStatusu=K')) {
                Log::error('Terminal is in closed state', ['terminal_id' => $this->config['merchant_terminal']]);
                return new PaymentResponseDTO(
                    success: false,
                    message: 'Terminal şu anda işlem yapamıyor. Lütfen daha sonra tekrar deneyiniz.',
                    raw: $response
                );
            }

            if (isset($response['ProcReturnCode']) && $response['ProcReturnCode'] === '71') {
                Log::error('Invalid batch number error', [
                    'terminal_id' => $this->config['merchant_terminal'],
                    'batch_no' => $response['BatchNo'] ?? 'N/A'
                ]);
                return new PaymentResponseDTO(
                    success: false,
                    message: 'İşlem şu anda gerçekleştirilemiyor. Lütfen daha sonra tekrar deneyiniz.',
                    raw: $response
                );
            }

            if (($response['3DStatus'] ?? '') !== '1') {
                return new PaymentResponseDTO(
                    success: false,
                    message: '3D doğrulama başarısız',
                    raw: $response
                );
            }

            $hashStr = $response['MerchantID'] . 
                      $this->config['merchant_password'] . 
                      $response['OrderId'] . 
                      ($response['AuthCode'] ?? '') . 
                      $response['ProcReturnCode'] . 
                      $response['3DStatus'] . 
                      $response['ResponseRnd'] . 
                      $this->config['api_user'];
            
            $hashCalculated = base64_encode(pack('H*', sha1($hashStr)));
            
            if ($hashCalculated !== $response['ResponseHash']) {
                Log::error('Hash verification failed', [
                    'calculated' => $hashCalculated,
                    'received' => $response['ResponseHash']
                ]);
                
                return new PaymentResponseDTO(
                    success: false,
                    message: 'Hash doğrulama hatası',
                    raw: $response
                );
            }

            return $this->processPayment($response);
        } catch (Exception $e) {
            Log::error('3D Response verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function processPayment(array $threeDResponse): PaymentResponseDTO
    {
        try {
            $params = [
                'MbrId' => '5',
                'MerchantID' => $this->config['merchant_id'],
                'MerchantPass' => $this->config['merchant_password'],
                'UserCode' => $this->config['api_user'],
                'UserPass' => $this->config['api_password'],
                'SecureType' => $this->config['secure_type']['payment'],
                'TxnType' => 'Auth',
                'OrderId' => $threeDResponse['OrderId'],
                'PurchAmount' => $threeDResponse['PurchAmount'],
                'Currency' => $threeDResponse['Currency'] ?? '949',
                'InstallmentCount' => '0',
                'TerminalID' => $this->config['merchant_terminal'],
                'Ecommerce' => '1',
                'MrcCountryCode' => '792',
                'Cavv' => $threeDResponse['Cavv'] ?? '',
                'Eci' => $threeDResponse['Eci'] ?? '',
                'Xid' => $threeDResponse['Xid'] ?? '',
                'MD' => $threeDResponse['MD'] ?? '',
                'BatchNo' => '',
                'TerminalType' => 'WEB',
                'TransactionDeviceSource' => '0',
                'MrcSubType' => '0'
            ];

            $response = $this->client->post($this->config['urls']['api'], [
                'form_params' => $params
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            
            Log::info('API Response received', ['response' => $result]);

            return PaymentResponseDTO::fromArray($result);
        } catch (Exception $e) {
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function generate3DForm(array $params): string
    {
        $formFields = '';
        foreach ($params as $key => $value) {
            $formFields .= sprintf(
                '<input type="hidden" name="%s" value="%s">',
                htmlspecialchars($key),
                htmlspecialchars($value)
            );
        }

        return sprintf(
            '<form id="three_d_form" action="%s" method="post">%s</form>
            <script>document.getElementById("three_d_form").submit();</script>',
            $this->config['urls']['3d_gate'],
            $formFields
        );
    }
} 