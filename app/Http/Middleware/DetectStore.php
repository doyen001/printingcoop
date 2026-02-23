<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use App\Models\Store;
use App\Models\Currency;

class DetectStore
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get store list data (replicate getStoreListData)
        $StoreListData = $this->getStoreListData();
        $AllCurrencyList = $this->getCurrencyList();
        
        // Detect protocol and host (lines 26-30)
        $HTTP_X_FORWARDED_PROTO = $request->header('X-Forwarded-Proto', $request->getScheme());
        $HTTP_HOST = $request->getHost();
        $FILE_BASE_URL = $HTTP_X_FORWARDED_PROTO . '://' . $HTTP_HOST . '/';
        
        // Set base URL in config (line 30)
        Config::set('app.url', rtrim($FILE_BASE_URL, '/'));
        
        // Default to store ID 1 (line 31)
        $MainStoreData = $StoreListData[1] ?? [];
        
        // Find matching store by URL (lines 32-37)
        foreach ($StoreListData as $key => $val) {
            if ($val['url'] == $FILE_BASE_URL || $val['http_url'] == $FILE_BASE_URL) {
                $MainStoreData = $StoreListData[$key];
                break;
            }
        }
        
        // Set language name (line 40)
        $language_name = $MainStoreData['language_name'] ?? 'english';
        
        // Set main store and website store IDs (lines 44-49)
        $main_store_id = 1;
        $website_store_id = 1;
        if (!empty($MainStoreData)) {
            $main_store_id = $MainStoreData['id'];
            $website_store_id = $MainStoreData['main_store_id'];
        }
        
        // Get store list data for website (line 51)
        $StoreListData = $this->getStoreListData($website_store_id);
        
        // Handle currency change via query parameter (lines 57-72)
        if ($request->has('currency_id') && !empty($request->get('currency_id'))) {
            $currency_id = $request->get('currency_id');
            Cookie::queue('currency_id', $currency_id, 3600 * 24);
            
            $REDIRECT_URL = $request->get('REDIRECT_URL');
            
            // Clear cart (line 66)
            session()->forget('cart');
            
            if (!empty($REDIRECT_URL)) {
                return redirect($REDIRECT_URL);
            } else {
                return redirect('/');
            }
        }
        
        // Set default currency cookie if not exists (lines 74-81)
        if (is_null(Cookie::get('currency_id')) || empty(Cookie::get('currency_id'))) {
            $default_currency_id = $MainStoreData['default_currency_id'] ?? 1;
            Cookie::queue('currency_id', $default_currency_id, 3600 * 24);
        }
        
        // Get default currency (line 83)
        $default_currency_id = !empty(Cookie::get('currency_id')) ? Cookie::get('currency_id') : 1;
        
        // Get currency data (lines 85-94)
        $DefaultcurrencyData = $AllCurrencyList[$default_currency_id] ?? [];
        $product_price_currency = $DefaultcurrencyData['product_price_currency'] ?? 'price';
        $product_price_currency_symbol = $DefaultcurrencyData['symbols'] ?? '$';
        
        // COVID19 message handling (lines 96-108)
        $showCOVID19MSG = true;
        if (!empty(Cookie::get('COVID19MSG'))) {
            $showCOVID19MSG = false;
        }
        
        // Store all data in config for access throughout the application
        Config::set('store.main_store_data', $MainStoreData);
        Config::set('store.language_name', $language_name);
        Config::set('store.main_store_id', $main_store_id);
        Config::set('store.website_store_id', $website_store_id);
        Config::set('store.store_list_data', $StoreListData);
        Config::set('store.currency_list', $MainStoreData['CurrencyList'] ?? []);
        Config::set('store.default_currency_id', $default_currency_id);
        Config::set('store.default_currency_data', $DefaultcurrencyData);
        Config::set('store.product_price_currency', $product_price_currency);
        Config::set('store.product_price_currency_symbol', $product_price_currency_symbol);
        Config::set('store.show_covid19_msg', $showCOVID19MSG);
        Config::set('store.base_url', rtrim($FILE_BASE_URL, '/'));
        
        // Share with views
        view()->share('MainStoreData', $MainStoreData);
        view()->share('language_name', $language_name);
        view()->share('main_store_id', $main_store_id);
        view()->share('website_store_id', $website_store_id);
        view()->share('StoreListData', $StoreListData);
        view()->share('CurrencyList', $MainStoreData['CurrencyList'] ?? []);
        view()->share('default_currency_id', $default_currency_id);
        view()->share('DefaultcurrencyData', $DefaultcurrencyData);
        view()->share('product_price_currency', $product_price_currency);
        view()->share('product_price_currency_symbol', $product_price_currency_symbol);
        view()->share('showCOVID19MSG', $showCOVID19MSG);
        view()->share('BASE_URL', rtrim($FILE_BASE_URL, '/'));
        
        return $next($request);
    }
    
    /**
     * Get store list data (replicate Store_Model->getStoreListData)
     */
    private function getStoreListData($website_store_id = null)
    {
        $query = Store::where('status', 1);
        
        if ($website_store_id !== null) {
            $query->where('main_store_id', $website_store_id);
        }
        
        $stores = $query->get();
        
        $StoreListData = [];
        foreach ($stores as $store) {
            // Get currency list for this store
            $currencyIds = explode(',', $store->currency_id);
            $CurrencyList = [];
            
            foreach ($currencyIds as $currencyId) {
                $currency = Currency::find(trim($currencyId));
                if ($currency) {
                    $CurrencyList[$currency->id] = [
                        'id' => $currency->id,
                        'currency_name' => $currency->currency_name,
                        'symbols' => $currency->symbols,
                        'code' => $currency->code,
                        'order' => $currency->order,
                        'product_price_currency' => $currency->product_price_currency,
                    ];
                }
            }
            
            $StoreListData[$store->id] = [
                'id' => $store->id,
                'name' => $store->name,
                'phone' => $store->phone,
                'email' => $store->email,
                'url' => $store->url,
                'http_url' => $store->http_url,
                'address' => $store->address,
                'currency_id' => $store->currency_id,
                'langue_id' => $store->langue_id,
                'shopping_id' => $store->shopping_id,
                'description' => $store->description,
                'status' => $store->status,
                'default_currency_id' => $store->default_currency_id,
                'stor_type' => $store->stor_type,
                'main_store' => $store->main_store,
                'main_store_id' => $store->main_store_id,
                'order_id_prefix' => $store->order_id_prefix,
                'show_all_categories' => $store->show_all_categories,
                'show_language_translation' => $store->show_language_translation,
                'email_footer_line' => $store->email_footer_line,
                'from_email' => $store->from_email,
                'admin_email1' => $store->admin_email1,
                'admin_email2' => $store->admin_email2,
                'admin_email3' => $store->admin_email3,
                'email_template_logo' => $store->email_template_logo,
                'paypal_business_email' => $store->paypal_business_email,
                'paypal_payment_mode' => $store->paypal_payment_mode,
                'paypal_sandbox_business_email' => $store->paypal_sandbox_business_email,
                'order_pdf_company' => $store->order_pdf_company,
                'invoice_pdf_company' => $store->invoice_pdf_company,
                'pdf_template_logo' => $store->pdf_template_logo,
                'website_name' => $store->website_name,
                'flag_ship' => $store->flag_ship,
                'clover_mode' => $store->clover_mode,
                'clover_sandbox_api_key' => $store->clover_sandbox_api_key,
                'clover_sandbox_secret' => $store->clover_sandbox_secret,
                'clover_api_key' => $store->clover_api_key,
                'clover_secret' => $store->clover_secret,
                'language_name' => $this->getLanguageName($store->langue_id),
                'CurrencyList' => $CurrencyList,
            ];
        }
        
        return $StoreListData;
    }
    
    /**
     * Get currency list (replicate Store_Model->getCurrencyList)
     */
    private function getCurrencyList()
    {
        $currencies = Currency::all();
        
        $AllCurrencyList = [];
        foreach ($currencies as $currency) {
            $AllCurrencyList[$currency->id] = [
                'id' => $currency->id,
                'currency_name' => $currency->currency_name,
                'symbols' => $currency->symbols,
                'code' => $currency->code,
                'order' => $currency->order,
                'product_price_currency' => $currency->product_price_currency,
            ];
        }
        
        return $AllCurrencyList;
    }
    
    /**
     * Get language name based on langue_id
     */
    private function getLanguageName($langue_id)
    {
        // Map langue_id to language name (adjust based on your system)
        $languageMap = [
            1 => 'english',
            2 => 'french',
        ];
        
        return $languageMap[$langue_id] ?? 'english';
    }
}
