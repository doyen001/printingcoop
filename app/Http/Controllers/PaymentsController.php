<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\CartService;

/**
 * PaymentsController
 * Payment gateway integration and processing
 * CI: Checkouts->SubmitOrder(), cardPaymentRequest(), paymentRequest() lines 526-743
 */
class PaymentsController extends Controller
{
    /**
     * Process payment submission
     * CI: Checkouts->SubmitOrder() lines 526-662
     */
    public function processPayment(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        if (empty($loginId)) {
            return redirect('/Logins');
        }
        
        $order_id = $request->input('order_id');
        $payment_type = $request->input('payment_type');
        
        if (empty($order_id) || empty($payment_type)) {
            return redirect()->back()->with('message_error', 'Invalid payment request');
        }
        
        // Get order data
        $order = DB::table('product_orders')->where('id', $order_id)->first();
        
        if (!$order) {
            return redirect('/')->with('message_error', 'Order not found');
        }
        
        // Update payment type
        DB::table('product_orders')->where('id', $order_id)->update([
            'payment_type' => $payment_type,
        ]);
        
        // Process based on payment type
        switch ($payment_type) {
            case 'cod':
                return $this->processCOD($order_id, $order);
                
            case 'paypal':
                return $this->processPayPal($order_id, $order);
                
            case 'pos':
            case 'credit_card':
                return $this->processClover($request, $order_id, $order);
                
            default:
                return redirect('/')->with('message_error', 'Invalid payment method');
        }
    }
    
    /**
     * Process Cash on Delivery payment
     */
    protected function processCOD($order_id, $order)
    {
        $language_name = config('store.language_name', 'english');
        
        // CI: UpdateOrderStatus handles stock update, cart clear, emails
        $orderData = [
            'status' => 2,
            'payment_status' => 2,
            'payment_method' => 'COD',
        ];
        
        if ($this->updateOrderStatus($orderData, $order_id)) {
            $msg = $language_name == 'french'
                ? 'Votre commande a été passée avec succès'
                : 'Your order has been placed successfully';
            return redirect('MyOrders/view/' . base64_encode($order_id))
                ->with('message_success', $msg);
        } else {
            return redirect()->back()
                ->with('message_error', $language_name == 'french'
                    ? 'Votre commande a échoué'
                    : 'Your order has been placed unsuccessfully');
        }
    }
    
    /**
     * Process PayPal payment
     */
    protected function processPayPal($order_id, $order)
    {
        $language_name = config('store.language_name', 'english');
        
        // CI-style PayPal redirect: read store data from DB like CI
        $mainStoreObj = DB::table('stores')->where('main_store', 1)->first();
        if (!$mainStoreObj) {
            $mainStoreObj = DB::table('stores')->first();
        }
        $mainStoreData = $mainStoreObj ? (array) $mainStoreObj : [];
        
        // Build currency list as in CI $CurrencyList (table `currency`)
        $currencyTable = \Schema::hasTable('currencies') ? 'currencies' : 'currency';
        $currencies = DB::table($currencyTable)->get();
        $CurrencyList = [];
        foreach ($currencies as $currency) {
            $CurrencyList[$currency->id] = (array) $currency;
        }
        
        $data = [
            'ProductOrder' => (array) $order,
            'BASE_URL' => url('/'),
            'language_name' => $language_name,
            'MainStoreData' => $mainStoreData,
            'CurrencyList' => $CurrencyList,
        ];
        
        return view('elements.PaypalRedirect', $data);
    }
    
