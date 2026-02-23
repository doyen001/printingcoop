<?php

/**
 * Helper Functions
 * Ported from CodeIgniter application/helpers/*
 * 
 * IMPORTANT: Preserve exact function signatures and logic from CI
 */

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Application Constants
| CI: application/config/constants.php lines 186-189
|--------------------------------------------------------------------------
*/

// Define constants from CI project (hardcoded to avoid autoload issues)
if (!defined('CURREBCY_SYMBOL')) {
    define('CURREBCY_SYMBOL', 'CA$ ');
}
if (!defined('ORDER_ID_PREFIX')) {
    define('ORDER_ID_PREFIX', 'PRINTINGCOOP-');
}
if (!defined('ORDER_ID_PREFIX_FRENCH')) {
    define('ORDER_ID_PREFIX_FRENCH', 'IMPRIMEURCOOP-');
}
if (!defined('CUSTOMER_ID_PREFIX')) {
    define('CUSTOMER_ID_PREFIX', 'CUST-0');
}

/*
|--------------------------------------------------------------------------
| Auth Helper Functions
| CI: application/helpers/auth_helper.php
|--------------------------------------------------------------------------
*/

/**
 * Admin security - store hashed password
 * CI: auth_helper.php line 5-17
 */
// if (!function_exists('admin_security')) {
//     /**
//      * Store admin password in file-based system (lines 5-17)
//      * 
//      * @param array $user ['id' => user_id, 'password' => plain_password]
//      */
//     function admin_security($user)
//     {
//         $user_id = $user['id'];
//         $password = $user['password'];
        
//         $filename = storage_path('app/jeet/buttons/' . $user_id);
        
//         // Create directory if it doesn't exist
//         $directory = dirname($filename);
//         if (!file_exists($directory)) {
//             mkdir($directory, 0755, true);
//         }
        
//         $myfile = fopen($filename, "w");
//         $options = ['cost' => 12];
        
//         // Use PASSWORD_BCRYPT constant and secret wrapping (line 14)
//         $secret_start = env('PASSWORD_SECRET_START', '####PRINTINGCOOPSECURITYSTART####');
//         $secret_end = env('PASSWORD_SECRET_END', '####PRINTINGCOOPSECURITYEND####');
//         $txt = password_hash($secret_start . $password . $secret_end, PASSWORD_BCRYPT, $options);
        
//         fwrite($myfile, $txt);
//         fclose($myfile);
//     }
// }

if (!function_exists('admin_security')) {
    /**
     * Admin security - store hashed password (simplified for Laravel)
     * CI: auth_helper.php line 5-17
     */
    function admin_security($user)
    {
        // For Laravel, we don't need file-based storage since passwords are in database
        // This function is kept for compatibility but doesn't need to do anything
        return true;
    }
}

if (!function_exists('verify_admin_password')) {
    /**
     * Verify admin password - supports both MD5 (CI) and Laravel hashes
     * CI: auth_helper.php lines 18-33
     * 
     * @param array $user ['id' => user_id, 'password' => plain_password]
     * @return bool
     */
    function verify_admin_password($user)
    {
        $userId = is_array($user) ? $user['id'] : $user->id;
        $password = is_array($user) ? $user['password'] : $user->password;
        
        // Get admin user from database
        $admin = DB::table('admins')->where('id', $userId)->first();
        
        if (!$admin) {
            return false;
        }
        
        // Check if password is MD5 (CI format) or Laravel hash
        if (strlen($admin->password) === 32 && ctype_xdigit($admin->password)) {
            // MD5 hash (from CI project)
            return md5($password) === $admin->password;
        } else {
            // Laravel hash
            return \Illuminate\Support\Facades\Hash::check($password, $admin->password);
        }
    }
}

/*
|--------------------------------------------------------------------------
| Etc Helper Functions
| CI: application/helpers/etc_helper.php
|--------------------------------------------------------------------------
*/

/**
 * Convert name to ID format
 * CI: etc_helper.php line 7-10
 */
if (!function_exists('name2id')) {
    function name2id(string $name)
    {
        return str_replace(['[', ']'], ['\\\\[', '\\\\]'], $name);
    }
}

