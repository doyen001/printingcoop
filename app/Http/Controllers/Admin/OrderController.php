<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Shipping\FlagShipProvider;

class OrderController extends Controller
{
    /**
     * Order listing with filters (replicate CI Orders->index lines 25-52)
     */
    public function index(Request $request, $statusStr = null, $user_id = null)
    {
        // Order status mapping (lines 27-36)
        $orderStatusNames = [
            'new' => 2,
            'processing' => 3,
            'shipped' => 4,
            'delivered' => 5,
            'cancelled' => 6,
            'failed' => 7,
            'complete' => 8,
            'ready-for-pickup' => 9,
        ];
        
        $status = $statusStr == null ? null : $orderStatusNames[strtolower($statusStr)];
        
        $data = [];
        $data['page_title'] = ucfirst($statusStr) . ' Orders';
        $data['page_status'] = $statusStr;
        $data['sub_page_view_url'] = 'viewOrder';
        $data['sub_page_delete_url'] = 'deleteOrder';
        
        // Get store list (lines 44-45)
        $StoreList = $this->getAllStoreList();
        $data['StoreList'] = $StoreList;
        
        $data['user_id'] = !empty($user_id) ? $user_id : '0';
        $data['status'] = $status == null ? 'all' : $status;
        $data['statusStr'] = $statusStr == null ? 'all' : $statusStr;
        
        return view('admin.orders.index', $data);
    }
    
    /**
     * Get personalise detail (replicate CI Orders->personaliseDetail lines 54-59)
     */
    public function personaliseDetail(Request $request)
    {
        $id = $request->input('id');
        $data = $this->getPersonaliseDetail($id);
        return response()->json($data);
    }
    
