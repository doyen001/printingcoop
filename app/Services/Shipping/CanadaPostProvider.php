<?php

namespace App\Services\Shipping;

class CanadaPostProvider
{
    /**
     * Get shipping rates from Canada Post API (replicate CanedaPostApigetRate lines 1070-1156)
     * 
     * @param string $postalCode Destination postal code
     * @return array ['status' => int, 'msg' => string, 'list' => array]
     */
    public function getRates($postalCode)
    {
        $Rates = ['status' => '404', 'msg' => 'postal-code is not a valid', 'list' => []];
        
        // Canada Post API credentials (lines 1073-1075)
        $username = '99ee0c797ced5425';
        $password = 'b638d92827ade27061a7ed';
        $mailedBy = '0008736935';
        
        // REST URL (line 1078)
        $service_url = 'https://ct.soa-gw.canadapost.ca/rs/ship/price';
        
        // Create GetRates request xml (lines 1080-1099)
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
        
        // Execute REST Request (lines 1101-1119)
        $curl = curl_init($service_url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_CAINFO, storage_path('app/certs/cacert.pem'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlRequest);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/vnd.cpc.ship.rate-v4+xml',
            'Accept: application/vnd.cpc.ship.rate-v4+xml'
        ]);
        
        $curl_response = curl_exec($curl);
        
        if (curl_errno($curl)) {
            // Handle curl error silently as in original
        }
        
        $Rates['status'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        
        // Parse XML response (lines 1121-1153)
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string('<root>' . preg_replace('/<\?xml.*\?>/', '', $curl_response) . '</root>');
        
        if (!$xml) {
            // Failed loading XML - errors suppressed as in original
            foreach (libxml_get_errors() as $error) {
                // Errors suppressed
            }
        } else {
            if ($xml->{'price-quotes'}) {
                $priceQuotes = $xml->{'price-quotes'}->children('http://www.canadapost.ca/ws/ship/rate-v4');
                
                if ($priceQuotes->{'price-quote'}) {
                    foreach ($priceQuotes as $priceQuote) {
                        $array = json_decode(json_encode($priceQuote), true);
                        $service_name = $array['service-name'];
                        $list = [];
                        $list['service_name'] = $service_name;
                        $price = $array['price-details']['due'];
                        $list['price'] = $price;
                        $Rates['list'][] = $list;
                        $Rates['msg'] = "";
                    }
                }
            }
            
            if ($xml->{'messages'}) {
                $messages = $xml->{'messages'}->children('http://www.canadapost.ca/ws/messages');
                foreach ($messages as $message) {
                    // Error messages suppressed as in original
                    // echo 'Error Code: ' . $message->code . "\n";
                    // echo 'Error Msg: ' . $message->description . "\n\n";
                }
            }
        }
        
        return $Rates;
    }
}
