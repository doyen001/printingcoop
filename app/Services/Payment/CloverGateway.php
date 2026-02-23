<?php

namespace App\Services\Payment;

class CloverGateway
{
    /**
     * Request card token from Clover (replicate cardPaymentRequest lines 625-662)
     * 
     * @param array $data Card data
     * @param array $mainStoreData Store configuration
     * @return array ['token' => string|false, 'msg' => string]
     */
    public function requestCardToken($data, $mainStoreData)
    {
        // Determine URL based on mode (lines 627-628)
        $url = $mainStoreData['clover_mode'] == 1 
            ? 'https://token.clover.com/v1/' 
            : 'https://token-sandbox.dev.clover.com/v1/';
        
        $apiKey = $mainStoreData['clover_mode'] == 1 
            ? $mainStoreData['clover_api_key'] 
            : $mainStoreData['clover_sandbox_api_key'];
        
        $res = ['token' => false, 'msg' => 'Invalid Card Credentials'];
        
        // Execute curl request (lines 630-648)
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url . "tokens",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Content-Type: application/json",
                "apikey: " . $apiKey,
            ],
        ]);
        
        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);
        
        // Parse response (lines 649-660)
        if ($err) {
            $res['msg'] = $err;
        } else {
            if (isset($response->id)) {
                $res['token'] = $response->id;
                $res['msg'] = "";
            } elseif (isset($response->error->message)) {
                $res['msg'] = $response->error->message;
            } else {
                $res['msg'] = $response->message ?? 'Unknown error';
            }
        }
        
        return $res;
    }
    
    /**
     * Process payment request to Clover (replicate paymentRequest lines 664-704)
     * 
     * @param array $data Payment data
     * @param array $mainStoreData Store configuration
     * @return array ['status' => bool, 'paymentData' => string|false, 'msg' => string]
     */
    public function processPayment($data, $mainStoreData)
    {
        $res = ['status' => false, 'paymentData' => false, 'msg' => "Your Order's Payment Failed"];
        
        // Determine URL and token based on mode (lines 668-669)
        $url = $mainStoreData['clover_mode'] == 1 
            ? "https://scl.clover.com/v1/" 
            : "https://scl-sandbox.dev.clover.com/v1/";
        
        $token = $mainStoreData['clover_mode'] == 1 
            ? $mainStoreData['clover_secret'] 
            : $mainStoreData['clover_sandbox_secret'];
        
        // Execute curl request (lines 670-687)
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url . "charges",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Authorization: Bearer " . $token,
                "Content-Type: application/json",
            ],
        ]);
        
        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);
        
        // Parse response (lines 688-702)
        if ($err) {
            $res['msg'] = $err;
        } else {
            if (isset($response->id) && $response->status == "succeeded") {
                $res['msg'] = 'Your order has been placed Successfully';
                $res['paymentData'] = json_encode($response);
                $res['status'] = true;
            } else {
                $res['msg'] = 'Your order has been placed Unsuccessfully';
                if (isset($response->message)) {
                    $res['msg'] = $response->message;
                }
                $res['paymentData'] = json_encode($response);
            }
        }
        
        return $res;
    }
    
    /**
     * Handle POS payment flow (replicate SubmitOrder POS section lines 546-612)
     * 
     * @param array $request Request data
     * @param array $ProductOrder Order data
     * @param array $mainStoreData Store configuration
     * @return array Order data with payment status
     */
    public function handlePOSPayment($request, $ProductOrder, $mainStoreData)
    {
        $orderData = ['id' => $ProductOrder['id']];
        
        // Validate card information (lines 548)
        if (empty($request['card-num']) || empty($request['ExpMonth']) || empty($request['ExpYear']) || empty($request['cvv'])) {
            return [
                'success' => false,
                'msg' => 'Missing Information.',
                'orderData' => $orderData,
            ];
        }
        
        // Build card data (lines 549-556)
        $data = [
            'card' => [
                'number' => str_replace(" ", "", $request['card-num']),
                'exp_month' => $request['ExpMonth'],
                'exp_year' => $request['ExpYear'],
                'cvv' => $request['cvv'],
            ],
        ];
        
        // Request card token (line 557)
        $card = $this->requestCardToken($data, $mainStoreData);
        
        if (!$card['token']) {
            $orderData['payment_status'] = 2;
            $orderData['transition_remark'] = 'Pending';
            
            return [
                'success' => false,
                'msg' => $card['msg'],
                'orderData' => $orderData,
            ];
        }
        
        // Get currency (lines 559-561)
        $currency = $this->getCurrency($ProductOrder['currency_id']);
        $currency_code = count($currency) > 0 ? $currency['code'] : 'cad';
        
        // Build payment request (lines 562-571)
        $requestData = [
            'amount' => (round($ProductOrder['total_amount']) * 100),
            'currency' => strtolower($currency_code),
            'capture' => 'true',
            'description' => 'Products Order Payment',
            'external_reference_id' => $ProductOrder['id'],
            'receipt_email' => $ProductOrder['email'],
            'source' => $card['token'],
            'ecomind' => 'ecom',
        ];
        
        // Process payment (line 572)
        $response = $this->processPayment($requestData, $mainStoreData);
        
        // Handle payment response (lines 574-596)
        if ($response['status']) {
            $paymentRes = json_decode($response['paymentData']);
            
            $orderData['status'] = 2;
            $orderData['payment_status'] = 2;
            $orderData['transition_remark'] = 'payment success';
            $orderData['payment_method'] = 'POS';
            $orderData['transition_id'] = $paymentRes->id;
            $orderData['paypal_responce'] = $response['paymentData'];
            
            return [
                'success' => true,
                'msg' => $response['msg'],
                'orderData' => $orderData,
            ];
        } else {
            $orderData['payment_status'] = 3;
            $orderData['transition_remark'] = 'payment Failed';
            
            return [
                'success' => false,
                'msg' => $response['msg'],
                'orderData' => $orderData,
            ];
        }
    }
    
    /**
     * Get currency by ID
     * 
     * @param int $currency_id Currency ID
     * @return array Currency data
     */
    private function getCurrency($currency_id)
    {
        $currency = \DB::table('currencies')->where('id', $currency_id)->first();
        return $currency ? (array) $currency : [];
    }
}