    /**
     * Change order status (replicate CI Orders->changeOrderStatus lines 61-303)
     */
    public function changeOrderStatus(Request $request)
    {
        $id = $request->input('order_id');
        $status = $request->input('status');
        $emailMsg = $request->input('emailMsg');
        $json = ['status' => 0, 'msg' => ''];
        
        if (!empty($id) && !empty($status)) {
            $postData = ['id' => $id];
            
            // Don't set status for shipped (status 4) yet - will be set after FlagShip (line 69)
            if ($status != 4) {
                $postData['status'] = $status;
            }
            
            if ($this->saveProductOrder($postData)) {
                $orderData = $this->getProductOrderDataById($id);
                
                // Get address data (lines 76-79)
                $shipping_state = $this->getStateById($orderData['shipping_state']);
                $orderItemData = $this->getProductOrderItemDataById($id);
                $CountryData = $this->getCountryById($orderData['shipping_country']);
                $cityData = $this->getCityById($orderData['shipping_city']);
                
                // Get order and store info (lines 81-97)
                $toEmail = $orderData['email'];
                $name = $orderData['name'];
                $mobile = $orderData['mobile'];
                $order_id = $orderData['order_id'];
                
                $store_id = $orderData['store_id'];
                $StoreData = $this->getStoreDataById($store_id);
                
                $store_url = $StoreData['url'];
                $store_phone = $StoreData['phone'];
                $from_name = $StoreData['name'];
                $from_email = $StoreData['from_email'];
                $admin_email1 = $StoreData['admin_email1'];
                $admin_email2 = $StoreData['admin_email2'];
                $admin_email3 = $StoreData['admin_email3'];
                $langue_id = $StoreData['langue_id'];
                
                // Build email based on status (lines 99-250)
                $subject = '';
                $body = '';
                
                if ($status == 3) {
                    // Processing status (lines 99-118)
                    if ($langue_id == 2) {
                        $subject = 'Votre commande' . $order_id . ' traite';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                            <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                            ' . $emailMsg . '<br>
                            <h1>NOUS VOUS REMERCIONS DE VOTRE COMMANDE!</h1>
                           </span>
                           </div><br></br>';
                    } else {
                        $subject = 'Your Order ' . $order_id . ' is processing';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                            <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                            <br>
                            ' . $emailMsg . '<br>
                            <h1>THANK YOU FOR YOUR ORDER!</h1>
                           </span>
                           </div><br></br>';
                    }
                } else if ($status == 4) {
                    // Shipped status with FlagShip integration (lines 119-186)
                    $image = $this->getStoreEmailTemplateImage($store_id, 'shipped_order');
                    $image_template = '';
                    
                    if ($orderData['shipping_method_formate']) {
                        $shipping_method_formate = explode('-', $orderData['shipping_method_formate']);
                        
                        if ($shipping_method_formate[0] == "flagship") {
                            $tracking_number = '';
                            
                            // FlagShip API integration (lines 128-144)
                            $FlagShipConfirmData = $this->flagShipConfirm($orderData, $orderItemData, $CountryData, $shipping_state, $cityData, $StoreData);
                            
                            if ($FlagShipConfirmData['status'] == 0) {
                                $json['msg'] = $FlagShipConfirmData['msg'];
                                return response()->json($json);
                            }
                            
                            $data = $FlagShipConfirmData['data']->shipment;
                            $postData['shipment_id'] = $data->shipment_id;
                            $tracking_number = $postData['tracking_number'] = $data->tracking_number;
                            $postData['labels_regular'] = $data->labels->regular;
                            $postData['labels_thermal'] = $data->labels->thermal;
                            $postData['shipment_data'] = json_encode($data);
                        }
                    }
                    
                    // Now set status to shipped (lines 148-150)
                    $postData['id'] = $id;
                    $postData['status'] = $status;
                    $this->saveProductOrder($postData);
                    
                    if (!empty($image)) {
                        $image_url = $store_url . 'uploads/email_templates/' . $image;
                        $image_template = "<div class='top-info' style='margin-top: 25px;text-align: left;'><span style='font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;'><a href='" . $store_url . "/Logins'><img style='width:578px;' src='" . $image_url . "'></a></div>";
                    }
                    
                    if ($langue_id == 2) {
                        $subject = 'Votre commande ' . $order_id . ' a été expédié.';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                            <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                             ' . $image_template . '
                            <br>
                                ' . $emailMsg . '<br>
                                <h1>NOUS VOUS REMERCIONS DE VOTRE COMMANDE!</h1>
                            </span>
                            </div></br></br>';
                    } else {
                        $subject = 'Your Order ' . $order_id . ' has been shipped.';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                            <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                            ' . $image_template . '
                            <br>
                                ' . $emailMsg . '<br>
                                <h1>THANK YOU FOR YOUR ORDER!</h1>
                            </span>
                            </div></br></br>';
                    }
                } else if ($status == 9) {
                    // Ready for pickup (lines 187-205)
                    if ($langue_id == 2) {
                        $subject = "Votre commande $order_id a été prête pour le ramassage ";
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                    ' . $emailMsg . '<br>
                                    <h1>NOUS VOUS REMERCIONS DE VOTRE COMMANDE!</h1>
                                </span>
                            </div><br><br>';
                    } else {
                        $subject = 'Your Order ' . $order_id . ' has been Ready for pickup.';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                    ' . $emailMsg . '<br>
                                   <h1>THANK YOU FOR YOUR ORDER!</h1>
                                </span>
                            </div><br><br>';
                    }
                } else if ($status == 5) {
                    // Delivered (lines 206-223)
                    if ($langue_id == 2) {
                        $subject = "Votre commande $order_id a été livré";
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                    ' . $emailMsg . '<br>
                                    <h1>NOUS VOUS REMERCIONS DE VOTRE COMMANDE!</h1>
                                </span>
                            </div><br><br>';
                    } else {
                        $subject = 'Your Order ' . $order_id . ' has been delivered.';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                    ' . $emailMsg . '<br>
                                   <h1>THANK YOU FOR YOUR ORDER!</h1>
                                </span>
                            </div><br><br>';
                    }
                } else if ($status == 6) {
                    // Cancelled with FlagShip cancellation (lines 224-249)
                    if ($orderData['shipping_method_formate']) {
                        $shipping_method_formate = explode('-', $orderData['shipping_method_formate']);
                        if ($shipping_method_formate[0] == "flagship") {
                            $data = $this->flagShipCancel($orderData, $StoreData);
                        }
                    }
                    
                    if ($langue_id == 2) {
                        $subject = "Votre commande " . $order_id . " a été annulé.";
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                <br>
                                    ' . $emailMsg . '<br>
                                    <h1>NOUS VOUS REMERCIONS DE VOTRE COMMANDE!</h1>
                                </span>
                            </div><br><br>';
                    } else {
                        $subject = 'Your Order ' . $order_id . ' has been cancelled.';
                        $body = '<div class="top-info" style="margin-top: 15px;text-align: left;">
                                <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                                <br>
                                    ' . $emailMsg . '<br>
                                    <h1>THANK YOU FOR YOUR ORDER!</h1>
                                </span>
                            </div><br><br>';
                    }
                }
                
                // Generate PDFs (lines 252-275)
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
                
                // Get email template (line 280)
                $body = $this->getorderEmail($id, $subject, $body, $store_id);
                
                // Send emails (lines 282-291)
                $this->sendEmail($toEmail, $subject, $body, $from_email, $from_name, $files);
                if (!empty($admin_email1)) {
                    $this->sendEmail($admin_email1, $subject, $body, $from_email, $from_name, $files);
                }
                if (!empty($admin_email2)) {
                    $this->sendEmail($admin_email2, $subject, $body, $from_email, $from_name, $files);
                }
                if (!empty($admin_email3)) {
                    $this->sendEmail($admin_email3, $subject, $body, $from_email, $from_name, $files);
                }
                
                $json['status'] = 1;
                $json['msg'] = 'Order status ' . strtolower($this->getOrderStatus($status)) . ' change successfully.';
            } else {
                $json['msg'] = 'Order status ' . strtolower($this->getOrderStatus($status)) . ' change unsuccessfully.';
            }
        } else {
            $json['msg'] = 'Order status ' . strtolower($this->getOrderStatus($status)) . ' change unsuccessfully.';
        }
        
        return response()->json($json);
    }
    
