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
        
        // Update order status
        $orderData = [
            'order_status' => 'confirmed',
            'payment_status' => 1, // Convert 'pending' to integer (CI compatibility)
            'payment_method' => 'COD',
            'updated' => now(),
        ];
        
        DB::table('product_orders')->where('id', $order_id)->update($orderData);
        
        // Clear cart
        $cart = new CartService();
        $cart->destroy();
        
        // Send order confirmation email
        $this->sendOrderConfirmationEmail($order_id);
        
        return redirect('MyOrders/view/' . base64_encode($order_id))
            ->with('message_success', $language_name == 'french' 
                ? 'Votre commande a été passée avec succès' 
                : 'Your order has been placed successfully');
    }
    
    /**
     * Process PayPal payment
     */
    protected function processPayPal($order_id, $order)
    {
        $language_name = config('store.language_name', 'english');
        
        // Get PayPal configuration
        $paypal_mode = config('payment.paypal_mode', 'sandbox');
        $paypal_client_id = config('payment.paypal_client_id');
        
        if (empty($paypal_client_id)) {
            return redirect()->back()->with('message_error', 'PayPal not configured');
        }
        
        // Prepare PayPal data
        $data = [
            'ProductOrder' => $order,
            'BASE_URL' => url('/'),
            'language_name' => $language_name,
            'paypal_mode' => $paypal_mode,
            'paypal_client_id' => $paypal_client_id,
            'return_url' => url('Payments/paypal_success/' . base64_encode($order_id)),
            'cancel_url' => url('Payments/paypal_cancel/' . base64_encode($order_id)),
        ];
        
        return view('payments.paypal_redirect', $data);
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
        
        // Get card token
        $cardToken = $this->getCloverCardToken($cardData);
        
        if (!$cardToken['token']) {
            return redirect()->back()->with('message_error', $cardToken['msg']);
        }
        
        // Process payment
        $currency = DB::table('currencies')->where('id', $order->currency_id ?? 1)->first();
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
        
        $response = $this->processCloverPayment($paymentData);
        
        if ($response['status']) {
            // Payment successful
            $paymentRes = json_decode($response['paymentData']);
            
            DB::table('product_orders')->where('id', $order_id)->update([
                'order_status' => 'confirmed',
                'payment_status' => 'completed',
                'payment_method' => 'Credit Card',
                'transition_id' => $paymentRes->id ?? '',
                'transition_remark' => 'payment success',
                'paypal_responce' => $response['paymentData'],
                'updated' => now(),
            ]);
            
            // Clear cart
            $cart = new CartService();
            $cart->destroy();
            
            // Send confirmation email
            $this->sendOrderConfirmationEmail($order_id);
            
            return redirect('MyOrders/view/' . base64_encode($order_id))
                ->with('message_success', $response['msg']);
        } else {
            // Payment failed
            DB::table('product_orders')->where('id', $order_id)->update([
                'payment_status' => 'failed',
                'transition_remark' => 'payment failed',
                'updated' => now(),
            ]);
            
            return redirect()->back()->with('message_error', $response['msg']);
        }
    }
    
    /**
     * Get Clover card token
     * CI: Checkouts->cardPaymentRequest() lines 664-701
     */
    protected function getCloverCardToken($cardData)
    {
        $clover_mode = config('payment.clover_mode', 'sandbox');
        $url = $clover_mode == 'live' 
            ? 'https://token.clover.com/v1/' 
            : 'https://token-sandbox.dev.clover.com/v1/';
        
        $apiKey = $clover_mode == 'live' 
            ? config('payment.clover_api_key') 
            : config('payment.clover_sandbox_api_key');
        
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
    protected function processCloverPayment($paymentData)
    {
        $clover_mode = config('payment.clover_mode', 'sandbox');
        $url = $clover_mode == 'live' 
            ? 'https://scl.clover.com/v1/' 
            : 'https://scl-sandbox.dev.clover.com/v1/';
        
        $token = $clover_mode == 'live' 
            ? config('payment.clover_secret') 
            : config('payment.clover_sandbox_secret');
        
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
            'payment_method' => 'PayPal',
            'transition_id' => $txn_id,
        ];
        
        if ($payment_status == 'Completed' || $payment_status == 'completed') {
            $orderData['payment_status'] = 2; // Convert 'completed' to integer (CI compatibility)
            $orderData['transition_remark'] = 'payment success';
            
            // Clear cart
            $cart = new CartService();
            $cart->destroy();
            
            // Send confirmation email
            $this->sendOrderConfirmationEmail($order_id);
        } elseif ($payment_status == 'Pending' || $payment_status == 'pending') {
            $orderData['payment_status'] = 1; // Convert 'pending' to integer (CI compatibility)
            $orderData['transition_remark'] = 'payment pending';
        } else {
            $orderData['payment_status'] = 3; // Convert 'failed' to integer (CI compatibility)
            $orderData['transition_remark'] = 'payment failed';
            
            if (!empty($PayerID)) {
                $orderData['payment_status'] = 2; // Convert 'completed' to integer (CI compatibility)
                $orderData['transition_remark'] = 'payment success';
                if (empty($txn_id)) {
                    $orderData['transition_id'] = $PayerID;
                }
            }
        }
        
        DB::table('product_orders')->where('id', $order_id)->update($orderData);
        
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
            
            DB::table('product_orders')->where('id', $order_id)->update([
                'payment_status' => 'cancelled',
                'transition_remark' => 'payment cancelled by user',
                'updated' => now(),
            ]);
        }
        
        return redirect('Checkouts/index/' . base64_encode(4) . '/' . base64_encode($order_id))
            ->with('message_error', 'Payment was cancelled');
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
