<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Order listing (replicate MyOrders->index lines 18-30)
     */
    public function index()
    {
        $loginId = session('loginId');
        $language_name = config('store.language_name', 'English');
        
        // Check authentication (lines 13-15)
        if (empty($loginId)) {
            return redirect('Homes');
        }
        
        $data = [];
        $data['page_title'] = 'Order History';
        
        // French translation (lines 24-26)
        if ($language_name == 'French') {
            $data['page_title'] = "Historique des commandes";
        }
        
        // Get user's orders (line 27)
        $orderData = $this->getProductOrderList($loginId);
        $data['orderData'] = $orderData;
        
        return view('public.orders.index', $data);
    }
    
    /**
     * Order detail view (replicate MyOrders->view lines 32-69)
     */
    public function view($id = null)
    {
        $loginId = session('loginId');
        $language_name = config('store.language_name', 'English');
        
        // Check authentication
        if (empty($loginId)) {
            return redirect('Homes');
        }
        
        if (!empty($id)) {
            $id = base64_decode($id);
        }
        
        // Get order data (lines 48-49)
        $orderData = $this->getProductOrderDataById($id);
        $OrderItemData = $this->getProductOrderItemDataById($id);
        
        // Get address data (lines 52-55)
        $stateData = $this->getStateById($orderData['billing_state']);
        $countryData = $this->getCountryById($orderData['billing_country']);
        $cityData = $this->getCityById($orderData['billing_city']);
        $salesTaxRatesProvinces_Data = $this->salesTaxRatesProvincesById($orderData['billing_state']);
        
        $data = [];
        $data['page_title'] = 'Order details';
        
        // French translation (lines 58-60)
        if ($language_name == 'French') {
            $data['page_title'] = "Détails de la commande";
        }
        
        $data['orderData'] = $orderData;
        $data['OrderItemData'] = $OrderItemData;
        $data['cityData'] = $cityData;
        $data['stateData'] = $stateData;
        $data['countryData'] = $countryData;
        $data['salesTaxRatesProvinces_Data'] = $salesTaxRatesProvinces_Data;
        
        return view('public.orders.view', $data);
    }
    
    /**
     * Delete order (replicate MyOrders->deleteOrder lines 71-88)
     */
    public function deleteOrder($id = null)
    {
        $language_name = config('store.language_name', 'English');
        
        if (!empty($id)) {
            $page_title = 'Order has been deleted';
            
            // French translation (lines 76-78)
            if ($language_name == 'French') {
                $page_title = "La commande a été supprimée";
            }
            
            if ($this->deleteProductOrder(base64_decode($id))) {
                session()->flash('message_success', $page_title . ' Successfully.');
            } else {
                session()->flash('message_error', $page_title . ' Unsuccessfully.');
            }
        } else {
            session()->flash('message_error', 'Missing information.');
        }
        
        return redirect('MyOrders');
    }
    
    /**
     * Change order status (cancel order) (replicate MyOrders->changeOrderStatus lines 90-219)
     */
    public function changeOrderStatus(Request $request)
    {
        $id = $request->input('order_id');
        $status = $request->input('status');
        $MobileMsg = $request->input('mobileMsg');
        $json = ['status' => 0, 'msg' => ''];
        
        if (!empty($id) && !empty($status) && $status == '6') {
            $postData = [
                'id' => $id,
                'status' => $status,
                'order_comment' => $MobileMsg,
            ];
            
            if ($this->saveProductOrder($postData)) {
                $orderData = $this->getProductOrderDataById($id);
                $orderItemData = $this->getProductOrderItemDataById($id);
                
                $store_id = $orderData['store_id'];
                $StoreData = $this->getStoreDataById($store_id);
                
                // Store configuration (lines 112-118)
                $store_url = $StoreData['url'];
                $store_phone = $StoreData['phone'];
                $from_name = $StoreData['name'];
                $from_email = $StoreData['from_email'];
                $admin_email1 = $StoreData['admin_email1'];
                $admin_email2 = $StoreData['admin_email2'];
                $admin_email3 = $StoreData['admin_email3'];
                
                $toEmail = $orderData['email'];
                $name = $orderData['name'];
                $order_id = $orderData['order_id'];
                $langue_id = $StoreData['langue_id'];
                
                // Build email for cancellation (lines 125-165)
                if ($status == 6) {
                    if ($langue_id == '2') {
                        // French email (lines 126-144)
                        $subject = 'Ordre ' . $order_id . ' a été annulé.';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                        <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                            Salut administrateur,
                           <br>
                            Désolé de vous informer que nous avons annulé votre commande' . $order_id . ' par ' . $name . '<br>La raison indiquée ci-dessous <br>' . $MobileMsg . '
                        </span>
                        </div><br>';
                        
                        $body_user = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                        <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                            Salut ' . $name . ',
                        <br>
                            Désolé de vous informer que vous avez annulé votre commande ' . $order_id . ' par ' . $name . '<br>La raison indiquée ci-dessous <br>' . $MobileMsg . '
                        </span>
                        </div><br>';
                        
                        $body_user = $this->getorderEmail($id, $subject, $body_user, $orderData['store_id']);
                        $body = $this->getorderEmail($id, $subject, $body, $orderData['store_id']);
                    } else {
                        // English email (lines 145-164)
                        $subject = 'Order ' . $order_id . ' has been cancelled.';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                            <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                Hi admin,
                            <br>
                                Sorry to inform you that we have cancelled your order ' . $order_id . ' by ' . $name . ' <br>The reason Indicated below <br>' . $MobileMsg . '
                            </span>
                            </div><br>';
                        
                        $body_user = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                            <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                Hi ' . $name . ',
                            <br>
                                Sorry to inform you that you have cancelled your order ' . $order_id . ' The reason <br>Indicated below <br>' . $MobileMsg . '
                            </span>
                            </div><br>';
                        
                        $body_user = $this->getorderEmail($id, $subject, $body_user, $orderData['store_id']);
                        $body = $this->getorderEmail($id, $subject, $body, $orderData['store_id']);
                    }
                    
                    // Determine PDF file names (lines 167-183)
                    if ($langue_id == 2) {
                        $invoice_file = $orderData['order_id'] . '-fr-invoice.pdf';
                        $invoice_file = strtolower($invoice_file);
                        $invoicefilePath = storage_path('app/pdf/' . $invoice_file);
                        
                        $order_file = $orderData['order_id'] . '-fr-order.pdf';
                        $order_file = strtolower($order_file);
                        $orderfilePath = storage_path('app/pdf/' . $order_file);
                    } else {
                        $invoice_file = $orderData['order_id'] . '-invoice.pdf';
                        $invoice_file = strtolower($invoice_file);
                        $invoicefilePath = storage_path('app/pdf/' . $invoice_file);
                        
                        $order_file = $orderData['order_id'] . '-order.pdf';
                        $order_file = strtolower($order_file);
                        $orderfilePath = storage_path('app/pdf/' . $order_file);
                    }
                    
                    // Generate PDFs if not exist (lines 185-191)
                    if (!file_exists($invoicefilePath)) {
                        $this->getOrderInvoicePdf($id, $store_id);
                    }
                    
                    if (!file_exists($orderfilePath)) {
                        $this->getOrderPdf($id, $store_id);
                    }
                    
                    $files = [
                        $invoice_file => $invoicefilePath,
                        $order_file => $orderfilePath,
                    ];
                    
                    // Send emails (lines 196-206)
                    $this->sendEmail($toEmail, $subject, $body_user, $from_email, $from_name, $files);
                    
                    if (!empty($admin_email1)) {
                        $this->sendEmail($admin_email1, $subject, $body, $from_email, $from_name, $files);
                    }
                    if (!empty($admin_email2)) {
                        $this->sendEmail($admin_email2, $subject, $body, $from_email, $from_name, $files);
                    }
                    if (!empty($admin_email3)) {
                        $this->sendEmail($admin_email3, $subject, $body, $from_email, $from_name, $files);
                    }
                }
                
                $json['status'] = 1;
                $json['msg'] = 'Your order has been cancelled successfully.';
            } else {
                $json['msg'] = 'Your order has been cancelled unsuccessfully';
            }
        } else {
            $json['msg'] = 'Your order has been cancelled unsuccessfully';
        }
        
        return response()->json($json);
    }
    
    /**
     * Download PDF (replicate MyOrders->downloadOrderPdf lines 239-297)
     */
    public function downloadOrderPdf($id = null, $type = 'invoice')
    {
        $orderData = $this->getProductOrderDataById($id);
        $store_id = $orderData['store_id'];
        $StoreData = $this->getStoreDataById($store_id);
        $langue_id = $StoreData['langue_id'];
        
        // Determine file name based on language and type (lines 255-272)
        if ($langue_id == '2') {
            if ($type == 'order') {
                $file_name = $orderData['order_id'] . "-fr-order.pdf";
            } else {
                $file_name = $orderData['order_id'] . "-fr-invoice.pdf";
            }
            $file_name = strtolower($file_name);
            $filePath = storage_path('app/pdf/' . $file_name);
        } else {
            if ($type == 'order') {
                $file_name = $orderData['order_id'] . "-order.pdf";
            } else {
                $file_name = $orderData['order_id'] . "-invoice.pdf";
            }
            $file_name = strtolower($file_name);
            $filePath = storage_path('app/pdf/' . $file_name);
        }
        
        // Check file exists and download (lines 280-295)
        if (file_exists($filePath)) {
            return response()->download($filePath, $file_name);
        } else {
            // Generate PDFs if not exist (lines 288-290)
            $this->getOrderInvoicePdf($id, $store_id);
            $this->getOrderPdf($id, $store_id);
            
            if (file_exists($filePath)) {
                return response()->download($filePath, $file_name);
            }
        }
        
        abort(404, 'File not found');
    }
    
    // ========== Private Helper Methods ==========
    
    private function getProductOrderList($user_id)
    {
        $orders = DB::table('product_orders')
            ->where('user_id', $user_id)
            ->orderBy('id', 'desc')
            ->get();
        
        return array_map(function($item) {
            return (array) $item;
        }, $orders->toArray());
    }
    
    private function getProductOrderDataById($id)
    {
        $order = DB::table('product_orders')->where('id', $id)->first();
        return $order ? (array) $order : [];
    }
    
    private function getProductOrderItemDataById($order_id)
    {
        $items = DB::table('product_order_items')->where('order_id', $order_id)->get();
        return array_map(function($item) {
            return (array) $item;
        }, $items->toArray());
    }
    
    private function getStateById($id)
    {
        $state = DB::table('states')->where('id', $id)->first();
        return $state ? (array) $state : [];
    }
    
    private function getCountryById($id)
    {
        $country = DB::table('countries')->where('id', $id)->first();
        return $country ? (array) $country : [];
    }
    
    private function getCityById($id)
    {
        $city = DB::table('cities')->where('id', $id)->first();
        return $city ? (array) $city : [];
    }
    
    private function salesTaxRatesProvincesById($state_id)
    {
        $tax = DB::table('sales_tax_rates_provinces')->where('state_id', $state_id)->first();
        return $tax ? (array) $tax : ['total_tax_rate' => 0];
    }
    
    private function deleteProductOrder($id)
    {
        return DB::table('product_orders')->where('id', $id)->delete();
    }
    
    private function saveProductOrder($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            DB::table('product_orders')->where('id', $id)->update($data);
            return $id;
        } else {
            unset($data['id']);
            return DB::table('product_orders')->insertGetId($data);
        }
    }
    
    private function getStoreDataById($id)
    {
        $store = DB::table('stores')->where('id', $id)->first();
        return $store ? (array) $store : [];
    }
    
    private function getorderEmail($id, $subject, $body, $store_id)
    {
        // Implement email template generation
        // This would call the email template helper
        return $body;
    }
    
    private function getOrderInvoicePdf($id, $store_id)
    {
        // Implement PDF generation for invoice
        // This would use a PDF library like TCPDF or DomPDF
    }
    
    private function getOrderPdf($id, $store_id)
    {
        // Implement PDF generation for order
        // This would use a PDF library like TCPDF or DomPDF
    }
    
    private function sendEmail($to, $subject, $body, $from_email, $from_name, $files = [])
    {
        // Implement email sending with attachments
        // This would use Laravel's Mail facade
    }
}
