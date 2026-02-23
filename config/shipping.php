<?php

/**
 * Shipping Provider Configuration
 * Replicate exact values from CI shipping configurations
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Default Shipping Provider
    |--------------------------------------------------------------------------
    */
    'default_provider' => env('SHIPPING_PROVIDER', 'ups'),
    
    /*
    |--------------------------------------------------------------------------
    | UPS Configuration
    |--------------------------------------------------------------------------
    | CI: config/UpsApi/
    */
    'ups' => [
        'enabled' => env('UPS_ENABLED', true),
        'api_url' => env('UPS_API_URL', 'https://onlinetools.ups.com/ups.app/xml/Rate'),
        'access_license_number' => env('UPS_ACCESS_LICENSE', ''),
        'user_id' => env('UPS_USER_ID', ''),
        'password' => env('UPS_PASSWORD', ''),
        'shipper_number' => env('UPS_SHIPPER_NUMBER', ''),
        'test_mode' => env('UPS_TEST_MODE', true),
        
        // Default shipper address
        'shipper_address' => [
            'name' => env('UPS_SHIPPER_NAME', 'Printing Coop'),
            'attention_name' => env('UPS_SHIPPER_ATTENTION', 'Shipping Department'),
            'address_line1' => env('UPS_SHIPPER_ADDRESS1', ''),
            'city' => env('UPS_SHIPPER_CITY', ''),
            'state' => env('UPS_SHIPPER_STATE', ''),
            'postal_code' => env('UPS_SHIPPER_POSTAL', ''),
            'country_code' => env('UPS_SHIPPER_COUNTRY', 'CA'),
        ],
        
        // Service codes
        'services' => [
            '01' => 'UPS Next Day Air',
            '02' => 'UPS 2nd Day Air',
            '03' => 'UPS Ground',
            '07' => 'UPS Worldwide Express',
            '08' => 'UPS Worldwide Expedited',
            '11' => 'UPS Standard',
            '12' => 'UPS 3 Day Select',
            '13' => 'UPS Next Day Air Saver',
            '14' => 'UPS Next Day Air Early AM',
            '54' => 'UPS Worldwide Express Plus',
            '59' => 'UPS 2nd Day Air AM',
            '65' => 'UPS Saver',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Flagship Shipping Configuration
    |--------------------------------------------------------------------------
    | CI: config/flagship-api-sdk-master/
    */
    'flagship' => [
        'enabled' => env('FLAGSHIP_ENABLED', false),
        'api_url' => env('FLAGSHIP_API_URL', 'https://api.flagshipcompany.com'),
        'token' => env('FLAGSHIP_TOKEN', ''),
        'test_mode' => env('FLAGSHIP_TEST_MODE', true),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Canada Post Configuration
    |--------------------------------------------------------------------------
    */
    'canada_post' => [
        'enabled' => env('CANADA_POST_ENABLED', false),
        'api_url' => env('CANADA_POST_API_URL', 'https://ct.soa-gw.canadapost.ca'),
        'username' => env('CANADA_POST_USERNAME', ''),
        'password' => env('CANADA_POST_PASSWORD', ''),
        'customer_number' => env('CANADA_POST_CUSTOMER_NUMBER', ''),
        'test_mode' => env('CANADA_POST_TEST_MODE', true),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Shipping Methods
    |--------------------------------------------------------------------------
    */
    'methods' => [
        'standard' => [
            'name' => 'Standard Shipping',
            'enabled' => true,
            'description' => '5-7 business days',
            'base_rate' => 5.00,
        ],
        'express' => [
            'name' => 'Express Shipping',
            'enabled' => true,
            'description' => '2-3 business days',
            'base_rate' => 15.00,
        ],
        'overnight' => [
            'name' => 'Overnight Shipping',
            'enabled' => true,
            'description' => 'Next business day',
            'base_rate' => 25.00,
        ],
        'pickup' => [
            'name' => 'Local Pickup',
            'enabled' => true,
            'description' => 'Pick up at store',
            'base_rate' => 0.00,
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Shipping Calculation
    |--------------------------------------------------------------------------
    */
    'calculation' => [
        'method' => env('SHIPPING_CALCULATION_METHOD', 'weight'), // weight, price, flat
        'free_shipping_threshold' => env('FREE_SHIPPING_THRESHOLD', 100.00),
        'weight_unit' => 'kg', // kg or lb
        'dimension_unit' => 'cm', // cm or in
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Packaging
    |--------------------------------------------------------------------------
    */
    'packaging' => [
        'default_weight' => 0.5, // kg
        'default_length' => 30, // cm
        'default_width' => 20, // cm
        'default_height' => 10, // cm
        'max_weight' => 30, // kg
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Delivery Time Estimates
    |--------------------------------------------------------------------------
    */
    'delivery_estimates' => [
        'standard' => '5-7 business days',
        'express' => '2-3 business days',
        'overnight' => 'Next business day',
        'international' => '10-15 business days',
    ],
];