/*
|--------------------------------------------------------------------------
| Option Helper Functions
| CI: application/helpers/option_helper.php
|--------------------------------------------------------------------------
*/

/**
 * Add days to turnaround option string
 * CI: option_helper.php line 7-21
 */
if (!function_exists('option_turnaround_add_days')) {
    function option_turnaround_add_days(string $str, int $extra_days)
    {
        preg_match_all('/\d+/', $str, $matches, PREG_OFFSET_CAPTURE);
        $result = '';
        $offset = 0;
        foreach ($matches[0] as $match) {
            if ($match[1] > $offset) {
                $result .= substr($str, $offset, $match[1] - 1);
            }
            $result .= $match[0] + $extra_days;
            $offset = $match[1] + strlen($match[0]);
        }
        $result .= substr($str, $offset);
        return $result;
    }
}

/*
|--------------------------------------------------------------------------
| Sina Helper Functions (Provider API)
| CI: application/helpers/sina_helper.php
|--------------------------------------------------------------------------
*/

/**
 * CURL helper for API requests
 * CI: sina_helper.php line 10-46
 */
if (!function_exists('curl_helper')) {
    function curl_helper(string $url, string $method = 'get', array $data = null, string $token = null)
    {
        if (strcasecmp($method, 'post') != 0 && strcasecmp($method, 'get') != 0) {
            return null; // Unsupported yet
        }

        if (strcasecmp($method, 'get') == 0 && $data) {
            $url = "$url?" . http_build_query($data);
        }
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        
        if (strcasecmp($method, 'post') == 0) {
            curl_setopt($ch, CURLOPT_POST, true);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // 10 second timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // 5 second connection timeout

        $headers = [
            "Accept: application/json",
            "Content-Type: application/json",
        ];
        
        if ($token) {
            $headers[] = "Authorization: Bearer $token";
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (strcasecmp($method, 'post') == 0 && $data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $resp = curl_exec($ch);
        curl_close($ch);

        if ($resp === false) {
            return false;
        }

        return json_decode($resp) ?? $resp;
    }
}

/**
 * Get Sina access token
 * CI: sina_helper.php line 48-63
 */
if (!function_exists('sina_access_token')) {
    function sina_access_token()
    {
        $sina = config('sina');
        $url = $sina['endpoint'] . "/auth/token";
        $data = [
            'client_id' => $sina['client_id'],
            'client_secret' => $sina['client_secret'],
            'audience' => $sina['audience'],
            'grant_type' => $sina['grant_type'],
        ];
        $resp = curl_helper($url, 'post', $data);

        if ($resp === false) {
            return false;
        }
        
        return $resp->access_token;
    }
}

/**
 * Get Sina products
 * CI: sina_helper.php line 65-70
 */
if (!function_exists('sina_products')) {
    function sina_products()
    {
        $sina = config('sina');
        $url = $sina['endpoint'] . "/product";
        return curl_helper($url, 'get');
    }
}

/**
 * Get Sina product info
 * CI: sina_helper.php line 72-77
 */
if (!function_exists('sina_product_info')) {
    function sina_product_info(int $product_id, string $store = 'en_us')
    {
        $sina = config('sina');
        $url = $sina['endpoint'] . "/product/$product_id/$store";
        return curl_helper($url, 'get');
    }
}

/**
 * Get Sina price
 * CI: sina_helper.php line 79-84
 */
if (!function_exists('sina_price')) {
    function sina_price(int $product_id, array $options, string $store = 'en_us')
    {
        $sina = config('sina');
        $url = $sina['endpoint'] . "/price/$product_id/$store";
        return curl_helper($url, 'post', ['productOptions' => $options]);
    }
}

/**
 * Get Sina shipping estimate
 * CI: sina_helper.php line 86-94
 */
if (!function_exists('sina_order_shippingEstimate')) {
    function sina_order_shippingEstimate($items, $shippingInfo, $token)
    {
        $sina = config('sina');
        $url = $sina['endpoint'] . "/order/shippingEstimate";
        return curl_helper($url, 'post', [
            'items' => $items,
            'shippingInfo' => $shippingInfo,
        ], $token);
    }
}

/**
 * Create new Sina order
 * CI: sina_helper.php line 96-106
 */
if (!function_exists('sina_order_new')) {
    function sina_order_new($items, $shippingInfo, $billingInfo, $token)
    {
        $sina = config('sina');
        $url = $sina['endpoint'] . "/order/new";
        return curl_helper($url, 'post', [
            'items' => $items,
            'shippingInfo' => $shippingInfo,
            'billingInfo' => $billingInfo,
            'notes' => 'Business Card Test Order',
        ], $token);
    }
}

/**
 * Get Sina options from attribute IDs
 * CI: sina_helper.php line 111-131
 */
if (!function_exists('sina_options')) {
    function sina_options($attribute_ids)
    {
        $itemInfo = is_string($attribute_ids) ? json_decode($attribute_ids) : $attribute_ids;
        
        if (!is_object($itemInfo) || empty($itemInfo->provider_id)) {
            return false;
        }

        $providerProduct = DB::table('provider_products')
            ->where('provider_id', $itemInfo->provider_id)
            ->where('provider_product_id', $itemInfo->provider_product_id)
            ->first();

        if (!$providerProduct) {
            return false;
        }

        $itemInfo->information_type = $providerProduct->information_type;
        $itemInfo->options = [];

        if ($providerProduct->information_type == 1) { // Normal
            $optionValueIds = array_values((array)$itemInfo->provider_options);
            
            $data = DB::table('provider_options')
                ->where('provider_id', $itemInfo->provider_id)
                ->where('provider_product_id', $itemInfo->provider_product_id)
                ->whereIn('provider_option_value_id', $optionValueIds)
                ->get();

            foreach ($data as $option) {
                $itemInfo->options[$option->name] = $option->provider_option_value_id;
            }
        } else if ($providerProduct->information_type == 2) { // RollLabel
            $itemInfo->options = $itemInfo->provider_options;
        }

        return $itemInfo;
    }
}

/**
 * Get raw Sina options
 * CI: sina_helper.php line 133-150
 */
if (!function_exists('sina_options_raw')) {
    function sina_options_raw($attribute_ids)
    {
        $itemInfo = is_string($attribute_ids) ? json_decode($attribute_ids) : $attribute_ids;
        
        if (!is_object($itemInfo) || empty($itemInfo->provider_id)) {
            return false;
        }

        $providerProduct = DB::table('provider_products')
            ->where('provider_id', $itemInfo->provider_id)
            ->where('provider_product_id', $itemInfo->provider_product_id)
            ->first();

        if (!$providerProduct) {
            return false;
        }

        $itemInfo->information_type = $providerProduct->information_type;

        if ($providerProduct->information_type == 1) { // Normal
            $optionValueIds = array_values((array)$itemInfo->provider_options);
            
            $itemInfo->options = DB::table('provider_options')
                ->where('provider_id', $itemInfo->provider_id)
                ->where('provider_product_id', $itemInfo->provider_product_id)
                ->whereIn('provider_option_value_id', $optionValueIds)
                ->get();
        } else if ($providerProduct->information_type == 2) { // RollLabel
            $itemInfo->options = $itemInfo->provider_options;
        }

        return $itemInfo;
    }
}

/**
 * Map Sina options to display format
 * CI: sina_helper.php line 152-199
 */
if (!function_exists('sina_options_map')) {
    function sina_options_map($attribute_ids)
    {
        $itemInfo = sina_options_raw($attribute_ids);
        
        if (!$itemInfo) {
            return false;
        }

        $providerOptions = DB::table('provider_options')
            ->where('provider_id', $itemInfo->provider_id)
            ->get();

        $providerOptionsByName = [];
        foreach ($providerOptions as $option) {
            $providerOptionsByName[$option->name] = $option;
        }

        $func = function($key, $option) use ($providerOptionsByName) {
            if (is_object($option)) {
                if ($option->type == 1) { // Quantity
                    $attribute_name = 'Quantity';
                    $attribute_name_french = 'Quantité';
                } else if ($option->type == 2) { // Size
                    $attribute_name = 'Size';
                    $attribute_name_french = 'Taille';
                } else {
                    $attribute_name = $option->attribute_name ?? $option->label ?? $option->name;
                    $attribute_name_french = $option->attribute_name_french ?? $option->attribute_name ?? $option->label ?? $option->name;
                }
                return [
                    'attribute_name' => ucfirst($attribute_name),
                    'attribute_name_french' => ucfirst($attribute_name_french),
                    'item_name' => ucfirst($option->value),
                    'item_name_french' => ucfirst($option->value)
                ];
            } else {
                $providerOption = $providerOptionsByName[$key] ?? null;
                if ($providerOption) {
                    if ($providerOption->type == 1) { // Quantity
                        $attribute_name = 'Quantity';
                        $attribute_name_french = 'Quantité';
                    } else if ($providerOption->type == 2) { // Size
                        $attribute_name = 'Size';
                        $attribute_name_french = 'Taille';
                    } else {
                        $attribute_name = $providerOption->attribute_name ?? $providerOption->label ?? $providerOption->name;
                        $attribute_name_french = $providerOption->attribute_name_french ?? $providerOption->attribute_name ?? $providerOption->label ?? $providerOption->name;
                    }
                    return [
                        'attribute_name' => ucfirst($attribute_name),
                        'attribute_name_french' => ucfirst($attribute_name_french),
                        'item_name' => ucfirst($option),
                        'item_name_french' => ucfirst($option)
                    ];
                } else {
                    return [
                        'attribute_name' => ucfirst($key),
                        'attribute_name_french' => ucfirst($key),
                        'item_name' => ucfirst($option),
                        'item_name_french' => ucfirst($option)
                    ];
                }
            }
        };
        
        return array_map($func, array_keys((array)$itemInfo->options), (array)$itemInfo->options);
    }
}

/**
 * Get Sina shipping methods for order
 * CI: sina_helper.php line 201-242
 */
if (!function_exists('sina_shipping_methods')) {
    function sina_shipping_methods($order_id)
    {
        $order = DB::table('product_orders')->where('id', $order_id)->first();
        
        if (!$order) {
            return [];
        }

        $orderItems = DB::table('product_order_items')->where('order_id', $order_id)->get();

        $items = [];
        foreach ($orderItems as $item) {
            $itemInfo = sina_options($item->attribute_ids);
            if (!$itemInfo) {
                continue;
            }
            $items[] = [
                'productId' => $itemInfo->provider_product_id,
                'options' => $itemInfo->options,
            ];
        }

        $country = DB::table('countries')->where('id', $order->shipping_country)->first();
        $state = DB::table('states')->where('id', $order->shipping_state)->first();

        if (count($items) > 0 && $state && $country) {
            $shippingInfo = [
                'ShipState' => $state ? $state->iso2 : '',
                'ShipZip' => $order->shipping_pin_code,
                'ShipCountry' => $country ? $country->iso2 : null,
            ];

            $token = sina_access_token();
            $response = sina_order_shippingEstimate($items, $shippingInfo, $token);

            if (isset($response->statusCode) && $response->statusCode == 200) {
                return $response->body;
            }
        }

        return [];
    }
}

/**
 * Get Sina order info
 * CI: sina_helper.php line 244-249
 */
if (!function_exists('sina_order_info')) {
    function sina_order_info(string $token, int $order_id)
    {
        $sina = config('sina');
        $url = $sina['endpoint'] . "/order/$order_id";
        return curl_helper($url, 'get', null, $token);
    }
}

/*
|--------------------------------------------------------------------------
| Additional Helper Functions
| Common functions used throughout the application
|--------------------------------------------------------------------------
*/

/**
 * Get base URL
 * Replicate CI base_url() helper
 */
if (!function_exists('base_url')) {
    function base_url($uri = '')
    {
        return url($uri);
    }
}

/**
 * Get site URL
 * Replicate CI site_url() helper
 */
if (!function_exists('site_url')) {
    function site_url($uri = '')
    {
        return url($uri);
    }
}

/**
 * Redirect helper
 * Replicate CI redirect() helper
 */
if (!function_exists('redirect_to')) {
    function redirect_to($uri = '', $method = 'auto', $code = null)
    {
        return redirect($uri);
    }
}

/**
 * Format date
 * Common date formatting function
 */
if (!function_exists('dateFormate')) {
    function dateFormate($date, $format = 'Y-m-d H:i:s')
    {
        if (empty($date) || $date === '0000-00-00 00:00:00') {
            return '';
        }
        return date($format, strtotime($date));
    }
}

/**
 * Get order status class
 * Returns CSS class based on order status
 */
if (!function_exists('getOrderSatusClass')) {
    function getOrderSatusClass($status)
    {
        $classes = [
            1 => 'label-warning',  // Pending
            2 => 'label-info',      // Processing
            3 => 'label-primary',   // Shipped
            4 => 'label-success',   // Delivered
            5 => 'label-danger',    // Cancelled
        ];
        
        return $classes[$status] ?? 'label-default';
    }
}

/**
 * Get product image with fallback to default
 * CI: constants.php line 232-280
 * Checks if image file exists, returns default image if not found
 */
if (!function_exists('getProductImage')) {
    function getProductImage($imageName = null, $type = 'small')
    {
        $imageurl = '';
        
        if (!empty($imageName)) {
            switch ($type) {
                case 'small':
                    if (file_exists(public_path('uploads/products/small/' . $imageName))) {
                        $imageurl = asset('uploads/products/small/' . $imageName);
                    }
                    break;
                case 'medium':
                    if (file_exists(public_path('uploads/products/medium/' . $imageName))) {
                        $imageurl = asset('uploads/products/medium/' . $imageName);
                    }
                    break;
                case 'large':
                    if (file_exists(public_path('uploads/products/large/' . $imageName))) {
                        $imageurl = asset('uploads/products/large/' . $imageName);
                    }
                    break;
                default:
                    if (file_exists(public_path('uploads/products/' . $imageName))) {
                        $imageurl = asset('uploads/products/' . $imageName);
                    }
            }
        }
        
        // If image not found, check banner folder as fallback (CI line 270-272)
        if (empty($imageurl) && !empty($imageName)) {
            if (file_exists(public_path('uploads/banners/' . $imageName))) {
                $imageurl = asset('uploads/banners/' . $imageName);
            }
        }
        
        // Final fallback to default image (CI line 274-275)
        if (empty($imageurl)) {
            $imageurl = asset('defaults/product-no-image.png');
        }
        
        return $imageurl;
    }
}

/**
 * Get banner/service image with fallback to default
 * CI: constants.php line 282-330
 * Used for banners, services, and other non-product images
 */
if (!function_exists('getBannerImage')) {
    function getBannerImage($imageName = null, $type = 'small')
    {
        $imageurl = '';
        
        if (!empty($imageName)) {
            switch ($type) {
                case 'small':
                    if (file_exists(public_path('uploads/banners/small/' . $imageName))) {
                        $imageurl = asset('uploads/banners/small/' . $imageName);
                    }
                    break;
                case 'medium':
                    if (file_exists(public_path('uploads/banners/medium/' . $imageName))) {
                        $imageurl = asset('uploads/banners/medium/' . $imageName);
                    }
                    break;
                case 'large':
                    if (file_exists(public_path('uploads/banners/large/' . $imageName))) {
                        $imageurl = asset('uploads/banners/large/' . $imageName);
                    }
                    break;
                default:
                    if (file_exists(public_path('uploads/banners/' . $imageName))) {
                        $imageurl = asset('uploads/banners/' . $imageName);
                    }
            }
        }
        
        // Check base banner folder if not found in size-specific folder
        if (empty($imageurl) && !empty($imageName)) {
            if (file_exists(public_path('uploads/banners/' . $imageName))) {
                $imageurl = asset('uploads/banners/' . $imageName);
            }
        }
        
        // Final fallback to default banner image
        if (empty($imageurl)) {
            $imageurl = asset('defaults/banner-no-image.png');
        }
        
        return $imageurl;
    }
}

/**
 * Get blog image with fallback to default
 * CI: constants.php line 406-454
 * Checks if image file exists in size-specific folders, returns default if not found
 */
if (!function_exists('getBlogImage')) {
    function getBlogImage($imageName = null, $type = 'small')
    {
        $imageurl = '';
        
        if (!empty($imageName)) {
            switch ($type) {
                case 'small':
                    if (file_exists(public_path('uploads/blogs/small/' . $imageName))) {
                        $imageurl = asset('uploads/blogs/small/' . $imageName);
                    }
                    break;
                case 'medium':
                    if (file_exists(public_path('uploads/blogs/medium/' . $imageName))) {
                        $imageurl = asset('uploads/blogs/medium/' . $imageName);
                    }
                    break;
                case 'large':
                    if (file_exists(public_path('uploads/blogs/large/' . $imageName))) {
                        $imageurl = asset('uploads/blogs/large/' . $imageName);
                    }
                    break;
                default:
                    if (file_exists(public_path('uploads/blogs/' . $imageName))) {
                        $imageurl = asset('uploads/blogs/' . $imageName);
                    }
            }
        }
        
        // Fallback to base blogs folder if not found in size-specific folder
        if (empty($imageurl) && !empty($imageName)) {
            if (file_exists(public_path('uploads/blogs/' . $imageName))) {
                $imageurl = asset('uploads/blogs/' . $imageName);
            }
        }
        
        // Final fallback to default banner image
        if (empty($imageurl)) {
            $imageurl = asset('defaults/banner-no-image.png');
        }
        
        return $imageurl;
    }
}

/**
 * Generate URL that works with or without index.php
 * Detects if current request has index.php and preserves it in generated URLs
 * This ensures pagination and other links work on servers without mod_rewrite
 */
if (!function_exists('site_url')) {
    function site_url($path = '')
    {
        $baseUrl = url('/');
        
        // Check if the current request URL contains index.php
        $currentUrl = request()->url();
        $hasIndexPhp = strpos($currentUrl, '/index.php') !== false;
        
        // If current URL has index.php, add it to generated URLs
        if ($hasIndexPhp) {
            $baseUrl = url('/index.php');
        }
        
        // Remove leading slash from path if present
        $path = ltrim($path, '/');
        
        // Return the complete URL
        return $path ? $baseUrl . '/' . $path : $baseUrl;
    }
}

/**
 * Check if product can be purchased (Buy Now button)
 * CI: constants.php line 497-507
 */
if (!function_exists('checkBuyNowProduct')) {
    function checkBuyNowProduct($is_stock, $tota_stock)
    {
        $buy = true;
        if (!empty($is_stock)) {
            $buy = false;
        }
        /*if (empty($tota_stock)) {
        $buy = false;
        }*/
        return $buy;
    }
}

/**
 * Send email helper
 * SocketLab mail functions - Based on CI constants.php lines 959-980
 */
if (!function_exists('sendEmail')) {
    function sendEmail($toEmail = null, $sub = null, $body = null, $from = null, $fromname = null, $files = array())
    {
        try {
            // Use Laravel Mail instead of SocketLab for compatibility
            $from = !empty($from) ? $from : env('FROM_EMAIL', 'info@printing.coop');
            $fromname = !empty($fromname) ? $fromname : env('WEBSITE_NAME', 'Printing Coop');
            
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($toEmail, $sub, $body, $from, $fromname, $files) {
                $message->to(trim($toEmail))
                    ->subject($sub)
                    ->from(trim($from), $fromname)
                    ->html($body);
                
                // Handle file attachments
                foreach ($files as $fileName => $path) {
                    if (file_exists($path)) {
                        $message->attach($path, ['as' => $fileName]);
                    }
                }
            });
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Email send error: ' . $e->getMessage());
            return false;
        }
    }
}

/**
 * French email function - Based on CI constants.php lines 982-1000
 */
if (!function_exists('sendEmailFranch')) {
    function sendEmailFranch($toEmail = null, $sub = null, $body = null, $from = null, $fromname = null, $files = array())
    {
        try {
            // Use Laravel Mail instead of SocketLab for compatibility
            $from = !empty($from) ? $from : env('FROM_EMAIL_FRANCH', 'info@imprimeur.coop');
            $fromname = !empty($fromname) ? $fromname : env('WEBSITE_NAME_FRANCH', 'Imprimeur Coop');
            
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($toEmail, $sub, $body, $from, $fromname, $files) {
                $message->to(trim($toEmail))
                    ->subject($sub)
                    ->from(trim($from), $fromname)
                    ->html($body);
                
                // Handle file attachments
                foreach ($files as $fileName => $path) {
                    if (file_exists($path)) {
                        $message->attach($path, ['as' => $fileName]);
                    }
                }
            });
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Email send error: ' . $e->getMessage());
            return false;
        }
    }
}

/**
 * Email template function - Based on CI constants.php lines 764-796
 */
if (!function_exists('emailTemplate')) {
    function emailTemplate($subject, $body, $empty = false, $logo = false)
{
    //<img src="'.url('assets/images/printing.coopLogo.png').' width="60%">
    $logo = $logo ? $logo : 'https://printing.coop/assets/images/printing.coopLogo.png';
    $html = '<div class="top-section" style="width:100%;text-align:center; font-family: Raleway, sans-serif !important;display: flex;justify-content: center;align-items: center;">
        <div class="top-mid-section" style="width:100%; max-width:600px; height:auto; text-align:center; padding:0px 0px 0px 0px; box-shadow: 0px 0px 10px -3px rgba(0,0,0,0.5);background-image: url(https://printing.coop/assets/images/bg-vector-img.jpg);">
            <div style="background: rgba(255,255,255,0.9)">
            <div class="top-inner-section" style="background: #fa762b; padding: 3px 0px 1px 0px; box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.5);">
            </div>
            <div style="padding: 20px 0px 10px 0px; text-align: center;"><img src="' . $logo . '" width="60%"></div>
            <div class="tem-mid-section" style="text-align: center;">
                <div class="tem-visibility" style="z-index: 99; padding: 20px;">
                    <div class="top-title" style="font-size: 22px; text-align: center;">
                        <span><strong>' . $subject . '</strong></span>
                    </div>

                    <div class="email-body">
                        ' . $body . '
                    </div>
                    <div style="background-color: #0086ac;margin-top: 20px;">
                        <div style="padding: 20px;">
                            <span style="color: #fff;line-height: 25px;">We are always here to help. You can also contact us directly on<br>514-544-8043,1-877-384-8043 or email us at info@printing.coop<br>FOLLOW US <br>printing.coop<br>imprimeur.coop<br><br>© Copyright 2019 ' . env('WEBSITE_NAME', 'Printing Coop') . '</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tem-bottom" style="font-size: 18px; letter-spacing: 0.5px; line-height: 30px; background: #22a641;; color: #fff; padding: 3px 0px; text-align: center;">
            </div>
        </div>
    </div>
    </div>';
    return $html;
}
}

/**
 * French email template function - Based on CI constants.php lines 798-828
 */
if (!function_exists('emailTemplateFranch')) {
    function emailTemplateFranch($subject, $body)
    {
        $html = '<div class="top-section" style="width:100%;text-align:center; font-family: Raleway, sans-serif !important;display: flex;justify-content: center;align-items: center;">
            <div class="top-mid-section" style="width:100%; max-width:600px; height:auto; text-align:center; padding:0px 0px 0px 0px; box-shadow: 0px 0px 10px -3px rgba(0,0,0,0.5);background-image: url(https://printing.coop/assets/images/bg-vector-img.jpg);">
                <div style="background: rgba(255,255,255,0.9)">
                <div class="top-inner-section" style="background: #fa762b; padding: 3px 0px 1px 0px; box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.5);">
                </div>
                <div style="padding: 20px 0px 10px 0px; text-align: center;"><img src="https://printing.coop/uploads/logo/printing_coop_imprimeur_coop_logo2018_FR.png" width="60%"></div>
                <div class="tem-mid-section" style="text-align: center;">
                    <div class="tem-visibility" style="z-index: 99; padding: 20px;">
                        <div class="top-title" style="font-size: 22px; text-align: center;">
                            <span><strong>' . $subject . '</strong></span>
                        </div>

                        <div class="email-body">
                            ' . $body . '
                        </div>
                        <div style="background-color: #0086ac;margin-top: 20px;">
                            <div style="padding: 20px;">
                                <span style="color: #fff;line-height: 25px;">Nous sommes toujours là pour vous aider. Vous pouvez également nous contacter directement sur<br>514-544-8043,1-877-384-8043 ou écrivez-nous à info@imprimeur.coop<br>FOLLOW US <br>printing.coop<br>imprimeur.coop<br><br>© droits dauteur 2019 ' . env('WEBSITE_NAME_FRANCH', 'Imprimeur Coop') . '</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tem-bottom" style="font-size: 18px; letter-spacing: 0.5px; line-height: 30px; background: #22a641;; color: #fff; padding: 3px 0px; text-align: center;">
                </div>
            </div>
        </div>
        </div>';
        return $html;
    }
}

/**
 * Add email item helper - Based on CI constants.php line 1924
 */
if (!function_exists('addEmailItem')) {
    function addEmailItem($title, $data)
    {
        return '<b>' . $title . ' </b> : ' . ucfirst($data) . '<br><br>';
    }
}

/**
 * Get logo images helper - Based on CI constants.php lines 1038-1049
 */
if (!function_exists('getLogoImages')) {
    function getLogoImages($imageName = null, $mail = false)
    {
        $imageurl = '';
        $logoPath = public_path('uploads/logo/' . $imageName);
        
        if (file_exists($logoPath)) {
            if ($mail) {
                $imageurl = "https://printing.coop/uploads/logo/" . $imageName;
            } else {
                $imageurl = asset('uploads/logo/' . $imageName);
            }
        }
        
        return $imageurl;
    }
}

/**
 * Get UPS service code names
 * CI: constants.php lines 1051-1068
 */
if (!function_exists('upsServiceCode')) {
    function upsServiceCode()
    {
        $ups_service_code = [
            '01' => 'UPS Next Day Air',
            '02' => 'UPS 2nd Day Air',
            '03' => 'UPS Ground',
            '07' => 'UPS Worldwide Express',
            '08' => 'UPS Worldwide Expedited',
            '11' => 'UPS Standard',
            '12' => 'UPS 3 Day Select',
            '13' => 'UPS Next Day Air Saver',
            '14' => 'UPS Next Day Air Early A.M.',
            '54' => 'UPS Worldwide Express Plus',
            '59' => 'UPS 2nd Day Air AM',
            '65' => 'UPS World Wide Saver',
        ];
        return $ups_service_code;
    }
}

/**
 * Get Canada Post API rates
 * CI: constants.php lines 1072-1200
 */
if (!function_exists('CanedaPostApigetRate')) {
    function CanedaPostApigetRate($postalCode)
    {
        $Rates = ['status' => '404', 'msg' => 'postal-code is not a valid', 'list' => []];
        
        // Canada Post API credentials
        $username = '99ee0c797ced5425';
        $password = 'b638d92827ade27061a7ed';
        $mailedBy = '0008736935';
        
        // REST URL
        $service_url = 'https://ct.soa-gw.canadapost.ca/rs/ship/price';
        
        // Create GetRates request xml
        $originPostalCode = 'H2M1S2';
        $weight = 1;
        
        $xmlRequest = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<mailing-scenario xmlns="http://www.canadapost.ca/ws/ship/rate-v4">
<customer-number>{$mailedBy}</customer-number>
<parcel-characteristics>
    <weight>{$weight}</weight>
</parcel-characteristics>
<origin-postal-code>{$originPostalCode}</origin-postal-code>
<destination>
    <domestic>
    <postal-code>{$postalCode}</postal-code>
    </domestic>
</destination>
</mailing-scenario>
XML;
        
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $service_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/vnd.cpc.ship.rate-v4+xml',
                'Accept: application/vnd.cpc.ship.rate-v4+xml'
            ]);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode == 200 && $response) {
                $xml = simplexml_load_string($response);
                if ($xml) {
                    $Rates['status'] = '200';
                    $Rates['msg'] = 'Success';
                    $Rates['list'] = [];
                    
                    foreach ($xml->{'price-quote'} as $quote) {
                        $Rates['list'][] = [
                            'service_name' => (string)$quote->{'service-name'},
                            'price' => (string)$quote->{'price-details'}->{'due'},
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Canada Post API error: ' . $e->getMessage());
        }
        
        return $Rates;
    }
}