    /**
     * Process Clover (POS/Credit Card) payment
     * CI: Checkouts->SubmitOrder() lines 584-651
     */
    protected function processClover(Request $request, $order_id, $order)
    {
        $language_name = config('store.language_name', 'english');
        
        // Validate card details
        $cardNum = $request->input('card-num');
        $expMonth = $request->input('ExpMonth');
        $expYear = $request->input('ExpYear');
        $cvv = $request->input('cvv');
        
        if (empty($cardNum) || empty($expMonth) || empty($expYear) || empty($cvv)) {
            return redirect()->back()->with('message_error', 
                $language_name == 'french' ? 'Informations de carte manquantes' : 'Missing card information');
        }
        
        // Prepare card data
        $cardData = [
            'card' => [
                'number' => str_replace(' ', '', $cardNum),
                'exp_month' => $expMonth,
                'exp_year' => $expYear,
                'cvv' => $cvv,
            ],
        ];
        
        // Get main store data from DB like CI
        $mainStoreObj = DB::table('stores')->where('main_store', 1)->first();
        if (!$mainStoreObj) {
            $mainStoreObj = DB::table('stores')->first();
        }
        $mainStoreData = $mainStoreObj ? (array) $mainStoreObj : [];
        
        // Get card token
        $cardToken = $this->getCloverCardToken($cardData, $mainStoreData);
        
        if (!$cardToken['token']) {
            return redirect()->back()->with('message_error', $cardToken['msg']);
        }
        
        // Process payment
        $currencyTable = \Schema::hasTable('currencies') ? 'currencies' : 'currency';
        $currency = DB::table($currencyTable)->where('id', $order->currency_id ?? 1)->first();
        $currency_code = $currency->code ?? 'CAD';
        
        $paymentData = [
            'amount' => round($order->total_amount * 100), // Convert to cents
            'currency' => strtolower($currency_code),
            'capture' => 'true',
            'description' => 'Products Order Payment',
            'external_reference_id' => $order_id,
            'receipt_email' => $order->email,
            'source' => $cardToken['token'],
            'ecomind' => 'ecom',
        ];
        
        $response = $this->processCloverPayment($paymentData, $mainStoreData);
        
        if ($response['status']) {
            // Payment successful - use updateOrderStatus like CI
            $paymentRes = json_decode($response['paymentData']);
            
            $orderData = [
                'status' => 2,
                'payment_status' => 2,
                'payment_method' => 'POS',
                'transition_id' => $paymentRes->id ?? '',
                'transition_remark' => 'payment success',
                'paypal_responce' => $response['paymentData'],
            ];
            $this->updateOrderStatus($orderData, $order_id);
            
            return redirect('MyOrders/view/' . base64_encode($order_id))
                ->with('message_success', $response['msg']);
        } else {
            // Payment failed - CI saves payment_status=3 and redirects to MyOrders
            DB::table('product_orders')->where('id', $order_id)->update([
                'payment_status' => 3,
                'transition_remark' => 'payment Failed',
            ]);
            
            return redirect('MyOrders/view/' . base64_encode($order_id))
                ->with('message_error', $response['msg']);
        }
    }
    
