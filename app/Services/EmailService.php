<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderEmail;
use App\Mail\GenericEmail;

/**
 * Email Service (replicate CI MY_Controller email methods)
 * 
 * Methods:
 * - getorderEmail() - Order confirmation email (lines 133-182)
 * - getorderEmailFrance() - French order email (lines 184-218)
 * - emailTemplate() - Generic email template (lines 396-420)
 */
class EmailService
{
    /**
     * Get order email HTML (replicate CI MY_Controller->getorderEmail lines 133-182)
     * 
     * @param int $id Order ID
     * @param string $heding Email heading
     * @param string|null $body Email body content
     * @param int $store_id Store ID
     * @return string Email HTML
     */
    public function getorderEmail($id, $heding = "Order Confirmation", $body = null, $store_id = 1)
    {
        $orderData = $this->getProductOrderDataById($id);
        $OrderItemData = $this->getProductOrderItemDataById($id);
        $StoreData = $this->getStoreDataById($store_id);
        
        $stateData = $this->getStateById($orderData['billing_state']);
        $countryData = $this->getCountryById($orderData['billing_country']);
        $cityData = $this->getCityById($orderData['billing_city']);
        $salesTaxRatesProvinces_Data = $this->salesTaxRatesProvincesById($orderData['billing_state']);
        
        $CurrencyList = $this->getCurrencyList();
        $currency_id = $orderData['currency_id'];
        if (empty($currency_id)) {
            $currency_id = 1;
        }
        
        $OrderCurrencyData = $CurrencyList[$currency_id];
        $order_currency_currency_symbol = $OrderCurrencyData['symbols'];
        
        $data = [
            'page_title' => 'Order details',
            'orderData' => $orderData,
            'OrderItemData' => $OrderItemData,
            'cityData' => $cityData,
            'stateData' => $stateData,
            'countryData' => $countryData,
            'heding' => $heding,
            'body' => $body,
            'OrderCurrencyData' => $OrderCurrencyData,
            'order_currency_currency_symbol' => $order_currency_currency_symbol,
            'salesTaxRatesProvinces_Data' => $salesTaxRatesProvinces_Data,
            'StoreData' => $StoreData,
        ];
        
        // Determine template based on store and language (lines 169-181)
        if ($StoreData['main_store_id'] == 5) {
            if ($StoreData['langue_id'] == 2) {
                return view('emails.fr_ecoink_order', $data)->render();
            } else {
                return view('emails.ecoink_order', $data)->render();
            }
        } else {
            if ($StoreData['langue_id'] == 2) {
                return view('emails.fr_order', $data)->render();
            } else {
                return view('emails.order', $data)->render();
            }
        }
    }
    
    /**
     * Get French order email HTML (replicate CI MY_Controller->getorderEmailFrance lines 184-218)
     * 
     * @param int $id Order ID
     * @param string $heding Email heading
     * @param string|null $body Email body content
     * @return string Email HTML
     */
    public function getorderEmailFrance($id, $heding = "Order Confirmation", $body = null)
    {
        $orderData = $this->getProductOrderDataById($id);
        $OrderItemData = $this->getProductOrderItemDataById($id);
        
        $stateData = $this->getStateById($orderData['billing_state']);
        $countryData = $this->getCountryById($orderData['billing_country']);
        $cityData = $this->getCityById($orderData['billing_city']);
        $salesTaxRatesProvinces_Data = $this->salesTaxRatesProvincesById($orderData['billing_state']);
        
        $CurrencyList = $this->getCurrencyList();
        $currency_id = $orderData['currency_id'];
        if (empty($currency_id)) {
            $currency_id = 1;
        }
        
        $OrderCurrencyData = $CurrencyList[$currency_id];
        $order_currency_currency_symbol = $OrderCurrencyData['symbols'];
        
        $data = [
            'page_title' => 'Order details',
            'orderData' => $orderData,
            'OrderItemData' => $OrderItemData,
            'cityData' => $cityData,
            'stateData' => $stateData,
            'countryData' => $countryData,
            'heding' => $heding,
            'body' => $body,
            'OrderCurrencyData' => $OrderCurrencyData,
            'order_currency_currency_symbol' => $order_currency_currency_symbol,
            'salesTaxRatesProvinces_Data' => $salesTaxRatesProvinces_Data,
        ];
        
        return view('emails.fr_order', $data)->render();
    }
    
    /**
     * Generic email template (replicate CI MY_Controller->emailTemplate lines 396-420)
     * 
     * @param string $subject Email subject
     * @param string $body Email body
     * @param int $store_id Store ID
     * @return string Email HTML
     */
    public function emailTemplate($subject, $body, $store_id = 1)
    {
        if (empty($store_id)) {
            $store_id = 1;
        }
        
        $StoreData = $this->getStoreDataById($store_id);
        
        $data = [
            'subject' => $subject,
            'body' => $body,
            'StoreData' => $StoreData,
        ];
        
        // Determine template based on store and language (lines 407-419)
        if ($StoreData['main_store_id'] == 5) {
            if ($StoreData['langue_id'] == 2) {
                return view('emails.fr_ecoink_email', $data)->render();
            } else {
                return view('emails.ecoink_email', $data)->render();
            }
        } else {
            if ($StoreData['langue_id'] == 2) {
                return view('emails.fr_email', $data)->render();
            } else {
                return view('emails.email', $data)->render();
            }
        }
    }
    
    // ========== Helper Methods ==========
    
    private function getProductOrderDataById($id)
    {
        $order = DB::table('product_orders')->where('id', $id)->first();
        return $order ? (array) $order : [];
    }
    
    private function getProductOrderItemDataById($id)
    {
        $items = DB::table('product_order_items')->where('order_id', $id)->get();
        return array_map(function($item) {
            return (array) $item;
        }, $items->toArray());
    }
    
    private function getStoreDataById($store_id)
    {
        $store = DB::table('stores')->where('id', $store_id)->first();
        return $store ? (array) $store : [];
    }
    
    private function getStateById($state_id)
    {
        $state = DB::table('states')->where('id', $state_id)->first();
        return $state ? (array) $state : [];
    }
    
    private function getCountryById($country_id)
    {
        $country = DB::table('countries')->where('id', $country_id)->first();
        return $country ? (array) $country : [];
    }
    
    private function getCityById($city_id)
    {
        $city = DB::table('cities')->where('id', $city_id)->first();
        return $city ? (array) $city : [];
    }
    
    private function salesTaxRatesProvincesById($state_id)
    {
        $tax = DB::table('sales_tax_rates_provinces')->where('state_id', $state_id)->first();
        return $tax ? (array) $tax : [];
    }
    
    private function getCurrencyList()
    {
        $currencyTable = \Schema::hasTable('currencies') ? 'currencies' : 'currency';
        $currencies = DB::table($currencyTable)->get();
        $result = [];
        
        foreach ($currencies as $currency) {
            $result[$currency->id] = (array) $currency;
        }
        
        return $result;
    }
}
