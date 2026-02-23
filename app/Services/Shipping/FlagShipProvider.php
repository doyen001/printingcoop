<?php

namespace App\Services\Shipping;

class FlagShipProvider
{
    /**
     * Get shipping rates from FlagShip API (replicate getRatesFlagShip lines 1259-1399)
     * 
     * @param array $ProductOrder Order data
     * @param array $ProductOrderItems Order items
     * @param array $CountryData Country data
     * @param array $stateData State data
     * @param array $cityData City data
     * @param array $storeData Store data
     * @return array FlagShip rates
     */
    public function getRates($ProductOrder, $ProductOrderItems, $CountryData, $stateData, $cityData, $storeData)
    {
        // FlagShip configuration (lines 1268-1272)
        $FLAGSHIP_MODE = env('FLAGSHIP_MODE', 'live');
        $API_VERSION = env('FLAGSHIP_API_VERSION', '1.1');
        $MY_ACCESS_TOKEN_LIVE = env('FLAGSHIP_ACCESS_TOKEN_LIVE', 'nXiEiZtRLDzJtIzP1JWVBxv_7biNGkoydoAHO1NfFXA');
        $MY_ACCESS_TOKEN_TEST = env('FLAGSHIP_ACCESS_TOKEN_TEST', 'y-ew5cE7ZN22doaiunkrK8oXHa9_hcQyw2-Esin-10Y');
        
        $API_URL = $FLAGSHIP_MODE == 'live' ? 'https://api.smartship.io' : 'https://test-api.smartship.io';
        $MY_ACCESS_TOKEN = $FLAGSHIP_MODE == 'live' ? $MY_ACCESS_TOKEN_LIVE : $MY_ACCESS_TOKEN_TEST;
        
        $website_name = isset($storeData['website_name']) ? $storeData['website_name'] : 'printing.coop';
        $store_name = isset($storeData['name']) ? $storeData['name'] : 'printing.coop';
        $store_email = isset($storeData['email']) ? $storeData['email'] : 'info@printing.coop';
        
        // Extract shipping information (lines 1274-1286)
        $shipping_name = $ProductOrder['shipping_name'];
        $shipping_address = $ProductOrder['shipping_address'];
        $shipping_city = $cityData['name'];
        $shipping_country = $CountryData['iso2'];
        $shipping_state = $stateData['iso2'];
        $shipping_mobile = $ProductOrder['shipping_mobile'];
        $shipping_pin_code = $ProductOrder['shipping_pin_code'];
        $order_id = $ProductOrder['order_id'];
        
        $reference = $order_id . ' ' . $store_name;
        $driver_instructions = '';
        $user_email = $ProductOrder['email'];
        $id = $ProductOrder['id'];
        $total_amount = $ProductOrder['total_amount'];
        
        // Build items array with dimensions (lines 1287-1322)
        $items = [];
        foreach ($ProductOrderItems as $ProductOrderItem) {
            $name = $ProductOrderItem['name'];
            $shipping_box_length = $ProductOrderItem['shipping_box_length'];
            $shipping_box_width = $ProductOrderItem['shipping_box_width'];
            $shipping_box_height = $ProductOrderItem['shipping_box_height'];
            $shipping_box_weight = $ProductOrderItem['shipping_box_weight'];
            
            // Default dimensions if not set (lines 1297-1308)
            if (empty($shipping_box_length) || $shipping_box_length == 0.00) {
                $shipping_box_length = 12;
            }
            if (empty($shipping_box_width) || $shipping_box_width == 0.00) {
                $shipping_box_width = 9;
            }
            if (empty($shipping_box_height) || $shipping_box_height == 0.00) {
                $shipping_box_height = 3;
            }
            if (empty($shipping_box_weight) || $shipping_box_weight == 0.00) {
                $shipping_box_weight = 3;
            }
            
            // Round up dimensions (lines 1309-1312)
            $shipping_box_width = ceil($shipping_box_width);
            $shipping_box_height = ceil($shipping_box_height);
            $shipping_box_length = ceil($shipping_box_length);
            $shipping_box_weight = ceil($shipping_box_weight);
            
            $items[] = [
                "width" => $shipping_box_width,
                "height" => $shipping_box_height,
                "length" => $shipping_box_length,
                "weight" => $shipping_box_weight,
                "description" => $name,
            ];
        }
        
        // Build FlagShip API payload (lines 1325-1376)
        $payload = [
            'from' => [
                "name" => "printing coop",
                "attn" => "Mehdi Afzali",
                "address" => "9166 rue Lajeunesse",
                "suite" => "",
                "city" => "MONTREAL",
                "country" => "CA",
                "state" => "QC",
                "postal_code" => "H2M1S2",
                "phone" => "5143848043",
                "ext" => "",
                "department" => "",
                "is_commercial" => true,
            ],
            'to' => [
                "name" => $shipping_name,
                "attn" => "",
                "address" => $shipping_address,
                "suite" => "",
                "city" => $shipping_city,
                "country" => $shipping_country,
                "state" => $shipping_state,
                "postal_code" => $shipping_pin_code,
                "phone" => $shipping_mobile,
                "ext" => "",
                "department" => "",
                "is_commercial" => true,
            ],
            'packages' => [
                "items" => $items,
                "units" => "imperial",
                "type" => "package",
                "content" => "goods",
            ],
            'payment' => [
                "payer" => "F",
            ],
            'options' => [
                "signature_required" => false,
                "saturday_delivery" => false,
                "reference" => $reference,
                "driver_instructions" => "",
                "address_correction" => true,
                "return_documents_as" => "url",
                "shipment_tracking_emails" => "$store_email;$user_email",
            ],
        ];
        
        try {
            // Call FlagShip API (lines 1378-1393)
            // Note: This requires the Flagship PHP SDK to be installed
            // For now, we'll use a direct API call approach
            
            $ch = curl_init($API_URL . '/ship/rates');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $MY_ACCESS_TOKEN,
            ]);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $rates = json_decode($response);
            
            // Filter rates by service codes (lines 1384-1390)
            $ratesNew = [];
            $codes = $this->getFlagShipServiceCodes();
            
            if (is_array($rates)) {
                foreach ($rates as $rate) {
                    if (isset($rate->rate->service->courier_code) && array_key_exists($rate->rate->service->courier_code, $codes)) {
                        $ratesNew[] = $rate;
                    }
                }
            }
            
            return $ratesNew;
        } catch (\Exception $e) {
            // Silently handle exceptions as in original (lines 1394-1398)
            return [];
        }
    }
    
    /**
     * Get FlagShip service codes
     * 
     * @return array
     */
    private function getFlagShipServiceCodes()
    {
        return [
            'fedex_ground' => 'FedEx Ground',
            'fedex_express_saver' => 'FedEx Express Saver',
            'fedex_2day' => 'FedEx 2Day',
            'fedex_standard_overnight' => 'FedEx Standard Overnight',
            'ups_ground' => 'UPS Ground',
            'ups_3_day_select' => 'UPS 3 Day Select',
            'ups_2nd_day_air' => 'UPS 2nd Day Air',
            'ups_next_day_air_saver' => 'UPS Next Day Air Saver',
            'purolator_ground' => 'Purolator Ground',
            'purolator_express' => 'Purolator Express',
            'canpar_ground' => 'Canpar Ground',
            'canpar_select_letter' => 'Canpar Select Letter',
        ];
    }
}
