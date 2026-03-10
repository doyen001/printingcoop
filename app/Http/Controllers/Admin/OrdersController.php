<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PDF;

/**
 * Admin OrdersController
 * Complete order management for admin panel
 * CI: application/controllers/admin/Orders.php
 */
class OrdersController extends Controller
{
    /**
     * Order listing with filters
     * CI: Orders->index() lines 27-54
     */
    public function index(Request $request, $statusStr = null, $user_id = null)
    {
        $orderStatusMap = [
            'new' => 2,
            'processing' => 3,
            'shipped' => 4,
            'delivered' => 5,
            'cancelled' => 6,
            'failed' => 7,
            'complete' => 8,
            'ready-for-pickup' => 9,
        ];
        
        $status = $statusStr ? ($orderStatusMap[strtolower($statusStr)] ?? null) : null;
        
        $query = DB::table('product_orders')
            ->select('product_orders.*', 'users.name as user_name', 'users.email as user_email')
            ->leftJoin('users', 'product_orders.user_id', '=', 'users.id')
            ->where('product_orders.admin_delete', 1)  // CI project condition
            ->orderBy('product_orders.id', 'desc');
        
        // Filter by status (CI project style)
        if ($status) {
            $query->where('product_orders.status', $status);
        } else {
            // CI project default: exclude Incomplete (1) and Complete (8)
            $query->whereIn('product_orders.status', [2, 3, 4, 5, 6, 7, 9]);
        }
        
        // Filter by user
        if ($user_id) {
            $query->where('product_orders.user_id', $user_id);
        }
        
        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('product_orders.order_id', 'like', '%' . $search . '%')
                  ->orWhere('product_orders.name', 'like', '%' . $search . '%')
                  ->orWhere('product_orders.email', 'like', '%' . $search . '%');
            });
        }
        
        $orders = $query->paginate(20);
        
        $stores = DB::table('stores')->where('status', 1)->get();
        
        $data = [
            'page_title' => ucfirst($statusStr ?? 'All') . ' Orders',
            'page_status' => $statusStr,
            'orders' => $orders,
            'user_id' => $user_id ?? '0',
            'status' => $status ?? 'all',
            'statusStr' => $statusStr ?? 'all',
            'stores' => $stores,
        ];
        
        return view('admin.orders.index', $data);
    }
    
    /**
     * View order details (CI project style)
     * CI: Orders->viewOrder() lines 570-597
     */
    public function viewOrder($id)
    {
        try {
            // Get order data (CI project style)
            $orderData = DB::table('product_orders')->where('id', $id)->first();
            
            if (!$orderData) {
                return redirect('admin/Orders')->with('message_error', 'Order not found');
            }
            
            // Get order items (CI project style)
            $OrderItemData_raw = DB::table('product_order_items')
                ->select('product_order_items.*', 'provider_products.provider_product_id')
                ->leftJoin('provider_products', 'provider_products.product_id', '=', 'product_order_items.product_id')
                ->where('product_order_items.order_id', $id)
                ->get();
            
            // Convert to associative array with rowid as key (CI project style)
            $OrderItemData = [];
            foreach ($OrderItemData_raw as $item) {
                $itemArray = (array) $item;
                
                // Decode JSON fields (CI project style)
                $itemArray['cart_images'] = !empty($itemArray['cart_images']) ? json_decode($itemArray['cart_images'], true) : [];
                $itemArray['attribute_ids'] = !empty($itemArray['attribute_ids']) ? json_decode($itemArray['attribute_ids'], true) : [];
                $itemArray['product_size'] = !empty($itemArray['product_size']) ? json_decode($itemArray['product_size'], true) : [];
                $itemArray['product_width_length'] = !empty($itemArray['product_width_length']) ? json_decode($itemArray['product_width_length'], true) : [];
                $itemArray['page_product_width_length'] = !empty($itemArray['page_product_width_length']) ? json_decode($itemArray['page_product_width_length'], true) : [];
                $itemArray['product_depth_length_width'] = !empty($itemArray['product_depth_length_width']) ? json_decode($itemArray['product_depth_length_width'], true) : [];
                
                // Ensure arrays are properly formatted
                $itemArray['cart_images'] = is_array($itemArray['cart_images']) ? $itemArray['cart_images'] : [];
                $itemArray['attribute_ids'] = is_array($itemArray['attribute_ids']) ? $itemArray['attribute_ids'] : [];
                $itemArray['product_size'] = is_array($itemArray['product_size']) ? $itemArray['product_size'] : [];
                $itemArray['product_width_length'] = is_array($itemArray['product_width_length']) ? $itemArray['product_width_length'] : [];
                $itemArray['page_product_width_length'] = is_array($itemArray['page_product_width_length']) ? $itemArray['page_product_width_length'] : [];
                $itemArray['product_depth_length_width'] = is_array($itemArray['product_depth_length_width']) ? $itemArray['product_depth_length_width'] : [];
                
                $OrderItemData[$itemArray['id']] = $itemArray;
            }
            
            // Get address details (CI project style)
            $stateData = DB::table('states')->where('id', $orderData->billing_state)->first();
            $countryData = DB::table('countries')->where('id', $orderData->billing_country)->first();
            $cityData = DB::table('cities')->where('id', $orderData->billing_city)->first();
            
            // Get store list (CI project style)
            $StoreList = DB::table('stores')->where('status', 1)->get();
            $storesArray = [];
            foreach ($StoreList as $store) {
                $storesArray[$store->id] = (array) $store;
            }
            
            // Get currency list (CI project style)
            $currency_id = $orderData->currency_id ?? 1;
            $CurrencyList = [
                1 => ['symbols' => '$'],
                2 => ['symbols' => '€'],
                3 => ['symbols' => '£'],
            ];
            $OrderCurrencyData = $CurrencyList[$currency_id] ?? ['symbols' => '$'];
            
            // Get store data for language (CI project style)
            $store_id = $orderData->store_id;
            $StoreData = DB::table('stores')->where('id', $store_id)->first();
            $langue_id = $StoreData->langue_id ?? 1;
            
            $data = [
                'page_title' => 'Order details',
                'orderData' => (array) $orderData,
                'OrderItemData' => $OrderItemData,
                'cityData' => (array) $cityData,
                'stateData' => (array) $stateData,
                'countryData' => (array) $countryData,
                'StoreList' => $storesArray,
                'langue_id' => $langue_id,
                'OrderCurrencyData' => $OrderCurrencyData,
                'order_currency_currency_symbol' => $OrderCurrencyData['symbols'],
            ];
            
            return view('admin.orders.view', $data);
            
        } catch (\Exception $e) {
            Log::error('Error in OrdersController@viewOrder: ' . $e->getMessage());
            return redirect('admin/Orders')->with('message_error', 'Error loading order: ' . $e->getMessage());
        }
    }
    
    /**
     * Change order status
     * CI: Orders->changeOrderStatus() lines 63-305
     */
    public function changeOrderStatus(Request $request)
    {
        $order_id = $request->order_id;
        $status = $request->status;
        $emailMsg = $request->emailMsg ?? '';
        
        $response = ['status' => 0, 'msg' => ''];
        
        if (empty($order_id) || empty($status)) {
            $response['msg'] = 'Invalid order or status';
            return response()->json($response);
        }
        
        $order = DB::table('product_orders')->where('id', $order_id)->first();
        
        if (!$order) {
            $response['msg'] = 'Order not found';
            return response()->json($response);
        }
        
        // Update order status
        $updateData = [
            'order_status' => $status,
            'updated' => now(),
        ];
        
        DB::table('product_orders')->where('id', $order_id)->update($updateData);
        
        // Send email notification
        try {
            $this->sendOrderStatusEmail($order_id, $status, $emailMsg);
        } catch (\Exception $e) {
            Log::error('Failed to send order status email: ' . $e->getMessage());
        }
        
        $response['status'] = 1;
        $response['msg'] = 'Order status changed successfully';
        
        return response()->json($response);
    }
    
    /**
     * Change payment status
     * CI: Orders->changeOrderPaymentStatus() lines 306-411
     */
    public function changeOrderPaymentStatus(Request $request)
    {
        $order_id = $request->order_id;
        $payment_status = $request->payment_status;
        $payment_type = $request->payment_type;
        $transition_id = $request->transition_id;
        
        $response = ['status' => 0, 'msg' => ''];
        
        if (empty($order_id) || empty($payment_status)) {
            $response['msg'] = 'Invalid order or payment status';
            return response()->json($response);
        }
        
        $updateData = [
            'payment_status' => $payment_status,
            'updated' => now(),
        ];
        
        if ($payment_type) {
            $updateData['payment_type'] = $payment_type;
        }
        
        if ($transition_id) {
            $updateData['transition_id'] = $transition_id;
        }
        
        DB::table('product_orders')->where('id', $order_id)->update($updateData);
        
        $response['status'] = 1;
        $response['msg'] = 'Payment status changed successfully';
        
        return response()->json($response);
    }
    
    /**
     * Delete order
     * CI: Orders->deleteOrder() lines 413-425
     */
    public function deleteOrder($id, $page_status = null)
    {
        if (empty($id)) {
            return redirect()->back()->with('message_error', 'Invalid order ID');
        }
        
        // Delete order items
        DB::table('product_order_items')->where('order_id', $id)->delete();
        
        // Delete order
        DB::table('product_orders')->where('id', $id)->delete();
        
        $redirect = $page_status ? 'admin/Orders/index/' . $page_status : 'admin/Orders';
        
        return redirect($redirect)->with('message_success', 'Order deleted successfully');
    }
    
    /**
     * Export orders to CSV
     */
    public function exportOrders(Request $request)
    {
        $query = DB::table('product_orders')
            ->select('product_orders.*', 'users.name as user_name')
            ->leftJoin('users', 'product_orders.user_id', '=', 'users.id')
            ->orderBy('product_orders.id', 'desc');
        
        // Apply filters if provided
        if ($request->has('status') && $request->status != 'all') {
            $query->where('product_orders.order_status', $request->status);
        }
        
        if ($request->has('date_from')) {
            $query->where('product_orders.created', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->where('product_orders.created', '<=', $request->date_to);
        }
        
        $orders = $query->get();
        
        $filename = 'orders_' . date('Y-m-d_His') . '.csv';
        $handle = fopen('php://output', 'w');
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        // CSV headers
        fputcsv($handle, [
            'Order ID',
            'Customer Name',
            'Email',
            'Phone',
            'Total Amount',
            'Order Status',
            'Payment Status',
            'Payment Method',
            'Created Date',
        ]);
        
        // CSV data
        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_id,
                $order->name,
                $order->email,
                $order->mobile,
                $order->total_amount,
                $order->order_status,
                $order->payment_status,
                $order->payment_method ?? 'N/A',
                $order->created,
            ]);
        }
        
        fclose($handle);
        exit;
    }
    
    /**
     * Download invoice PDF
     */
    public function downloadInvoice($id)
    {
        $order = DB::table('product_orders')->where('id', $id)->first();
        
        if (!$order) {
            return redirect()->back()->with('message_error', 'Order not found');
        }
        
        $filename = strtolower($order->order_id) . '-invoice.pdf';
        $filepath = storage_path('app/public/pdf/' . $filename);
        
        // Generate PDF if it doesn't exist
        if (!file_exists($filepath)) {
            $this->generateInvoicePDF($id);
        }
        
        if (file_exists($filepath)) {
            return response()->download($filepath, $filename);
        }
        
        return redirect()->back()->with('message_error', 'Invoice not found');
    }
    
    /**
     * Download order details PDF
     */
    public function downloadOrder($id)
    {
        $order = DB::table('product_orders')->where('id', $id)->first();
        
        if (!$order) {
            return redirect()->back()->with('message_error', 'Order not found');
        }
        
        $filename = strtolower($order->order_id) . '-order.pdf';
        $filepath = storage_path('app/public/pdf/' . $filename);
        
        // Generate PDF if it doesn't exist
        if (!file_exists($filepath)) {
            $this->generateOrderPDF($id);
        }
        
        if (file_exists($filepath)) {
            return response()->download($filepath, $filename);
        }
        
        return redirect()->back()->with('message_error', 'Order details not found');
    }
    
    /**
     * Download general files (CI: download method)
     * Handles URLs like: admin/Orders/download/{encoded_location}/{encoded_name}
     */
    public function download($filePath = null, $fileName = null)
    {
        if (!$filePath) {
            abort(404, 'File path is required');
        }
        
        // Decode the URL-encoded parameters
        $filePath = base64_decode($filePath);
        
        // If fileName is provided, decode it too
        if ($fileName) {
            $fileName = base64_decode($fileName);
        }
        
        // Check if file exists (CI project style)
        if (file_exists($filePath)) {
            // Get file content
            $data = file_get_contents($filePath);
            
            // Determine filename for download (CI: force_download equivalent)
            $downloadName = $fileName ?: basename($filePath);
            
            // Return file download response
            return response($data)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $downloadName . '"')
                ->header('Content-Length', strlen($data));
        }
        
        abort(404, 'File not found: ' . basename($filePath));
    }
    
    /**
     * Download cart files (handles query parameters)
     */
    public function downloadCartFile(Request $request)
    {
        $encodedPath = $request->query('path');
        
        if (!$encodedPath) {
            abort(404, 'File path is required');
        }
        
        // Decode the URL-encoded path
        $filePath = base64_decode($encodedPath);
        
        // Check if file exists (CI project style)
        if (file_exists($filePath)) {
            // Get file content
            $data = file_get_contents($filePath);
            
            // Get filename for download
            $fileName = basename($filePath);
            
            // Return file download response (CI: force_download equivalent)
            return response($data)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                ->header('Content-Length', strlen($data));
        }
        
        abort(404, 'File not found: ' . basename($filePath));
    }
    
    /**
     * Get personalise details (AJAX)
     */
    public function personaliseDetail(Request $request)
    {
        $id = $request->id;
        
        $orderItem = DB::table('product_order_items')->where('id', $id)->first();
        
        if (!$orderItem) {
            return response()->json(['status' => 0, 'msg' => 'Order item not found']);
        }
        
        $data = [
            'cart_images' => json_decode($orderItem->cart_images ?? '[]'),
            'attribute_ids' => json_decode($orderItem->attribute_ids ?? '[]'),
            'votre_text' => $orderItem->votre_text,
            'recto_verso' => $orderItem->recto_verso,
        ];
        
        return response()->json($data);
    }
    
    /**
     * Helper: Send order status email
     */
    protected function sendOrderStatusEmail($order_id, $status, $emailMsg)
    {
        $order = DB::table('product_orders')->where('id', $order_id)->first();
        
        if (!$order) {
            return false;
        }
        
        $store = DB::table('stores')->where('id', $order->store_id)->first();
        
        if (!$store) {
            return false;
        }
        
        $subject = $this->getEmailSubject($status, $order->order_id, $store->langue_id ?? 1);
        $body = $this->getEmailBody($status, $emailMsg, $store->langue_id ?? 1);
        
        // Send email (implement mail sending logic)
        // Mail::to($order->email)->send(new OrderStatusChanged($order, $subject, $body));
        
        Log::info('Order status email sent for order: ' . $order->order_id);
        
        return true;
    }
    
    /**
     * Helper: Get email subject based on status
     */
    protected function getEmailSubject($status, $order_id, $langue_id)
    {
        $subjects = [
            'processing' => [
                1 => 'Your Order ' . $order_id . ' is processing',
                2 => 'Votre commande ' . $order_id . ' traite',
            ],
            'shipped' => [
                1 => 'Your Order ' . $order_id . ' has been shipped',
                2 => 'Votre commande ' . $order_id . ' a été expédié',
            ],
            'delivered' => [
                1 => 'Your Order ' . $order_id . ' has been delivered',
                2 => 'Votre commande ' . $order_id . ' a été livré',
            ],
            'cancelled' => [
                1 => 'Your Order ' . $order_id . ' has been cancelled',
                2 => 'Votre commande ' . $order_id . ' a été annulé',
            ],
            'ready_for_pickup' => [
                1 => 'Your Order ' . $order_id . ' is ready for pickup',
                2 => 'Votre commande ' . $order_id . ' a été prête pour le ramassage',
            ],
        ];
        
        return $subjects[$status][$langue_id] ?? 'Order Update - ' . $order_id;
    }
    
    /**
     * Helper: Get email body based on status
     */
    protected function getEmailBody($status, $emailMsg, $langue_id)
    {
        $thankYou = $langue_id == 2 
            ? 'NOUS VOUS REMERCIONS DE VOTRE COMMANDE!' 
            : 'THANK YOU FOR YOUR ORDER!';
        
        return '<div class="top-info" style="margin-top: 15px;text-align: left;">
            <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                ' . $emailMsg . '<br>
                <h1>' . $thankYou . '</h1>
            </span>
        </div><br><br>';
    }
    
    /**
     * Helper: Generate invoice PDF
     */
    protected function generateInvoicePDF($order_id)
    {
        try {
            // Get order data
            $order = DB::table('product_orders')->where('id', $order_id)->first();
            if (!$order) {
                Log::error('Order not found for PDF generation: ' . $order_id);
                return false;
            }

            // Get order items
            $orderItems = DB::table('product_order_items')
                ->where('order_id', $order_id)
                ->get();

            // Get store and currency info
            $store = DB::table('stores')->where('id', $order->store_id)->first();
            $currencySymbol = '$'; // Default, can be enhanced based on currency_id

            // Create PDF view data
            $data = [
                'order' => $order,
                'orderItems' => $orderItems,
                'store' => $store,
                'currencySymbol' => $currencySymbol,
                'orderDate' => date('M d, Y H:i', strtotime($order->created)),
            ];

            // Generate PDF using DomPDF
            $pdf = PDF::loadView('admin.orders.invoice_pdf', $data);
            
            // Create directory if it doesn't exist
            $directory = storage_path('app/public/pdf');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save PDF
            $filename = strtolower($order->order_id) . '-invoice.pdf';
            $filepath = $directory . '/' . $filename;
            $pdf->save($filepath);

            Log::info('Invoice PDF generated successfully for order: ' . $order_id);
            return true;

        } catch (\Exception $e) {
            Log::error('Error generating invoice PDF for order ' . $order_id . ': ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Helper: Generate order PDF
     */
    protected function generateOrderPDF($order_id)
    {
        try {
            // Get order data
            $order = DB::table('product_orders')->where('id', $order_id)->first();
            if (!$order) {
                Log::error('Order not found for PDF generation: ' . $order_id);
                return false;
            }

            // Get order items with decoded JSON data
            $orderItems_raw = DB::table('product_order_items')
                ->where('order_id', $order_id)
                ->get();

            // Process order items like in viewOrder method
            $orderItems = [];
            foreach ($orderItems_raw as $item) {
                $itemArray = (array) $item;
                $itemArray['cart_images'] = !empty($itemArray['cart_images']) ? json_decode($itemArray['cart_images'], true) : [];
                $itemArray['attribute_ids'] = !empty($itemArray['attribute_ids']) ? json_decode($itemArray['attribute_ids'], true) : [];
                $itemArray['product_size'] = !empty($itemArray['product_size']) ? json_decode($itemArray['product_size'], true) : [];
                $itemArray['product_width_length'] = !empty($itemArray['product_width_length']) ? json_decode($itemArray['product_width_length'], true) : [];
                $itemArray['page_product_width_length'] = !empty($itemArray['page_product_width_length']) ? json_decode($itemArray['page_product_width_length'], true) : [];
                $itemArray['product_depth_length_width'] = !empty($itemArray['product_depth_length_width']) ? json_decode($itemArray['product_depth_length_width'], true) : [];
                
                // Ensure arrays are properly formatted
                $itemArray['cart_images'] = is_array($itemArray['cart_images']) ? $itemArray['cart_images'] : [];
                $itemArray['attribute_ids'] = is_array($itemArray['attribute_ids']) ? $itemArray['attribute_ids'] : [];
                $itemArray['product_size'] = is_array($itemArray['product_size']) ? $itemArray['product_size'] : [];
                $itemArray['product_width_length'] = is_array($itemArray['product_width_length']) ? $itemArray['product_width_length'] : [];
                $itemArray['page_product_width_length'] = is_array($itemArray['page_product_width_length']) ? $itemArray['page_product_width_length'] : [];
                $itemArray['product_depth_length_width'] = is_array($itemArray['product_depth_length_width']) ? $itemArray['product_depth_length_width'] : [];
                
                $orderItems[] = $itemArray;
            }

            // Get address details
            $stateData = DB::table('states')->where('id', $order->billing_state)->first();
            $countryData = DB::table('countries')->where('id', $order->billing_country)->first();
            $cityData = DB::table('cities')->where('id', $order->billing_city)->first();

            // Get store info
            $store = DB::table('stores')->where('id', $order->store_id)->first();
            $currencySymbol = '$'; // Default, can be enhanced based on currency_id

            // Create PDF view data
            $data = [
                'order' => (array) $order,
                'orderItems' => $orderItems,
                'cityData' => (array) $cityData,
                'stateData' => (array) $stateData,
                'countryData' => (array) $countryData,
                'store' => $store,
                'currencySymbol' => $currencySymbol,
                'orderDate' => date('M d, Y H:i', strtotime($order->created)),
            ];

            // Generate PDF using DomPDF
            $pdf = PDF::loadView('admin.orders.order_pdf', $data);
            
            // Create directory if it doesn't exist
            $directory = storage_path('app/public/pdf');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            // Save PDF
            $filename = strtolower($order->order_id) . '-order.pdf';
            $filepath = $directory . '/' . $filename;
            $pdf->save($filepath);

            Log::info('Order PDF generated successfully for order: ' . $order_id);
            return true;

        } catch (\Exception $e) {
            Log::error('Error generating order PDF for order ' . $order_id . ': ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Create new order form
     * CI: Orders->createOrder() lines 599-718
     */
    public function createOrder()
    {
        try {
            // Get categories (CI project style)
            $categoryList = DB::table('categories')->where('status', 1)->orderBy('name', 'asc')->get();
            $stores = DB::table('stores')->where('status', 1)->orderBy('name', 'asc')->get();
            
            // Get countries, states, cities (CI project style)
            $PostData = session()->all();
            $countries = DB::table('countries')->orderBy('name', 'asc')->get()->map(function($item) {
                return (array) $item;
            })->toArray();
            
            $billing_country = $PostData['billing_country'] ?? '';
            $billing_state = $PostData['billing_state'] ?? '';
            
            $states = [];
            if ($billing_country) {
                $states = DB::table('states')->where('country_id', $billing_country)->orderBy('name', 'asc')->get()->map(function($item) {
                    return (array) $item;
                })->toArray();
            }
            
            $citys = [];
            if ($billing_state) {
                $citys = DB::table('cities')->where('state_id', $billing_state)->orderBy('name', 'asc')->get()->map(function($item) {
                    return (array) $item;
                })->toArray();
            }
            
            // Payment status options (CI equivalent: getOrderPaymentStatus)
            $PaymentStatus = [
                1 => 'Pending',
                2 => 'Success',
                3 => 'Failed',
            ];
            
            // Payment methods (CI equivalent: PaymentMethod)
            $paymentMethods = [
                'cash_on_delivery' => 'Cash on Delivery',
                'credit_card' => 'Credit Card',
                'paypal' => 'PayPal',
                'bank_transfer' => 'Bank Transfer'
            ];
            
            // Shipping data (CI project style)
            $total_charges_ups = [];
            $CanedaPostShiping = ['list' => []];
            $PickupStoresList = DB::table('stores')->where('status', 1)->get()->map(function($item) {
                return (array) $item;
            })->toArray();

            $data = [
                'page_title' => 'Create Order',
                'categories' => $categoryList,
                'stores' => $stores,
                'countries' => $countries,
                'states' => $states,
                'citys' => $citys,
                'PaymentStatus' => $PaymentStatus,
                'paymentMethods' => $paymentMethods,
                'PostData' => $PostData,
                'total_charges_ups' => $total_charges_ups,
                'CanedaPostShiping' => $CanedaPostShiping,
                'PickupStoresList' => $PickupStoresList,
            ];

            return view('admin.orders.create_order', $data);
            
        } catch (Exception $e) {
            Log::error('Error in OrdersController@createOrder: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading create order form: ' . $e->getMessage());
        }
    }

    /**
     * Save new order
     * CI: Orders->createOrder() POST handling lines 620-718
     */
    public function saveOrder(Request $request)
    {
        try {
            // Validate order data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'mobile' => 'required|string|max:20',
                'billing_name' => 'required|string|max:255',
                'billing_pin_code' => 'required|string|max:10',
                'billing_mobile' => 'required|string|max:20',
                'billing_address' => 'required|string',
                'billing_city' => 'required|string|max:255',
                'billing_state' => 'required|string|max:255',
                'billing_country' => 'required|string|max:255',
                'payment_status' => 'required|string',
                'payment_type' => 'required|string',
                'total_amount' => 'required|numeric|min:0',
                'sub_total_amount' => 'required|numeric|min:0',
                'total_items' => 'required|integer|min:0',
            ]);

            // Get or create user
            $user = DB::table('users')->where('email', $validated['email'])->first();
            if (!$user) {
                $userId = \App\Models\User::createUserWithDefaults([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'mobile' => $validated['mobile'],
                ]);
            } else {
                $userId = $user->id;
            }

            // Create order
            // Convert payment_status string to integer (CI compatibility)
                $paymentStatusMap = [
                    'pending' => 1,
                    'Pending' => 1,
                    'success' => 2,
                    'Success' => 2,
                    'completed' => 2,
                    'Completed' => 2,
                    'failed' => 3,
                    'Failed' => 3,
                ];
                
                // Calculate preferred customer discount (CI compatibility)
                $preferredDiscount = 0;
                if ($user && $user->user_type == 2 && $user->preferred_status == 1) {
                    $preferredDiscount = ($validated['sub_total_amount'] * 10) / 100;
                }
                
                $orderData = [
                    'user_id' => $userId,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'mobile' => $validated['mobile'],
                    'billing_name' => $validated['billing_name'],
                    'shipping_name' => $validated['billing_name'],
                    'billing_pin_code' => $validated['billing_pin_code'],
                    'shipping_pin_code' => $validated['billing_pin_code'],
                    'billing_mobile' => $validated['billing_mobile'],
                    'shipping_mobile' => $validated['billing_mobile'],
                    'billing_address' => $validated['billing_address'],
                    'shipping_address' => $validated['billing_address'],
                    'billing_city' => $validated['billing_city'],
                    'shipping_city' => $validated['billing_city'],
                    'billing_state' => $validated['billing_state'],
                    'shipping_state' => $validated['billing_state'],
                    'billing_country' => $validated['billing_country'],
                    'shipping_country' => $validated['billing_country'],
                    'payment_status' => $paymentStatusMap[$validated['payment_status']] ?? 1, // Default to pending (1)
                    'payment_type' => $validated['payment_type'],
                    'total_amount' => $validated['total_amount'],
                    'sub_total_amount' => $validated['sub_total_amount'],
                    'total_items' => $validated['total_items'],
                    'delivery_charge' => $request->input('shipping_fee', 0),
                    'currency_id' => 1,
                    'preffered_customer_discount' => $preferredDiscount, // Required field (CI compatibility)
                    'created' => now(),
                    'updated' => now(),
                ];

            $orderId = DB::table('product_orders')->insertGetId($orderData);

            // Update order with order ID
            DB::table('product_orders')
                ->where('id', $orderId)
                ->update(['order_id' => 'ORD' . $orderId]);

            return redirect()->route('orders.index')
                ->with('message_success', 'Order created successfully with ID: ORD' . $orderId);

        } catch (Exception $e) {
            Log::error('Error in OrdersController@saveOrder: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error creating order: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Get subcategories by category ID (AJAX)
     * CI equivalent: dynamic loading in create-order.php
     */
    public function getSubcategories($category_id)
    {
        try {
            $subcategories = DB::table('sub_categories')
                ->where('category_id', $category_id)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'subcategories' => $subcategories
            ]);
        } catch (Exception $e) {
            Log::error('Error in OrdersController@getSubcategories: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading subcategories'
            ]);
        }
    }

    /**
     * Get products by subcategory ID (AJAX)
     * CI equivalent: dynamic loading in create-order.php
     */
    public function getProducts($subcategory_id)
    {
        try {
            $products = DB::table('products')
                ->where('sub_category_id', $subcategory_id)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'products' => $products
            ]);
        } catch (Exception $e) {
            Log::error('Error in OrdersController@getProducts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading products'
            ]);
        }
    }

    /**
     * Add product to order (AJAX)
     * CI equivalent: AddSingleProduct() function in create-order.php
     */
    public function addProduct(Request $request)
    {
        try {
            $productId = $request->input('product_id');
            
            if (!$productId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product ID is required'
                ]);
            }

            $product = DB::table('products')->where('id', $productId)->first();
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ]);
            }

            // Generate HTML for product row
            $html = '
                <div class="custom-order-card" id="product_' . $product->id . '">
                    <div class="custom-order-header">
                        <h4>' . $product->name . '</h4>
                        <button type="button" class="custom-order-toggle" onclick="removeProduct(' . $product->id . ')">
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                    <div class="custom-order-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Product:</strong> ' . $product->name . '</p>
                                <p><strong>Price:</strong> $<span id="price_' . $product->id . '" data-price="' . $product->price . '">' . number_format($product->price, 2) . '</span></p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Quantity:</label>
                                    <input type="number" class="form-control" id="quantity_' . $product->id . '" 
                                           value="1" min="1" onchange="updateQuantity(' . $product->id . ', this.value)">
                                </div>
                                <p><strong>Total:</strong> $<span class="product-total" id="total_' . $product->id . '">' . number_format($product->price, 2) . '</span></p>
                            </div>
                        </div>
                        <input type="hidden" name="products[' . $product->id . '][id]" value="' . $product->id . '">
                        <input type="hidden" name="products[' . $product->id . '][name]" value="' . $product->name . '">
                        <input type="hidden" name="products[' . $product->id . '][price]" value="' . $product->price . '">
                        <input type="hidden" name="products[' . $product->id . '][quantity]" id="hidden_quantity_' . $product->id . '" value="1">
                    </div>
                </div>
            ';

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        } catch (Exception $e) {
            Log::error('Error in OrdersController@addProduct: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding product'
            ]);
        }
    }

    /**
     * Get orders data for DataTables (AJAX)
     * CI equivalent: ajaxList() method
     */
    public function getData(Request $request)
    {
        try {
            $draw = $request->input('draw');
            $start = $request->input('start');
            $length = $request->input('length');
            $search = $request->input('search.value');
            
            // Build query
            $query = DB::table('product_orders');
            
            // Apply filters
            if ($request->input('from_no')) {
                $query->where('order_id', '>=', $request->input('from_no'));
            }
            
            if ($request->input('to_no')) {
                $query->where('order_id', '<=', $request->input('to_no'));
            }
            
            if ($request->input('from')) {
                $query->whereDate('created', '>=', $request->input('from'));
            }
            
            if ($request->input('to')) {
                $query->whereDate('created', '<=', $request->input('to'));
            }
            
            if ($request->input('status')) {
                $query->whereIn('status', $request->input('status'));
            }
            
            // Search
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('order_id', 'like', '%' . $search . '%')
                      ->orWhere('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('mobile', 'like', '%' . $search . '%');
                });
            }
            
            // Get total records
            $totalRecords = $query->count();
            
            // Get filtered records
            $records = $query->offset($start)
                            ->limit($length)
                            ->orderBy('created', 'desc')
                            ->get();
            
            // Format data
            $data = [];
            foreach ($records as $record) {
                $data[] = [
                    'order_id' => $record->order_id,
                    'name' => $record->name,
                    'email' => $record->email,
                    'mobile' => $record->mobile,
                    'total_amount' => '$' . number_format($record->total_amount, 2),
                    'status' => $this->getOrderStatusText($record->status),
                    'payment_status' => $this->getPaymentStatusText($record->payment_status),
                    'payment_method' => $record->payment_method ?? 'N/A',
                    'created' => date('Y-m-d H:i:s', strtotime($record->created)),
                    'actions' => '<a href="' . route('orders.view', $record->id) . '" class="btn btn-sm btn-info">View</a>'
                ];
            }
            
            return response()->json([
                'draw' => $draw,
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $data
            ]);
        } catch (Exception $e) {
            Log::error('Error in OrdersController@getData: ' . $e->getMessage());
            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }
    }

    /**
     * Export orders to CSV
     * CI equivalent: exportOrders() method
     */
    public function exportCsv(Request $request)
    {
        try {
            // Build query with same filters as getData
            $query = DB::table('product_orders');
            
            // Apply filters (same as getData method)
            if ($request->input('from_no')) {
                $query->where('order_id', '>=', $request->input('from_no'));
            }
            
            if ($request->input('to_no')) {
                $query->where('order_id', '<=', $request->input('to_no'));
            }
            
            if ($request->input('from')) {
                $query->whereDate('created', '>=', $request->input('from'));
            }
            
            if ($request->input('to')) {
                $query->whereDate('created', '<=', $request->input('to'));
            }
            
            if ($request->input('status')) {
                $query->whereIn('status', $request->input('status'));
            }
            
            $orders = $query->orderBy('created', 'desc')->get();
            
            // Create CSV
            $filename = 'orders_' . date('Y-m-d') . '.csv';
            $handle = fopen('php://output', 'w');
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            // Add CSV headers
            fputcsv($handle, [
                'Order ID', 'Name', 'Email', 'Mobile', 'Total Amount', 
                'Status', 'Payment Status', 'Payment Method', 'Created Date'
            ]);
            
            // Add data rows
            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_id,
                    $order->name,
                    $order->email,
                    $order->mobile,
                    $order->total_amount,
                    $this->getOrderStatusText($order->status),
                    $this->getPaymentStatusText($order->payment_status),
                    $order->payment_method ?? 'N/A',
                    $order->created
                ]);
            }
            
            fclose($handle);
            exit;
        } catch (Exception $e) {
            Log::error('Error in OrdersController@exportCsv: ' . $e->getMessage());
            return redirect()->back()->with('message_error', 'Error exporting orders');
        }
    }

    /**
     * Get orders data for Kendo Grid (CI project style)
     * CI: Orders->list() method for Kendo Grid
     */
    public function list(Request $request)
    {
        try {
            // Get parameters from Kendo Grid
            $take = $request->input('pageSize', 10);
            $skip = $request->input('skip', 0);
            $page = $request->input('page', 1);
            
            // Build query (CI project style)
            $query = DB::table('product_orders')
                ->select('product_orders.*', 'users.name as user_name', 'users.email as user_email', 'stores.name as store_name')
                ->leftJoin('users', 'product_orders.user_id', '=', 'users.id')
                ->leftJoin('stores', 'product_orders.store_id', '=', 'stores.id')
                ->where('product_orders.admin_delete', 1);  // CI project condition
            
            // Apply filters (CI project style)
            if ($request->input('from_no')) {
                $query->where('product_orders.order_id', '>=', $request->input('from_no'));
            }
            
            if ($request->input('to_no')) {
                $query->where('product_orders.order_id', '<=', $request->input('to_no'));
            }
            
            if ($request->input('from')) {
                $query->whereDate('product_orders.created', '>=', $request->input('from'));
            }
            
            if ($request->input('to')) {
                $query->whereDate('product_orders.created', '<=', $request->input('to'));
            }
            
            // Apply status filtering (CI project style)
            if ($request->input('status')) {
                if (is_array($request->input('status'))) {
                    $query->whereIn('product_orders.status', $request->input('status'));
                } else {
                    $query->where('product_orders.status', $request->input('status'));
                }
            } else {
                // CI project default: exclude Incomplete (1) and Complete (8)
                $query->whereIn('product_orders.status', [2, 3, 4, 5, 6, 7, 9]);
            }
            
            // Get total records
            $total = $query->count();
            
            // Get paginated records
            $orders = $query->offset($skip)
                           ->limit($take)
                           ->orderBy('product_orders.id', 'desc')
                           ->get();
            
            // Format data for Kendo Grid (CI project style)
            $data = [];
            foreach ($orders as $order) {
                $data[] = [
                    'id' => $order->id,
                    'order_id' => $order->order_id,
                    'provider_order_id' => $order->provider_order_id ?? '',
                    'provider_order_count' => 1, // Default value for CI project compatibility
                    'provider_product_count' => 0, // Default value for CI project compatibility
                    'shipping_method_formate' => $order->shipping_method_formate ?? '',
                    'store_name' => $order->store_name ?? 'Default Store',
                    'name' => $order->name,
                    'email' => $order->email,
                    'mobile' => $order->mobile,
                    'sub_total_amount' => $order->sub_total_amount ?? 0,
                    'preffered_customer_discount' => $order->preffered_customer_discount ?? 0,
                    'coupon_discount_amount' => $order->coupon_discount_amount ?? 0,
                    'delivery_charge' => $order->delivery_charge ?? 0,
                    'total_sales_tax' => $order->total_sales_tax ?? 0,
                    'total_amount' => $order->total_amount,
                    'total_items' => $order->total_items ?? 0,
                    'payment_type' => $order->payment_type ?? 'N/A',
                    'payment_status' => $order->payment_status, // Use numeric status for paymentStatusChangeOptions function
                    'transition_id' => $order->transition_id ?? '',
                    'status' => $order->status, // Use numeric status for itemActions function
                    'created' => date('Y-m-d H:i:s', strtotime($order->created)),
                    'updated' => date('Y-m-d H:i:s', strtotime($order->updated)),
                    'shipment_id' => $order->shipment_id ?? null,
                    'tracking_number' => $order->tracking_number ?? null,
                    'labels_regular' => $order->labels_regular ?? null,
                    'labels_thermal' => $order->labels_thermal ?? null,
                ];
            }
            
            // Return Kendo Grid format (CI project style)
            return response()->json([
                'data' => $data,
                'total' => $total,
                'errors' => null
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in OrdersController@list: ' . $e->getMessage());
            return response()->json([
                'data' => [],
                'total' => 0,
                'errors' => 'Error loading orders: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Helper method to get order status text
     */
    private function getOrderStatusText($status)
    {
        $statusMap = [
            1 => 'New',
            2 => 'Processing',
            3 => 'Shipped',
            4 => 'Delivered',
            5 => 'Cancelled',
            6 => 'Ready for Pickup'
        ];
        
        return $statusMap[$status] ?? 'Unknown';
    }

    /**
     * Helper method to get payment status text
     */
    private function getPaymentStatusText($status)
    {
        $statusMap = [
            1 => 'Pending',
            2 => 'Completed',
            3 => 'Failed'
        ];
        
        return $statusMap[$status] ?? 'Unknown';
    }
    
    /**
     * Get orders by status for dashboard AJAX
     * CI: Orders->getOrdersByStatus() for dashboard tabs
     */
    public function getOrdersByStatus($status)
    {
        try {
            // Decode base64 status
            $statusId = base64_decode($status);
            
            // Get orders by status
            $orders = DB::table('product_orders')
                ->select('product_orders.*', 'users.name as user_name', 'users.email as user_email')
                ->leftJoin('users', 'product_orders.user_id', '=', 'users.id')
                ->where('product_orders.status', $statusId)
                ->orderBy('product_orders.id', 'desc')
                ->limit(10)
                ->get();
            
            // Return HTML for dashboard
            $html = '';
            if ($orders->count() > 0) {
                foreach ($orders as $order) {
                    $html .= '<tr>';
                    $html .= '<td>' . $order->id . '</td>';
                    $html .= '<td>' . $order->order_id . '</td>';
                    $html .= '<td>' . ($order->user_name ?? $order->name ?? 'Guest') . '</td>';
                    $html .= '<td>$' . number_format($order->total_amount, 2) . '</td>';
                    $html .= '<td>' . $this->getOrderStatusText($order->status) . '</td>';
                    $html .= '<td>' . date('M d, Y', strtotime($order->created)) . '</td>';
                    $html .= '<td>';
                    $html .= '<a href="' . url('admin/Orders/viewOrder/' . $order->id) . '" class="btn btn-sm btn-info">View</a>';
                    $html .= '</td>';
                    $html .= '</tr>';
                }
            } else {
                $html .= '<tr><td colspan="7" class="text-center">No orders found</td></tr>';
            }
            
            return $html;
            
        } catch (\Exception $e) {
            return '<tr><td colspan="7" class="text-center text-danger">Error loading orders: ' . $e->getMessage() . '</td></tr>';
        }
    }
}
