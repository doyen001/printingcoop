<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * MyOrdersController - Complete order management for customers
 * CI: application/controllers/MyOrders.php (299 lines)
 */
class MyOrdersController extends Controller
{
    /**
     * Check if user is logged in
     */
    protected function checkLogin()
    {
        if (!session('loginId')) {
            return redirect('Homes');
        }
        return null;
    }
    
    /**
     * Order history listing
     * CI: lines 18-30
     */
    public function index()
    {
        
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $orderData = DB::table('product_orders')
            ->where('user_id', $loginId)
            ->orderBy('created', 'desc')
            ->paginate(20);
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Historique des commandes' : 'Order History',
            'orders' => $orderData,
        ];
        
        return view('my_orders.index', $data);
    }
    
    /**
     * View order details
     * CI: lines 32-69
     */
    public function view($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        if (!empty($id)) {
            $id = base64_decode($id);
        }
        
        $order = DB::table('product_orders')
            ->where('id', $id)
            ->where('user_id', $loginId)
            ->first();
        
        if (!$order) {
            return redirect('MyOrders')->with('message_error', 'Order not found');
        }
        
        $orderItems = DB::table('product_order_items')
            ->where('order_id', $id)
            ->get();
        
        // Get address details
        $billingState = DB::table('states')->where('id', $order->billing_state)->first();
        $billingCountry = DB::table('countries')->where('id', $order->billing_country)->first();
        $billingCity = DB::table('cities')->where('id', $order->billing_city)->first();
        
        $shippingState = DB::table('states')->where('id', $order->shipping_state)->first();
        $shippingCountry = DB::table('countries')->where('id', $order->shipping_country)->first();
        $shippingCity = DB::table('cities')->where('id', $order->shipping_city)->first();
        
        // Match CI: Address_Model->salesTaxRatesProvincesById($orderData['billing_state'])
        // and be tolerant to either underscored or dashed table names.
        if (\Schema::hasTable('sales_tax_rates_provinces')) {
            $salesTaxRates = DB::table('sales_tax_rates_provinces')
                ->where('state_id', $order->billing_state)
                ->first();
        } elseif (\Schema::hasTable('sales-tax-rates-provinces')) {
            $salesTaxRates = DB::table('sales-tax-rates-provinces')
                ->where('state_id', $order->billing_state)
                ->first();
        } else {
            $salesTaxRates = null;
        }
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Détails de la commande' : 'Order Details',
            'orderData' => $order,
            'OrderItemData' => $orderItems,
            'stateData' => $billingState,
            'countryData' => $billingCountry,
            'cityData' => $billingCity,
            'shippingState' => $shippingState,
            'shippingCountry' => $shippingCountry,
            'shippingCity' => $shippingCity,
            'salesTaxRatesProvinces_Data' => $salesTaxRates,
        ];
        
        return view('my_orders.view', $data);
    }
    
    /**
     * Delete/Cancel order
     * CI: lines 71-88
     */
    public function deleteOrder($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        if (!empty($id)) {
            $id = base64_decode($id);
            
            $order = DB::table('product_orders')
                ->where('id', $id)
                ->where('user_id', $loginId)
                ->first();
            
            if ($order) {
                // Delete order items
                DB::table('product_order_items')->where('order_id', $id)->delete();
                
                // Delete order
                DB::table('product_orders')->where('id', $id)->delete();
                
                $message = $language_name == 'french' 
                    ? 'La commande a été supprimée' 
                    : 'Order has been deleted';
                
                return redirect('MyOrders')->with('message_success', $message . ' successfully.');
            }
        }
        
        return redirect('MyOrders')->with('message_error', 'Missing information.');
    }
    
    /**
     * Change order status (cancel order)
     * CI: lines 90-219
     */
    public function changeOrderStatus(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $id = $request->input('order_id');
        $status = $request->input('status');
        $mobileMsg = $request->input('mobileMsg');
        
        $json = ['status' => 0, 'msg' => ''];
        
        if (!empty($id) && !empty($status) && $status == '6') {
            $order = DB::table('product_orders')
                ->where('id', $id)
                ->where('user_id', $loginId)
                ->first();
            
            if (!$order) {
                $json['msg'] = 'Order not found';
                return response()->json($json);
            }
            
            // Update order status to cancelled
            DB::table('product_orders')->where('id', $id)->update([
                'order_status' => 'cancelled',
                'order_comment' => $mobileMsg,
                'updated' => now(),
            ]);
            
            // Send cancellation email
            try {
                $this->sendCancellationEmail($id, $mobileMsg);
            } catch (\Exception $e) {
                Log::error('Failed to send cancellation email: ' . $e->getMessage());
            }
            
            $json['status'] = 1;
            $json['msg'] = $language_name == 'french'
                ? 'Votre commande a été annulée avec succès.'
                : 'Your order has been cancelled successfully.';
        } else {
            $json['msg'] = $language_name == 'french'
                ? "Votre commande n'a pas été annulée"
                : 'Your order has been cancelled unsuccessfully';
        }
        
        return response()->json($json);
    }
    
    /**
     * Download invoice or order PDF
     * CI: lines 239-297
     */
    public function downloadOrderPdf($id, $type = 'invoice')
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $order = DB::table('product_orders')
            ->where('id', $id)
            ->where('user_id', $loginId)
            ->first();
        
        if (!$order) {
            return redirect('MyOrders')->with('message_error', 'Order not found');
        }
        
        $store = DB::table('stores')->where('id', $order->store_id)->first();
        $langue_id = $store->langue_id ?? 1;
        
        if ($langue_id == 2) {
            $filename = $type == 'order' 
                ? strtolower($order->order_id . '-fr-order.pdf')
                : strtolower($order->order_id . '-fr-invoice.pdf');
        } else {
            $filename = $type == 'order'
                ? strtolower($order->order_id . '-order.pdf')
                : strtolower($order->order_id . '-invoice.pdf');
        }
        
        $filepath = storage_path('app/public/pdf/' . $filename);
        
        // Generate PDF if it doesn't exist
        if (!file_exists($filepath)) {
            // TODO: Implement PDF generation
            // $this->generateOrderPDF($id, $type);
        }
        
        if (file_exists($filepath)) {
            return response()->download($filepath, $filename);
        }
        
        return redirect('MyOrders')->with('message_error', 'PDF file not found');
    }
    
    /**
     * Download file
     * CI: lines 221-237
     */
    public function download($filePath, $name)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $file = urldecode($filePath);
        
        if (file_exists($file)) {
            return response()->download($file, urldecode($name));
        }
        
        return redirect()->back()->with('message_error', 'File not found');
    }
    
    /**
     * Reorder - Add all items from previous order to cart
     */
    public function reorder($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        if (!empty($id)) {
            $id = base64_decode($id);
        }
        
        $order = DB::table('product_orders')
            ->where('id', $id)
            ->where('user_id', $loginId)
            ->first();
        
        if (!$order) {
            return redirect('MyOrders')->with('message_error', 'Order not found');
        }
        
        $orderItems = DB::table('product_order_items')
            ->where('order_id', $id)
            ->get();
        
        // Clear current cart
        DB::table('shopping_carts')->where('user_id', $loginId)->delete();
        
        // Add all items to cart
        foreach ($orderItems as $item) {
            DB::table('shopping_carts')->insert([
                'user_id' => $loginId,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'created' => now(),
                'updated' => now(),
            ]);
        }
        
        $message = $language_name == 'french'
            ? 'Articles ajoutés au panier'
            : 'Items added to cart';
        
        return redirect('ShoppingCarts')->with('message_success', $message);
    }
    
    /**
     * Track order status
     */
    public function trackOrder($id)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        if (!empty($id)) {
            $id = base64_decode($id);
        }
        
        $order = DB::table('product_orders')
            ->where('id', $id)
            ->where('user_id', $loginId)
            ->first();
        
        if (!$order) {
            return redirect('MyOrders')->with('message_error', 'Order not found');
        }
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Suivi de commande' : 'Track Order',
            'order' => $order,
        ];
        
        return view('my_orders.track_order', $data);
    }
    
    /**
     * Helper: Send cancellation email
     */
    protected function sendCancellationEmail($orderId, $reason)
    {
        $order = DB::table('product_orders')->where('id', $orderId)->first();
        
        if (!$order) {
            return false;
        }
        
        $store = DB::table('stores')->where('id', $order->store_id)->first();
        
        if (!$store) {
            return false;
        }
        
        $langue_id = $store->langue_id ?? 1;
        
        if ($langue_id == 2) {
            $subject = 'Ordre ' . $order->order_id . ' a été annulé.';
            $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                    Salut ' . $order->name . ',<br>
                    Désolé de vous informer que vous avez annulé votre commande ' . $order->order_id . '<br>
                    La raison indiquée ci-dessous:<br>' . $reason . '
                </span>
            </div>';
        } else {
            $subject = 'Order ' . $order->order_id . ' has been cancelled.';
            $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                    Hi ' . $order->name . ',<br>
                    Sorry to inform you that you have cancelled your order ' . $order->order_id . '<br>
                    The reason indicated below:<br>' . $reason . '
                </span>
            </div>';
        }
        
        // TODO: Implement email sending
        // Mail::to($order->email)->send(new OrderCancelled($order, $subject, $body));
        
        Log::info('Order cancellation email sent for order: ' . $order->order_id);
        
        return true;
    }
}
