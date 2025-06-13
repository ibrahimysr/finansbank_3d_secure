<?php

return [
    'finans' => [
        'test_mode' => env('FINANS_TEST_MODE'),
        'merchant_id' => env('FINANS_MERCHANT_ID'),
        'merchant_terminal' => env('FINANS_MERCHANT_TERMINAL'),
        'merchant_name' => env('FINANS_MERCHANT_NAME' ),
        'merchant_password' => env('FINANS_MERCHANT_PASSWORD' ),
        'store_key' => env('FINANS_STORE_KEY' ),
        'api_user' => env('FINANS_API_USER' ),
        'api_password' => env('FINANS_API_PASSWORD' ),
        'secure_type' => [
            '3d' => '3DPay',
            'payment' => '3DPayPayment'
        ],
        'urls' => [
            '3d_gate' => env('FINANS_3D_URL'),
            'api' => env('FINANS_API_URL'),
            'success' => env('FINANS_SUCCESS_URL', '/payment/success'),
            'failure' => env('FINANS_FAILURE_URL', '/payment/failure'),
        ],
        'test_cards' => [
            'visa' => '4022780198283155',
            'expiry' => '0150',
            'cvv' => '',
            'alternative_card' => [
                'number' => '9792091234123455',
                'expiry' => '1220',
                'cvv' => '123'
            ]
        ]
    ]
]; 