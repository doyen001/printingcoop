<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
     * Get currency list (matching CI structure)
     */
    protected function getCurrencyList()
    {
        $currencies = DB::table('currency')->orderBy('order', 'asc')->get();
        $currencyList = [];
        
        foreach ($currencies as $currency) {
            $currencyList[$currency->id] = (array) $currency;
        }
        
        return $currencyList;
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
        
        // Get orders matching CI logic: user_delete=1 and specific statuses
        // Temporarily remove conditions to debug
        $orders = DB::table('product_orders')
            ->where('user_id', $loginId)
            // ->where('user_delete', 1)
            // ->whereIn('status', [2, 3, 4, 5, 6, 7, 9])
            ->orderBy('updated', 'desc')
            ->get();
        
        $orderData = [];
        
        foreach ($orders as $order) {
            // Convert stdClass to array like CI does
            $orderArray = (array) $order;
            
            // Get order items for this order with JOIN matching CI
            $orderItems = DB::table('product_order_items')
                ->select('product_order_items.*', 'provider_products.provider_product_id')
                ->leftJoin('provider_products', 'provider_products.product_id', '=', 'product_order_items.product_id')
                ->where('product_order_items.order_id', $order->id)
                ->get();
            
            // Convert order items to arrays
            $orderItemsArray = [];
            foreach ($orderItems as $item) {
                $orderItemsArray[] = (array) $item;
            }
            
            // Add OrderItem to order data (matching CI structure)
            $orderArray['OrderItem'] = $orderItemsArray;
            
            $orderData[] = $orderArray;
        }
        
        // Get currency list (matching CI structure)
        $CurrencyList = $this->getCurrencyList();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Historique des commandes' : 'Order History',
            'orderData' => $orderData,
            'CurrencyList' => $CurrencyList,
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
            
            // Update order status to cancelled (matches CI logic)
            DB::table('product_orders')->where('id', $id)->update([
                'status' => 6, // Cancelled status (matches CI)
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
        
        $filepath = public_path('pdf/' . $filename);
        
        // Check file exists and generate if not (matches CI logic lines 280-292)
        if (!file_exists($filepath)) {
            if ($type == 'invoice') {
                $this->generateOrderInvoicePdf($id, $order->store_id);
            } else {
                $this->generateOrderPdf($id, $order->store_id);
            }
            
            // Try again after generation (matches CI lines 289-292)
            if (file_exists($filepath)) {
                return response()->download($filepath, $filename);
            }
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
        
        // Match CI implementation exactly (CI line 225)
        $file = urldecode($filePath);
        
        // Debug logging
        Log::info('Download request - Original path: ' . $filePath);
        Log::info('Download request - Decoded path: ' . $file);
        
        // Handle relative paths (convert to absolute)
        if (strpos($file, '/') !== 0) {
            // Relative path - make it absolute from public directory
            $file = public_path($file);
        }
        
        Log::info('Download request - Full path: ' . $file);
        Log::info('Download request - File exists: ' . (file_exists($file) ? 'YES' : 'NO'));
        
        // Check file exists (CI line 227)
        if (file_exists($file)) {
            // Get file content (CI line 229)
            $data = file_get_contents($file);
            
            // Force download (CI line 232 equivalent)
            return response($data)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . urldecode($name) . '"')
                ->header('Content-Length', strlen($data));
        }
        
        return redirect()->back()->with('message_error', 'File not found: ' . $file);
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
        $from_email = $store->from_email ?? '';
        $from_name = $store->name ?? '';
        $admin_email1 = $store->admin_email1 ?? '';
        $admin_email2 = $store->admin_email2 ?? '';
        $admin_email3 = $store->admin_email3 ?? '';
        
        // Prepare email content (matches CI lines 124-164)
        if ($langue_id == 2) {
            // French content
            $subject = 'Ordre ' . $order->order_id . ' a été annulé.';
            
            // Admin email body
            $body_admin = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                    Salut administrateur,<br>
                    Désolé de vous informer que nous avons annulé votre commande ' . $order->order_id . ' par ' . $order->name . '<br>La raison indiquée ci-dessous <br>' . $reason . '
                </span>
            </div><br>';
            
            // User email body
            $body_user = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                    Salut ' . $order->name . ',<br>
                    Désolé de vous informer que vous avez annulé votre commande ' . $order->order_id . ' par ' . $order->name . '<br>La raison indiquée ci-dessous <br>' . $reason . '
                </span>
            </div><br>';
        } else {
            // English content
            $subject = 'Order ' . $order->order_id . ' has been cancelled.';
            
            // Admin email body
            $body_admin = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                    Hi admin,<br>
                    Sorry to inform you that we have cancelled your order ' . $order->order_id . ' by ' . $order->name . ' <br>The reason Indicated below <br>' . $reason . '
                </span>
            </div><br>';
            
            // User email body
            $body_user = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                    Hi ' . $order->name . ',<br>
                    Sorry to inform you that you have cancelled your order ' . $order->order_id . ' The reason <br>Indicated below <br>' . $reason . '
                </span>
            </div><br>';
        }
        
        // Prepare PDF attachments (matches CI lines 166-194)
        $files = [];
        if ($langue_id == 2) {
            $invoice_file = $order->order_id . '-fr-invoice.pdf';
            $order_file = $order->order_id . '-fr-order.pdf';
        } else {
            $invoice_file = $order->order_id . '-invoice.pdf';
            $order_file = $order->order_id . '-order.pdf';
        }
        
        $invoice_file = strtolower($invoice_file);
        $order_file = strtolower($order_file);
        
        $invoice_path = public_path('pdf/' . $invoice_file);
        $order_path = public_path('pdf/' . $order_file);
        
        // Check if PDFs exist, if not generate them (matches CI logic)
        if (!file_exists($invoice_path)) {
            $this->generateOrderInvoicePdf($orderId, $order->store_id);
        }
        if (!file_exists($order_path)) {
            $this->generateOrderPdf($orderId, $order->store_id);
        }
        
        // Add to attachments if files exist
        if (file_exists($invoice_path)) {
            $files[$invoice_file] = $invoice_path;
        }
        if (file_exists($order_path)) {
            $files[$order_file] = $order_path;
        }
        
        // Send emails (matches CI lines 195-205)
        try {
            // Send to user
            if (!empty($order->email) && !empty($from_email)) {
                $this->sendEmailWithAttachments($order->email, $subject, $body_user, $from_email, $from_name, $files);
            }
            
            // Send to admins
            if (!empty($admin_email1)) {
                $this->sendEmailWithAttachments($admin_email1, $subject, $body_admin, $from_email, $from_name, $files);
            }
            if (!empty($admin_email2)) {
                $this->sendEmailWithAttachments($admin_email2, $subject, $body_admin, $from_email, $from_name, $files);
            }
            if (!empty($admin_email3)) {
                $this->sendEmailWithAttachments($admin_email3, $subject, $body_admin, $from_email, $from_name, $files);
            }
            
            Log::info('Cancellation emails sent for order: ' . $order->order_id);
            
        } catch (\Exception $e) {
            Log::error('Failed to send cancellation emails: ' . $e->getMessage());
            return false;
        }
        
        return true;
    }
    
    /**
     * Helper: Send email with attachments (matches CI sendEmail function)
     */
    protected function sendEmailWithAttachments($to, $subject, $body, $from, $fromName, $attachments = [])
    {
        // For now, use Laravel's Mail facade - TODO: Create proper Mailable classes
        try {
            $data = [
                'subject' => $subject,
                'body' => $body,
                'fromName' => $fromName,
            ];
            
            Mail::send([], [], function ($message) use ($to, $subject, $body, $from, $fromName, $attachments) {
                $message->to($to)
                    ->subject($subject)
                    ->from($from, $fromName)
                    ->setBody($body, 'text/html');
                
                // Add attachments
                foreach ($attachments as $filename => $path) {
                    if (file_exists($path)) {
                        $message->attach($path, [
                            'as' => $filename,
                            'mime' => mime_content_type($path),
                        ]);
                    }
                }
            });
            
        } catch (\Exception $e) {
            Log::error('Email send failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Helper: Generate order invoice PDF (matches CI getOrderInvoicePdf)
     */
    protected function generateOrderInvoicePdf($orderId, $storeId)
    {
        try {
            $order = DB::table('product_orders')->where('id', $orderId)->first();
            $store = DB::table('stores')->where('id', $storeId)->first();
            $orderItems = DB::table('product_order_items')->where('order_id', $orderId)->get();
            
            if (!$order || !$store) {
                return false;
            }
            
            $langue_id = $store->langue_id ?? 1;
            $filename = $langue_id == 2 
                ? strtolower($order->order_id . '-fr-invoice.pdf')
                : strtolower($order->order_id . '-invoice.pdf');
            
            $filepath = public_path('pdf/' . $filename);
            
            // Create simple HTML for PDF (matches CI invoice structure)
            $html = $this->generateInvoiceHtml($order, $store, $orderItems, $langue_id);
            
            // Generate PDF using DomPDF
            $pdf = app('dompdf.wrapper');
            $pdf->loadHTML($html);
            $pdf->save($filepath);
            
            Log::info('Generated invoice PDF: ' . $filename);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate invoice PDF: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Helper: Generate order PDF (matches CI getOrderPdf)
     */
    protected function generateOrderPdf($orderId, $storeId)
    {
        try {
            $order = DB::table('product_orders')->where('id', $orderId)->first();
            $store = DB::table('stores')->where('id', $storeId)->first();
            $orderItems = DB::table('product_order_items')->where('order_id', $orderId)->get();
            
            if (!$order || !$store) {
                return false;
            }
            
            $langue_id = $store->langue_id ?? 1;
            $filename = $langue_id == 2 
                ? strtolower($order->order_id . '-fr-order.pdf')
                : strtolower($order->order_id . '-order.pdf');
            
            $filepath = public_path('pdf/' . $filename);
            
            // Create simple HTML for PDF (matches CI order structure)
            $html = $this->generateOrderHtml($order, $store, $orderItems, $langue_id);
            
            // Generate PDF using DomPDF
            $pdf = app('dompdf.wrapper');
            $pdf->loadHTML($html);
            $pdf->save($filepath);
            
            Log::info('Generated order PDF: ' . $filename);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate order PDF: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate invoice HTML content
     */
    protected function generateInvoiceHtml($order, $store, $orderItems, $langue_id)
    {
        $title = $langue_id == 2 ? 'Facture' : 'Invoice';
        $orderLabel = $langue_id == 2 ? 'Commande' : 'Order';
        $dateLabel = $langue_id == 2 ? 'Date' : 'Date';
        $totalLabel = $langue_id == 2 ? 'Total' : 'Total';
        
        $html = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .info { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .total { font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>{$title}</h1>
                <h2>{$store->name}</h2>
            </div>
            
            <div class='info'>
                <p><strong>{$orderLabel} #:</strong> {$order->order_id}</p>
                <p><strong>{$dateLabel}:</strong> {$order->created}</p>
                <p><strong>Customer:</strong> {$order->name}</p>
                <p><strong>Email:</strong> {$order->email}</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($orderItems as $item) {
            $itemTotal = $item->quantity * $item->price;
            $html .= "
                    <tr>
                        <td>{$item->name}</td>
                        <td>{$item->quantity}</td>
                        <td>\${$item->price}</td>
                        <td>\${$itemTotal}</td>
                    </tr>";
        }
        
        $html .= "
                </tbody>
                <tfoot>
                    <tr class='total'>
                        <td colspan='3'>{$totalLabel}</td>
                        <td>\${$order->total_amount}</td>
                    </tr>
                </tfoot>
            </table>
        </body>
        </html>";
        
        return $html;
    }
    
    /**
     * Generate order HTML content
     */
    protected function generateOrderHtml($order, $store, $orderItems, $langue_id)
    {
        $title = $langue_id == 2 ? 'Détails de la commande' : 'Order Details';
        $orderLabel = $langue_id == 2 ? 'Commande' : 'Order';
        $dateLabel = $langue_id == 2 ? 'Date' : 'Date';
        $totalLabel = $langue_id == 2 ? 'Total' : 'Total';
        
        $html = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .info { margin-bottom: 20px; }
                .address { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .total { font-weight: bold; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>{$title}</h1>
                <h2>{$store->name}</h2>
            </div>
            
            <div class='info'>
                <p><strong>{$orderLabel} #:</strong> {$order->order_id}</p>
                <p><strong>{$dateLabel}:</strong> {$order->created}</p>
                <p><strong>Status:</strong> {$order->status}</p>
            </div>
            
            <div class='address'>
                <h3>Billing Address</h3>
                <p>{$order->billing_name}</p>
                <p>{$order->billing_address}</p>
                <p>{$order->billing_city}, {$order->billing_state}</p>
                <p>{$order->billing_country}</p>
                <p>{$order->billing_pin_code}</p>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>";
        
        foreach ($orderItems as $item) {
            $itemTotal = $item->quantity * $item->price;
            $html .= "
                    <tr>
                        <td>{$item->name}</td>
                        <td>{$item->quantity}</td>
                        <td>\${$item->price}</td>
                        <td>\${$itemTotal}</td>
                    </tr>";
        }
        
        $html .= "
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan='2'>Subtotal</td>
                        <td>\${$order->sub_total_amount}</td>
                    </tr>
                    <tr>
                        <td colspan='2'>Tax</td>
                        <td>\${$order->total_sales_tax}</td>
                    </tr>
                    <tr class='total'>
                        <td colspan='3'>{$totalLabel}</td>
                        <td>\${$order->total_amount}</td>
                    </tr>
                </tfoot>
            </table>
        </body>
        </html>";
        
        return $html;
    }
}
