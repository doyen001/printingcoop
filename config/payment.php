<?php

/**
 * Payment Gateway Configuration
 * Replicate exact values from CI config/paypal.php
 */

return [
    /*
    |--------------------------------------------------------------------------
    | PayPal Configuration
    |--------------------------------------------------------------------------
    | CI: config/paypal.php
    */
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID', ''),
        'secret' => env('PAYPAL_SECRET', ''),
        'business_email' => env('PAYPAL_BUSINESS_EMAIL', ''),
        'sandbox' => env('PAYPAL_SANDBOX', true), // CI: line 19
        'currency_code' => env('PAYPAL_CURRENCY', 'USD'), // CI: line 31
        'ipn_log_enabled' => true, // CI: line 25
        'ipn_log_file' => storage_path('logs/paypal_ipn.log'), // CI: line 24
        'button_path' => 'buttons', // CI: line 28
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Payment Methods
    |--------------------------------------------------------------------------
    | Available payment methods in the system
    */
    'methods' => [
        'cod' => [
            'name' => 'Cash on Delivery',
            'enabled' => true,
            'description' => 'Pay with cash upon delivery',
        ],
        'paypal' => [
            'name' => 'PayPal',
            'enabled' => true,
            'description' => 'Pay securely with PayPal',
        ],
        'paytm' => [
            'name' => 'Paytm',
            'enabled' => false,
            'description' => 'Pay with Paytm',
        ],
        'payumoney' => [
            'name' => 'PayUMoney',
            'enabled' => false,
            'description' => 'Pay with PayUMoney',
        ],
        'stripe' => [
            'name' => 'Stripe',
            'enabled' => false,
            'description' => 'Pay with credit/debit card',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Paytm Configuration
    |--------------------------------------------------------------------------
    */
    'paytm' => [
        'merchant_id' => env('PAYTM_MERCHANT_ID', ''),
        'merchant_key' => env('PAYTM_MERCHANT_KEY', ''),
        'website' => env('PAYTM_WEBSITE', 'WEBSTAGING'),
        'industry_type' => env('PAYTM_INDUSTRY_TYPE', 'Retail'),
        'channel_id' => env('PAYTM_CHANNEL_ID', 'WEB'),
        'transaction_url' => env('PAYTM_TRANSACTION_URL', 'https://securegw-stage.paytm.in/order/process'),
        'transaction_status_url' => env('PAYTM_STATUS_URL', 'https://securegw-stage.paytm.in/order/status'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | PayUMoney Configuration
    |--------------------------------------------------------------------------
    */
    'payumoney' => [
        'merchant_key' => env('PAYUMONEY_MERCHANT_KEY', ''),
        'salt' => env('PAYUMONEY_SALT', ''),
        'auth_header' => env('PAYUMONEY_AUTH_HEADER', ''),
        'payment_url' => env('PAYUMONEY_PAYMENT_URL', 'https://test.payu.in/_payment'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Stripe Configuration
    |--------------------------------------------------------------------------
    */
    'stripe' => [
        'public_key' => env('STRIPE_PUBLIC_KEY', ''),
        'secret_key' => env('STRIPE_SECRET_KEY', ''),
        'currency' => env('STRIPE_CURRENCY', 'usd'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Clover Configuration (POS/Credit Card)
    |--------------------------------------------------------------------------
    */
    'clover_mode' => env('CLOVER_MODE', 'sandbox'), // 'live' or 'sandbox'
    'clover_api_key' => env('CLOVER_API_KEY', ''),
    'clover_secret' => env('CLOVER_SECRET', ''),
    'clover_sandbox_api_key' => env('CLOVER_SANDBOX_API_KEY', ''),
    'clover_sandbox_secret' => env('CLOVER_SANDBOX_SECRET', ''),
    
    /*
    |--------------------------------------------------------------------------
    | PayPal Legacy Configuration (for backward compatibility)
    |--------------------------------------------------------------------------
    */
    'paypal_mode' => env('PAYPAL_MODE', 'sandbox'),
    'paypal_client_id' => env('PAYPAL_CLIENT_ID', ''),
    'paypal_secret' => env('PAYPAL_SECRET', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Payment Status
    |--------------------------------------------------------------------------
    */
    'statuses' => [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
        'cancelled' => 'Cancelled',
    ],
];