    /**
     * Get Clover card token
     * CI: Checkouts->cardPaymentRequest() lines 664-701
     */
    protected function getCloverCardToken($cardData, $mainStoreData = [])
    {
        // CI reads clover_mode (1=live) from stores table
        $clover_mode = $mainStoreData['clover_mode'] ?? 0;
        $url = $clover_mode == 1 
            ? 'https://token.clover.com/v1/' 
            : 'https://token-sandbox.dev.clover.com/v1/';
        
        $apiKey = $clover_mode == 1 
            ? ($mainStoreData['clover_api_key'] ?? '') 
            : ($mainStoreData['clover_sandbox_api_key'] ?? '');
        
        $res = ['token' => false, 'msg' => 'Invalid Card Credentials'];
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url . 'tokens',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($cardData),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'apikey: ' . $apiKey,
            ],
        ]);
        
        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            $res['msg'] = $err;
        } else {
            if (isset($response->id)) {
                $res['token'] = $response->id;
                $res['msg'] = '';
            } elseif (isset($response->error->message)) {
                $res['msg'] = $response->error->message;
            } else {
                $res['msg'] = $response->message ?? 'Card tokenization failed';
            }
        }
        
        return $res;
    }
    
    /**
     * Process Clover payment
     * CI: Checkouts->paymentRequest() lines 703-743
     */
    protected function processCloverPayment($paymentData, $mainStoreData = [])
    {
        // CI reads clover_mode (1=live) from stores table
        $clover_mode = $mainStoreData['clover_mode'] ?? 0;
        $url = $clover_mode == 1 
            ? 'https://scl.clover.com/v1/' 
            : 'https://scl-sandbox.dev.clover.com/v1/';
        
        $token = $clover_mode == 1 
            ? ($mainStoreData['clover_secret'] ?? '') 
            : ($mainStoreData['clover_sandbox_secret'] ?? '');
        
        $res = ['status' => false, 'paymentData' => false, 'msg' => "Your Order's Payment Failed"];
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url . 'charges',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($paymentData),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ],
        ]);
        
        $response = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);
        
        if ($err) {
            $res['msg'] = $err;
        } else {
            if (isset($response->id) && $response->status == 'succeeded') {
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
     * PayPal success callback
     * CI: Checkouts->PayPalSuccessResponse() lines 745-794
     */
    public function paypalSuccess(Request $request, $order_id = null)
    {
        if (empty($order_id)) {
            return redirect('/')->with('message_error', 'Invalid order');
        }
        
        $order_id = base64_decode($order_id);
        $payment_status = $request->input('payment_status');
        $txn_id = $request->input('txn_id');
        $PayerID = $request->input('PayerID');
        
        $orderData = [
            'status' => 2,
            'payment_method' => 'paypal',
            'transition_id' => $txn_id,
        ];
        
        // Save full PayPal response like CI
        if (!empty($request->all())) {
            $orderData['paypal_responce'] = json_encode($request->all());
        }
        
        if ($payment_status == 'Completed' || $payment_status == 'completed') {
            $orderData['payment_status'] = 2;
            $orderData['transition_remark'] = 'payment success';
            
            $this->updateOrderStatus($orderData, $order_id);
            
        } elseif ($payment_status == 'Pending' || $payment_status == 'pending') {
            $orderData['payment_status'] = 1;
            $orderData['transition_remark'] = 'payment Pending';
            DB::table('product_orders')->where('id', $order_id)->update($orderData);
        } else {
            $orderData['payment_status'] = 3;
            $orderData['transition_remark'] = 'payment Failed';
            
            if (!empty($PayerID)) {
                $orderData['payment_status'] = 2;
                $orderData['transition_remark'] = 'payment success';
                if (empty($txn_id)) {
                    $orderData['transition_id'] = $PayerID;
                }
            }
            
            DB::table('product_orders')->where('id', $order_id)->update($orderData);
        }
        
        return redirect('MyOrders/view/' . base64_encode($order_id))
            ->with('message_success', 'Your order payment has been successfully processed');
    }
    
    /**
     * PayPal cancel callback
     */
    public function paypalCancel($order_id = null)
    {
        if (!empty($order_id)) {
            $order_id = base64_decode($order_id);
            
            // CI uses status=7 for cancelled, payment_status=3 for failed
            $orderData = [
                'status' => 7,
                'payment_status' => 3,
            ];
            $this->updateOrderStatus($orderData, $order_id);
            
            return redirect('MyOrders/view/' . base64_encode($order_id))
                ->with('message_error', 'Your order payment has been failed');
        }
        
        return redirect('/');
    }
    
    /**
     * PayPal IPN callback
     * CI: Checkouts->PayPalIPNResponse() lines 799-936
     */
    public function paypalIPN(Request $request, $order_id = null)
    {
        if (empty($order_id)) {
            exit();
        }
        
        $PostOrderData = DB::table('product_orders')->where('id', $order_id)->first();
        if (!$PostOrderData) {
            exit();
        }
        $PostOrderData = (array) $PostOrderData;
        
        $store_id = $PostOrderData['store_id'];
        $postStoreData = DB::table('stores')->where('id', $store_id)->first();
        $postStoreData = $postStoreData ? (array) $postStoreData : [];
        $paypal_payment_mode = $postStoreData['paypal_payment_mode'] ?? 'live';
        
        $url = 'https://www.paypal.com/cgi-bin/webscr';
        if ($paypal_payment_mode == 'sandbox') {
            $url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }
        
        $txn_id = $payment_status = '';
        $sendMail = false;
        
        // Read raw POST data
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = [];
        foreach ($raw_post_array as $keyval) {
            $keyval = explode('=', $keyval);
            if (count($keyval) == 2) {
                $myPost[$keyval[0]] = urldecode($keyval[1]);
            }
        }
        
        // Prepend cmd=_notify-validate
        $req = 'cmd=_notify-validate';
        foreach ($myPost as $key => $value) {
            $value = urlencode($value);
            $req .= "&$key=$value";
        }
        
        // POST IPN data back to PayPal to validate
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
            exit;
        }
        curl_close($ch);
        
        // Inspect IPN validation result
        if (strcmp($res, 'VERIFIED') == 0) {
            $txn_id = $myPost['txn_id'] ?? '';
            $payment_status = $myPost['payment_status'] ?? '';
            if ($PostOrderData['transition_id'] != $txn_id) {
                $sendMail = true;
            }
        }
        
        // Log IPN
        Log::info('PayPal IPN Response', [
            'order_id' => $order_id,
            'raw_post_data' => $raw_post_data,
            'ipn_result' => $res,
            'txn_id' => $txn_id,
            'payment_status' => $payment_status,
        ]);
        
        $orderData = [
            'payment_method' => 'paypal',
            'payment_status' => 2,
            'transition_id' => $txn_id,
        ];
        
        if (!empty($raw_post_data)) {
            $orderData['paypal_responce'] = $raw_post_data;
        }
        
        if (!empty($order_id)) {
            $orderData['status'] = 2;
            if ($payment_status == 'Completed' || $payment_status == 'completed') {
                $orderData['payment_status'] = 2;
                $orderData['transition_remark'] = 'payment success';
            } elseif ($payment_status == 'Pending' || $payment_status == 'pending') {
                $orderData['payment_status'] = 1;
                $orderData['transition_remark'] = 'payment Pending';
            } else {
                $orderData['payment_status'] = 3;
                $orderData['transition_remark'] = 'payment Failed';
            }
            
            $this->updateOrderStatus($orderData, $order_id, $sendMail);
        }
        
        exit();
    }
    
    /**
     * Update order status and handle post-order actions (emails, cart clear, stock update)
     * CI: Checkouts->UpdateOrderStatus() lines 957-1151
     */
    protected function updateOrderStatus(array $orderData, $order_id, $sendMail = true)
    {
        try {
            DB::table('product_orders')->where('id', $order_id)->update($orderData);
            
            $order = DB::table('product_orders')->where('id', $order_id)->first();
            if (!$order) {
                return false;
            }
            
            // Status 2 = New/Confirmed order
            if ($order->status == 2 && ($orderData['payment_status'] ?? 0) == 2) {
                // Update product stock (CI: subtract quantity from total_stock)
                $orderItems = DB::table('product_order_items')->where('order_id', $order_id)->get();
                foreach ($orderItems as $item) {
                    $product = DB::table('products')->where('id', $item->product_id)->first();
                    if ($product) {
                        $total_stock = max(0, $product->total_stock - $item->quantity);
                        DB::table('products')->where('id', $product->id)->update(['total_stock' => $total_stock]);
                    }
                }
                
                // Clear cart
                $cart = new CartService();
                $cart->destroy();
                
                // Send confirmation email
                if ($sendMail) {
                    $this->sendOrderConfirmationEmail($order_id);
                }
            }
            
            // Status 7 = Payment failed - send failure email
            if ($order->status == 7 && $sendMail) {
                $this->sendOrderConfirmationEmail($order_id);
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send order confirmation email
     */
    protected function sendOrderConfirmationEmail($order_id)
    {
        try {
            $order = DB::table('product_orders')->where('id', $order_id)->first();
            $orderItems = DB::table('product_order_items')->where('order_id', $order_id)->get();
            
            if (!$order) {
                return false;
            }
            
            $language_name = config('store.language_name', 'english');
            
            // Prepare email data
            $emailData = [
                'order' => $order,
                'orderItems' => $orderItems,
                'language_name' => $language_name,
            ];
            
            // Send email (implement mail view later)
            // Mail::to($order->email)->send(new OrderConfirmation($emailData));
            
            Log::info('Order confirmation email sent for order: ' . $order_id);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Stripe payment processing (for future implementation)
     */
    public function processStripe(Request $request, $order_id)
    {
        // Stripe integration can be added here
        // Similar structure to Clover but using Stripe API
        
        return response()->json([
            'status' => 0,
            'msg' => 'Stripe integration coming soon'
        ]);
    }
}
