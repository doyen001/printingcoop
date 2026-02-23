<?php

/**
 * Store Configuration
 * Replicate exact values from CI config/constants.php
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Website Names
    |--------------------------------------------------------------------------
    | CI: WEBSITE_NAME, WEBSITE_NAME_FRANCH (constants.php line 91-92)
    */
    'website_name' => env('WEBSITE_NAME', 'Printing Coop'),
    'website_name_french' => env('WEBSITE_NAME_FRENCH', 'Imprimeur.coop'),
    
    /*
    |--------------------------------------------------------------------------
    | Store IDs
    |--------------------------------------------------------------------------
    | Multi-store configuration
    | 1 = Printing Coop (default)
    | 3 = ClickImprimerie
    | 5 = EcoInk
    */
    'default_store_id' => 1,
    'stores' => [
        1 => [
            'name' => 'Printing Coop',
            'domain' => 'printing.coop',
            'css_file' => 'style.css',
            'logo' => 'printing-coop-logo.png',
            'favicon' => 'favicon.png',
            'analytics_id' => 'G-S5JX3QGBRH', // English
            'analytics_id_french' => 'G-L7V7YLFS15', // French
            'loader_image' => 'loder.gif',
        ],
        3 => [
            'name' => 'ClickImprimerie',
            'domain' => 'clickimprimerie.com',
            'css_file' => 'clickimprimerie.style.css',
            'logo' => 'clickimprimerie-logo.png',
            'favicon' => 'favicon-click.png',
            'analytics_id' => 'G-X71XTPM7CL',
            'loader_image' => 'loader-pink.gif',
        ],
        5 => [
            'name' => 'EcoInk',
            'domain' => 'ecoink.ca',
            'css_file' => 'ecoink.style.css',
            'logo' => 'ecoink-logo.png',
            'favicon' => 'favicon-eco.png',
            'analytics_id' => 'G-QHV7YWZEQ5',
            'loader_image' => 'loader-green.gif',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    | CI: PASSWORD_SECRET_START, PASSWORD_SECRET_END (constants.php line 7-8)
    */
    'password_secret_start' => env('PASSWORD_SECRET_START', '####PRINTINGCOOPSECURITYSTART####'),
    'password_secret_end' => env('PASSWORD_SECRET_END', '####PRINTINGCOOPSECURITYEND####'),
    
    /*
    |--------------------------------------------------------------------------
    | IP Blocking Configuration
    |--------------------------------------------------------------------------
    | CI: BLOCKED_IPS_ACCESS_TIME_IN_MINUTES (constants.php line 9)
    */
    'blocked_ips_access_time_minutes' => env('BLOCKED_IPS_ACCESS_TIME', 240),
    
    /*
    |--------------------------------------------------------------------------
    | File Upload Configuration
    |--------------------------------------------------------------------------
    */
    'upload_paths' => [
        'products' => 'uploads/products/',
        'logo' => 'uploads/logo/',
        'banners' => 'uploads/banners/',
        'blogs' => 'uploads/blogs/',
        'orders' => 'uploads/orders/',
        'email_templates' => 'uploads/email_templates/',
    ],
    
    'image_sizes' => [
        'small' => ['width' => 150, 'height' => 150],
        'medium' => ['width' => 300, 'height' => 300],
        'large' => ['width' => 800, 'height' => 800],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Timezone Configuration
    |--------------------------------------------------------------------------
    | CI: date_default_timezone_set("Asia/Kolkata") (config.php line 3)
    */
    'timezone' => env('APP_TIMEZONE', 'Asia/Kolkata'),
    
    /*
    |--------------------------------------------------------------------------
    | Default Language
    |--------------------------------------------------------------------------
    | CI: $config['language'] = 'english' (config.php line 80)
    */
    'default_language' => 'english',
    'supported_languages' => ['english', 'french'],
    
    /*
    |--------------------------------------------------------------------------
    | Announcements
    |--------------------------------------------------------------------------
    */
    'announcement' => 'Proudly involved in the community! 10% discount for Community organizations, co-operatives, not-for-profit organizations and print reselling companies will benefit.',
    'announcement_french' => 'Fièrement impliqué dans la communauté! 10% de rabais pour les organismes communautaires, les coopératives, les organismes à but non lucratif et les entreprises de revente d\'impression en bénéficieront.',
    
    /*
    |--------------------------------------------------------------------------
    | Store Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file stores runtime store detection data.
    | Values are set dynamically by the DetectStore middleware.
    */

    'main_store_data' => [],
    
    'language_name' => 'english',
    
    'main_store_id' => 1,
    
    'website_store_id' => 1,
    
    'store_list_data' => [],
    
    'currency_list' => [],
    
    'default_currency_id' => 1,
    
    'default_currency_data' => [],
    
    'product_price_currency' => 'price',
    
    'product_price_currency_symbol' => '$',
    
    'show_covid19_msg' => true,
    
    'base_url' => env('APP_URL', 'http://localhost'),

];
