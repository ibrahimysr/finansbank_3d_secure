<?php

return [
    'finans' => [
        'test_mode' => env('FINANS_TEST_MODE', true),
        'merchant_id' => env('FINANS_MERCHANT_ID', ''),
        'merchant_terminal' => env('FINANS_MERCHANT_TERMINAL', ''),
        'merchant_name' => env('FINANS_MERCHANT_NAME', ''),
        'merchant_password' => env('FINANS_MERCHANT_PASSWORD', ''),
        'store_key' => env('FINANS_STORE_KEY', ''),
        'api_user' => env('FINANS_API_USER', ''),
        'api_password' => env('FINANS_API_PASSWORD', ''),
        'secure_type' => [
            '3d' => '3DPay',
            'payment' => '3DPayPayment'
        ],
        'urls' => [
            '3d_gate' => env('FINANS_3D_URL', ''),
            'api' => env('FINANS_API_URL', ''),
            'success' => env('FINANS_SUCCESS_URL', '/payment/success'),
            'failure' => env('FINANS_FAILURE_URL', '/payment/failure'),
        ],
        'test_cards' => [
            'visa' => [
                'number' => '4022780198283155',
                'expiry' => '0150',
                'cvv' => '',
                'holder' => 'Test User'
            ],
            'mastercard' => [
                'number' => '5555555555554444',
                'expiry' => '12/25',
                'cvv' => '123',
                'holder' => 'Test User'
            ]
        ],
        'test_customer' => [
            'email' => 'test@example.com',
            'phone' => '5551234567'
        ]
    ]
]; 