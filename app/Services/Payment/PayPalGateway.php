<?php

namespace App\Services\Payment;

class PayPalGateway
{
    /**
     * Handle PayPal success response (replicate PayPalSuccessResponse lines 706-754)
     * 
     * @param int $order_id Order ID
     * @param array $request Request data
     * @return array Order data with status
     */
    public function handleSuccessResponse($order_id, $request)
    {
        $orderData = [];
        $payment_status = $request['payment_status'] ?? '';
        $txn_id = $request['txn_id'] ?? '';
        $PayerID = $request['PayerID'] ?? '';
        
        if (!empty($order_id)) {
            $orderData['id'] = $order_id;
            $orderData['status'] = 2;
            $orderData['payment_method'] = 'paypal';
            $orderData['transition_id'] = $txn_id;
            
            if (!empty($request)) {
                $orderData['paypal_responce'] = json_encode($request);
            }
            
            // Handle payment status (lines 726-746)
            if ($payment_status == 'Completed' || $payment_status == 'completed') {
                $orderData['payment_status'] = 2;
                $orderData['transition_remark'] = 'payment success';
            } else if ($payment_status == 'Pending' || $payment_status == 'pending') {
                $orderData['payment_status'] = 1;
                $orderData['transition_remark'] = 'payment Pending';
            } else {
                $orderData['payment_status'] = 3;
                $orderData['transition_remark'] = 'payment Failed';
                
                // Fallback to PayerID if txn_id is missing (lines 737-743)
                if (!empty($PayerID)) {
                    $orderData['payment_status'] = 2;
                    $orderData['transition_remark'] = 'payment success';
                    if (empty($txn_id)) {
                        $orderData['transition_id'] = $PayerID;
                    }
                }
            }
        }
        
        return $orderData;
    }
    
    /**
     * Handle PayPal IPN (Instant Payment Notification) (replicate PayPalIPNResponse lines 759-888)
     * 
     * @param int $order_id Order ID
     * @param array $storeData Store configuration
     * @return array Order data with status
     */
    public function handleIPNResponse($order_id, $storeData)
    {
        $paypal_payment_mode = $storeData['paypal_payment_mode'] ?? 'sandbox';
        $url = 'https://www.paypal.com/cgi-bin/webscr';
        
        if ($paypal_payment_mode == 'sandbox') {
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
        
        $txn_id = '';
        $payment_status = '';
        
        // STEP 1: Read POST data (lines 772-798)
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = [];
        
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }
        
        // Build validation request (lines 787-798)
        $req = 'cmd=_notify-validate';
        $get_magic_quotes_exists = false;
        
        if (function_exists('get_magic_quotes_gpc')) {
            $get_magic_quotes_exists = true;
        }
        
        foreach ($myPost as $key => $value) {
            if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
                $value = urlencode(stripslashes($value));
            } else {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }
        
        // STEP 2: POST IPN data back to PayPal to validate (lines 800-819)
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);
        
        if (!($res = curl_exec($ch))) {
            curl_close($ch);
            return null;
        }
        curl_close($ch);
        
        // STEP 3: Inspect IPN validation result (lines 820-852)
        if (strcmp($res, "VERIFIED") == 0) {
            $txn_id = $_POST['txn_id'] ?? '';
            $payment_status = $_POST['payment_status'] ?? '';
        } else if (strcmp($res, "INVALID") == 0) {
            // Log invalid IPN (lines 843-851)
            $dateTime = date('Y-m-d H:i:s');
            $log = "DateTime:$dateTime Responce-Data:$raw_post_data The \n response from IPN was:" . $res . "\n url:$url\n order_id:$order_id\n ";
            $file = fopen(storage_path('logs/payment-ipn-log'), "a");
            fwrite($file, $log);
            fclose($file);
            return null;
        }
        
        // Log IPN response (lines 853-858)
        $dateTime = date('Y-m-d H:i:s');
        $log = "DateTime:$dateTime Responce-Data:$raw_post_data The \n response from IPN was:" . $res . "\n url:$url\n order_id:$order_id\n ";
        $file = fopen(storage_path('logs/payment-ipn-log'), "a");
        fwrite($file, $log);
        fclose($file);
        
        // Build order data (lines 860-883)
        $orderData = [];
        $orderData['id'] = $order_id;
        $orderData['payment_method'] = 'paypal';
        $orderData['payment_status'] = 2;
        
        if (!empty($raw_post_data)) {
            $orderData['paypal_responce'] = $raw_post_data;
        }
        
        if (!empty($order_id)) {
            $orderData['status'] = 2;
            
            if ($payment_status == 'Completed' || $payment_status == 'completed') {
                $orderData['payment_status'] = 2;
                $orderData['transition_remark'] = 'payment success';
            } else if ($payment_status == 'Pending' || $payment_status == 'pending') {
                $orderData['payment_status'] = 1;
                $orderData['transition_remark'] = 'payment Pending';
            } else {
                $orderData['payment_status'] = 3;
                $orderData['transition_remark'] = 'payment Failed';
            }
            
            $orderData['transition_id'] = $txn_id;
        }
        
        return $orderData;
    }
    
    /**
     * Handle PayPal cancel response (replicate PayPalCancelResponse lines 890-905)
     * 
     * @param int $order_id Order ID
     * @return array Order data with status
     */
    public function handleCancelResponse($order_id)
    {
        $orderData = [];
        
        if (!empty($order_id)) {
            $orderData['id'] = $order_id;
            $orderData['status'] = 7;
            $orderData['payment_status'] = 3;
        }
        
        return $orderData;
    }
}
