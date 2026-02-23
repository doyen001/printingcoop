<?php

/**
 * Sina Provider Configuration
 * CI: application/config/sina.php
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Sina API Configuration
    |--------------------------------------------------------------------------
    | Configuration for Sina provider API integration
    */
    'endpoint' => env('SINA_ENDPOINT', 'https://liveapi.sinalite.com'),
    'client_id' => env('SINA_CLIENT_ID', 'd9L5eSnZGSvTPAzNRcMleHD0cFhIWCa2'),
    'client_secret' => env('SINA_CLIENT_SECRET', '3UiQ3bPxx1SkRgoQQqkh8xyRDTZiphYIMGLQT_Mqb0xsqOQPVCaHHtCdI7MwZ-SX'),
    'audience' => env('SINA_AUDIENCE', 'https://apiconnect.sinalite.com'),
    'grant_type' => env('SINA_GRANT_TYPE', 'client_credentials'),
    'shipping_extra_days' => 2,
];
