<?php

/**
 * Application Constants
 * Ported from CodeIgniter application/config/constants.php
 * 
 * These constants are used throughout the application
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Currency and Order Constants
    | CI: constants.php lines 186-189
    |--------------------------------------------------------------------------
    */
    'CURREBCY_SYMBOL' => 'CA$ ',
    'ORDER_ID_PREFIX' => 'PRINTINGCOOP-',
    'ORDER_ID_PREFIX_FRENCH' => 'IMPRIMEURCOOP-',
    'CUSTOMER_ID_PREFIX' => 'CUST-0',
    
    /*
    |--------------------------------------------------------------------------
    | File Upload Constants
    | CI: constants.php lines 183-185
    |--------------------------------------------------------------------------
    */
    'FILE_ALLOWED_TYPES' => 'jpg|jpeg|png|gif',
    'FILE_MAX_SIZE' => 2048, // in Kb
    'FILE_MAX_SIZE_JS' => 1024 * 1024 * 2, // 1048576 bytes
    
    /*
    |--------------------------------------------------------------------------
    | Website Information
    |--------------------------------------------------------------------------
    */
    'WEBSITE_NAME' => env('WEBSITE_NAME', 'Printing Coop'),
    'WEBSITE_NAME_FRANCH' => env('WEBSITE_NAME_FRANCH', 'Imprimeur Coop'),
    'FROM_EMAIL' => env('FROM_EMAIL', 'info@printing.coop'),
    'FROM_EMAIL_FRANCH' => env('FROM_EMAIL_FRANCH', 'info@imprimeur.coop'),
];
