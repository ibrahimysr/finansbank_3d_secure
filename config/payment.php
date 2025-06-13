<?php

return [
    'finans' => [
        'test_mode' => env('FINANS_TEST_MODE', true),
        'merchant_id' => env('FINANS_MERCHANT_ID', '085300000009704'),
        'merchant_terminal' => env('FINANS_MERCHANT_TERMINAL', 'VS251939'),
        'merchant_name' => env('FINANS_MERCHANT_NAME', '3D PAY TEST ISYERI'),
        'merchant_password' => env('FINANS_MERCHANT_PASSWORD', '12345678'),
        'store_key' => env('FINANS_STORE_KEY', '12345678'),
        'api_user' => env('FINANS_API_USER', 'QNB_API_KULLANICI_3DPAY'),
        'api_password' => env('FINANS_API_PASSWORD', 'UcBN0'),
        'secure_type' => [
            '3d' => '3DPay',
            'payment' => '3DPayPayment'
        ],
        'urls' => [
            '3d_gate' => env('FINANS_3D_URL', 'https://vpostest.qnb.com.tr/Gateway/Default.aspx'),
            'api' => env('FINANS_API_URL', 'https://vpostest.qnb.com.tr/Gateway/Default.aspx'),
            'success' => env('FINANS_SUCCESS_URL', '/payment/success'),
            'failure' => env('FINANS_FAILURE_URL', '/payment/failure'),
        ],
        'test_cards' => [
            'visa' => '4022780198283155',
            'expiry' => '0150',
            'cvv' => '', // Boş geçilebilir
            'alternative_card' => [
                'number' => '9792091234123455',
                'expiry' => '1220',
                'cvv' => '123'
            ]
        ]
    ]
]; 