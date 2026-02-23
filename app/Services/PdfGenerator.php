<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * PDF Generator Service (replicate CI MY_Controller PDF methods)
 * 
 * Methods:
 * - getOrderInvoicePdf() - Invoice PDF (lines 220-273)
 * - getOrderPdf() - Order PDF (lines 275-326)
 * - getOrderInvoicePdfFrance() - French invoice PDF (lines 328-360)
 * - getOrderPdfFrance() - French order PDF (lines 362-394)
 */
class PdfGenerator
{
    /**
     * Get order invoice PDF HTML (replicate CI MY_Controller->getOrderInvoicePdf lines 220-273)
     * 
     * @param int $id Order ID
     * @param int $store_id Store ID
     * @return string PDF HTML
     */
    public function getOrderInvoicePdf($id, $store_id = 1)
    {
        if (empty($store_id)) {
            $store_id = 1;
        }
        
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
            'OrderCurrencyData' => $OrderCurrencyData,
            'order_currency_currency_symbol' => $order_currency_currency_symbol,
            'salesTaxRatesProvinces_Data' => $salesTaxRatesProvinces_Data,
            'StoreData' => $StoreData,
        ];
        
        // Determine template based on store and language (lines 260-272)
        if ($StoreData['main_store_id'] == 5) {
            if ($StoreData['langue_id'] == 2) {
                return view('pdf.fr_ecoink_invoice-pdf', $data)->render();
            } else {
                return view('pdf.ecoink_invoice-pdf', $data)->render();
            }
        } else {
            if ($StoreData['langue_id'] == 2) {
                return view('pdf.fr_invoice-pdf', $data)->render();
            } else {
                return view('pdf.invoice-pdf', $data)->render();
            }
        }
    }
    
    /**
     * Get order PDF HTML (replicate CI MY_Controller->getOrderPdf lines 275-326)
     * 
     * @param int $id Order ID
     * @param int $store_id Store ID
     * @return string PDF HTML
     */
    public function getOrderPdf($id, $store_id = 1)
    {
        if (empty($store_id)) {
            $store_id = 1;
        }
        
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
            'OrderCurrencyData' => $OrderCurrencyData,
            'order_currency_currency_symbol' => $order_currency_currency_symbol,
            'salesTaxRatesProvinces_Data' => $salesTaxRatesProvinces_Data,
            'StoreData' => $StoreData,
        ];
        
        // Determine template based on store and language (lines 313-325)
        if ($StoreData['main_store_id'] == 5) {
            if ($StoreData['langue_id'] == 2) {
                return view('pdf.fr_ecoink_order-pdf', $data)->render();
            } else {
                return view('pdf.ecoink_order-pdf', $data)->render();
            }
        } else {
            if ($StoreData['langue_id'] == 2) {
                return view('pdf.fr_order-pdf', $data)->render();
            } else {
                return view('pdf.order-pdf', $data)->render();
            }
        }
    }
    
    /**
     * Get French invoice PDF HTML (replicate CI MY_Controller->getOrderInvoicePdfFrance lines 328-360)
     * 
     * @param int $id Order ID
     * @return string PDF HTML
     */
    public function getOrderInvoicePdfFrance($id)
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
            'OrderCurrencyData' => $OrderCurrencyData,
            'order_currency_currency_symbol' => $order_currency_currency_symbol,
            'salesTaxRatesProvinces_Data' => $salesTaxRatesProvinces_Data,
        ];
        
        return view('pdf.fr_invoice-pdf', $data)->render();
    }
    
    /**
     * Get French order PDF HTML (replicate CI MY_Controller->getOrderPdfFrance lines 362-394)
     * 
     * @param int $id Order ID
     * @return string PDF HTML
     */
    public function getOrderPdfFrance($id)
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
            'OrderCurrencyData' => $OrderCurrencyData,
            'order_currency_currency_symbol' => $order_currency_currency_symbol,
            'salesTaxRatesProvinces_Data' => $salesTaxRatesProvinces_Data,
        ];
        
        return view('pdf.fr_order-pdf', $data)->render();
    }
    
    /**
     * Generate PDF from HTML
     * 
     * @param string $html HTML content
     * @param string $filename Output filename
     * @param string $output Output mode: 'I' (inline), 'D' (download), 'F' (file), 'S' (string)
     * @return mixed
     */
    public function generatePdf($html, $filename = 'document.pdf', $output = 'I')
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        switch ($output) {
            case 'I': // Inline (browser)
                return $dompdf->stream($filename, ['Attachment' => false]);
            case 'D': // Download
                return $dompdf->stream($filename, ['Attachment' => true]);
            case 'F': // File
                $pdfOutput = $dompdf->output();
                file_put_contents($filename, $pdfOutput);
                return $filename;
            case 'S': // String
                return $dompdf->output();
            default:
                return $dompdf->stream($filename, ['Attachment' => false]);
        }
    }
    
    /**
     * Save PDF to file
     * 
     * @param string $html HTML content
     * @param string $filepath Full file path
     * @return string File path
     */
    public function savePdfToFile($html, $filepath)
    {
        return $this->generatePdf($html, $filepath, 'F');
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
        $currencies = DB::table('currencies')->get();
        $result = [];
        
        foreach ($currencies as $currency) {
            $result[$currency->id] = (array) $currency;
        }
        
        return $result;
    }
}