    /**
     * View order detail (replicate CI Orders->viewOrder lines 568-595)
     */
    public function viewOrder($id)
    {
        $orderData = $this->getProductOrderDataById($id);
        $OrderItemData = $this->getProductOrderItemDataById($id);
        
        $stateData = $this->getStateById($orderData['billing_state']);
        $countryData = $this->getCountryById($orderData['billing_country']);
        $cityData = $this->getCityById($orderData['billing_city']);
        $salesTaxRatesProvinces_Data = $this->salesTaxRatesProvincesById($orderData['billing_state']);
        
        $StoreList = $this->getAllStoreList();
        
        $store_id = $orderData['store_id'];
        $StoreData = $this->getStoreDataById($store_id);
        $langue_id = $StoreData['langue_id'];
        
        $data = [];
        $data['page_title'] = 'Order details';
        $data['orderData'] = $orderData;
        $data['OrderItemData'] = $OrderItemData;
        $data['cityData'] = $cityData;
        $data['stateData'] = $stateData;
        $data['countryData'] = $countryData;
        $data['salesTaxRatesProvinces_Data'] = $salesTaxRatesProvinces_Data;
        $data['langue_id'] = $langue_id;
        $data['StoreList'] = $StoreList;
        
        return view('admin.orders.view', $data);
    }
    
    // ========== Private Helper Methods ==========
    
    private function getAllStoreList()
    {
        return DB::table('stores')->get()->toArray();
    }
    
    private function getPersonaliseDetail($id)
    {
        $item = DB::table('product_order_items')->where('id', $id)->first();
        return $item ? (array) $item : [];
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
    
    private function getStoreDataById($id)
    {
        $store = DB::table('stores')->where('id', $id)->first();
        return $store ? (array) $store : [];
    }
    
    private function salesTaxRatesProvincesById($state_id)
    {
        $tax = DB::table('sales_tax_rates_provinces')->where('state_id', $state_id)->first();
        return $tax ? (array) $tax : ['total_tax_rate' => 0];
    }
    
    private function getStoreEmailTemplateImage($store_id, $template_name)
    {
        $template = DB::table('email_templates')
            ->where('store_id', $store_id)
            ->where('template_name', $template_name)
            ->first();
        return $template ? $template->image : null;
    }
    
    private function flagShipConfirm($orderData, $orderItemData, $CountryData, $shipping_state, $cityData, $StoreData)
    {
        // Implement FlagShip confirmation
        // This would call the FlagShip API helper function
        return ['status' => 0, 'msg' => 'FlagShip integration not implemented'];
    }
    
    private function flagShipCancel($orderData, $StoreData)
    {
        // Implement FlagShip cancellation
        // This would call the FlagShip API helper function
        return ['status' => 0, 'msg' => 'FlagShip cancellation not implemented'];
    }
    
    private function getorderEmail($id, $subject, $body, $store_id)
    {
        // Implement email template generation
        return $body;
    }
    
    private function getOrderInvoicePdf($id, $store_id)
    {
        // Implement PDF generation for invoice
    }
    
    private function getOrderPdf($id, $store_id)
    {
        // Implement PDF generation for order
    }
    
    private function sendEmail($to, $subject, $body, $from_email, $from_name, $files = [])
    {
        // Implement email sending with attachments
    }
    
    private function getOrderStatus($status)
    {
        $statuses = [
            2 => 'New',
            3 => 'Processing',
            4 => 'Shipped',
            5 => 'Delivered',
            6 => 'Cancelled',
            7 => 'Failed',
            8 => 'Complete',
            9 => 'Ready for Pickup',
        ];
        return $statuses[$status] ?? 'Unknown';
    }
}
