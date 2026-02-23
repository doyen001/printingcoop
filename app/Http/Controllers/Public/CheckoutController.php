<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Cart\CartService;
use App\Services\Shipping\UpsProvider;
use App\Services\Shipping\CanadaPostProvider;
use App\Services\Shipping\FlagShipProvider;

class CheckoutController extends Controller
{
    /**
     * Checkout index - Steps 1-2 (replicate CI Checkouts->index lines 20-487)
     * 
     * Step 1: Login check (lines 34-36)
     * Step 2: Address selection (lines 125-487)
     */
    public function index(Request $request, $stap = 1, $order_id = 0, $product_id = 0, $coupon_code = null)
    {
        $data = [];
        
        // Get store and user data
        $loginId = session('loginId');
        $loginName = session('loginName');
        $language_name = config('store.language_name', 'English');
        $main_store_data = config('store.main_store_data', []);
        
        $data['page_title'] = 'Checkout';
        
        // Step 1: Login check (lines 34-36)
        if (empty($loginId)) {
            return redirect('/Logins');
        }
        
        // French translation (lines 37-39)
        if ($language_name == 'French') {
            $data['page_title'] = 'Check-out';
        }
        
        // Decode step parameter (lines 40-44)
        if ($stap == 1) {
            $stap = base64_encode($stap);
        }
        $stap = base64_decode($stap);
        
        // Decode order_id (lines 46-48)
        if ($order_id != '0') {
            $order_id = base64_decode($order_id);
        }
        
        // Auto-advance from step 1 to step 2 if logged in (lines 50-52)
        if ($stap == 1 && !empty($loginId)) {
            $stap = 2;
        }
        
        // Validate step (lines 54-56)
        if (!in_array($stap, ['1', '2', '3', '4'])) {
            return redirect('/');
        }
        
        // Handle coupon code application (lines 58-123)
        if ($request->has('coupon_code') && $request->get('coupon_code') != '' && $request->has('apply_code') && $request->get('apply_code') != '') {
            $coupon_code = $request->get('coupon_code');
            $couponData = $this->getDiscountDataByCode($coupon_code);
            
            if (!empty($couponData)) {
                $discount = $couponData['discount'];
                $discount_type = $couponData['discount_type'];
                $discount_valid_from = $couponData['discount_valid_from'];
                $discount_valid_to = $couponData['discount_valid_to'];
                $cdate = date('Y-m-d H:i:s');
                
                // Validate coupon dates (lines 68-117)
                if (strtotime($discount_valid_from) > strtotime($cdate)) {
                    session()->flash('code_error', 'This coupon code that time not apply');
                    $coupon_code = '';
                } else if (strtotime($discount_valid_to) < strtotime($cdate)) {
                    session()->flash('code_error', 'coupon code expired');
                    $coupon_code = '';
                } else {
                    $coupon_discount_amount = '0';
                    if (!empty($coupon_code) && !empty($order_id)) {
                        $ProductOrder = $this->getProductOrderDataById($order_id);
                        
                        if (strtotime($discount_valid_from) <= strtotime($cdate) && strtotime($discount_valid_to) >= strtotime($cdate)) {
                            if ($discount_type == 'discount_percent') {
                                $coupon_discount_amount = ($ProductOrder['sub_total_amount'] * $discount) / 100;
                            } else {
                                $coupon_discount_amount = $discount;
                            }
                        } else {
                            $coupon_code = '';
                        }
                        
                        // Apply coupon to order (lines 98-115)
                        if ($coupon_code == $ProductOrder['coupon_code']) {
                            session()->flash('code_success', 'coupon code already applied');
                        } else if (!empty($ProductOrder['coupon_code']) && $coupon_code != $ProductOrder['coupon_code']) {
                            DB::table('product_orders')->where('id', $order_id)->update([
                                'coupon_discount_amount' => $coupon_discount_amount,
                                'coupon_code' => $coupon_code,
                                'total_amount' => ($ProductOrder['total_amount'] + $ProductOrder['coupon_discount_amount']) - $coupon_discount_amount,
                            ]);
                            session()->flash('code_success', 'coupon code applied successfully');
                        } else {
                            DB::table('product_orders')->where('id', $order_id)->update([
                                'coupon_discount_amount' => $coupon_discount_amount,
                                'coupon_code' => $coupon_code,
                                'total_amount' => $ProductOrder['total_amount'] - $coupon_discount_amount,
                            ]);
                            session()->flash('code_success', 'coupon code applied successfully');
                        }
                    }
                }
            } else {
                session()->flash('code_error', 'invalid coupon code');
            }
            
            return redirect('Checkouts/index/' . base64_encode($stap) . '/' . base64_encode($order_id) . "/" . base64_encode($product_id) . "/" . $coupon_code);
        }
        
        // Load user addresses (lines 125-131)
        $address = $this->getAddressListByUserId($loginId);
        $data['address'] = $address;
        $data['states'] = [];
        $data['citys'] = [];
        $data['countries'] = $this->getCountries();
        
        // Initialize order data (lines 133-139)
        $ProductOrder = [];
        $ProductOrderItem = [];
        $userData = [];
        $total_charges_ups = [];
        $CanedaPostShiping = [];
        $FlagShiping = [];
        $salesTaxRatesProvinces_Data = [];
        $our_company_shiping_cost = 0;
        
        // Get user data (lines 140-142)
        if (!empty($loginId)) {
            $userData = $this->getUserDataById($loginId);
        }
        
        // Load existing order or create from cart (lines 144-325)
        if (!empty($order_id)) {
            $ProductOrder = $this->getProductOrderDataById($order_id);
            $ProductOrderItem = $this->getProductOrderItemDataById($order_id);
            
            if (empty($ProductOrder)) {
                return redirect('/');
            }
            
            // Get shipping data for calculations (lines 152-225)
            $stateData = $this->getStateById($ProductOrder['shipping_state']);
            $CountryData = $this->getCountryById($ProductOrder['shipping_country']);
            $cityData = $this->getCityById($ProductOrder['shipping_city']);
            
            // Calculate shipping costs via UPS API (lines 161-201)
            $shipping_pin_code = strtoupper(str_replace(" ", "", $ProductOrder['shipping_pin_code']));
            
            $upsProvider = new UpsProvider();
            $upsProvider->addField('ShipTo_Name', $ProductOrder['shipping_name']);
            $upsProvider->addField('ShipTo_AddressLine', [
                $ProductOrder['shipping_address'], $ProductOrder['shipping_address'],
            ]);
            $upsProvider->addField('ShipTo_City', $cityData['name']);
            $upsProvider->addField('ShipTo_StateProvinceCode', $stateData['iso2']);
            $upsProvider->addField('ShipTo_PostalCode', $shipping_pin_code);
            $upsProvider->addField('ShipTo_CountryCode', $CountryData['iso2']);
            
            // Package dimensions and weight (lines 187-193)
            $index = 0;
            $dimensions[$index]['Weight'] = 1; // Kg
            $dimensions[$index]['Qty'] = $ProductOrder['total_items'];
            $upsProvider->addField('dimensions', $dimensions);
            
            list($response, $status) = $upsProvider->processRate();
            $ups_response = json_decode($response);
            
            if ($status == 200) {
                $total_charges_ups = $ups_response->RateResponse->RatedShipment;
            }
            
            // Calculate shipping via Canada Post or Sina (lines 203-212)
            $provider_product_count = $this->getOrderProductCount($order_id);
            if ($provider_product_count > 0) {
                $methods = $this->sinaShippingMethods($order_id);
                $CanedaPostShiping = ['statu' => 200, 'msg' => ''];
                foreach ($methods as $method) {
                    $CanedaPostShiping['list'] = [['service_name' => $method[1], 'price' => $method[2]]];
                }
            } else {
                $canadaPostProvider = new CanadaPostProvider();
                $CanedaPostShiping = $canadaPostProvider->getRates($shipping_pin_code);
            }
            $ProductOrder['provider_product_count'] = $provider_product_count;
            
            // Get sales tax rates (line 215)
            $salesTaxRatesProvinces_Data = $this->salesTaxRatesProvincesById($ProductOrder['billing_state']);
            
            // Get store data and calculate FlagShip shipping (lines 218-225)
            $storeData = $this->getStoreDataById($ProductOrder['store_id']);
            $our_company_shiping_cost = $this->calculateShippingCost($ProductOrder['total_amount']);
            
            $flag_ship = $storeData['flag_ship'] ?? 'no';
            if ($flag_ship == 'yes') {
                $flagShipProvider = new FlagShipProvider();
                $FlagShiping = $flagShipProvider->getRates($ProductOrder, $ProductOrderItem, $CountryData, $stateData, $cityData, $storeData);
            }
        } else {
            // Create order from cart (lines 226-325)
            $cart = new CartService();
            
            $ProductOrder['sub_total_amount'] = $cart->total();
            $ProductOrder['total_amount'] = $cart->total();
            $ProductOrder['preffered_customer_discount'] = 0;
            $ProductOrder['currency_id'] = 1;
            $ProductOrder['store_id'] = $main_store_data['id'] ?? 1;
            $ProductOrder['payment_mode'] = $main_store_data['paypal_payment_mode'] ?? 'sandbox';
            
            // Apply preferred customer discount (lines 236-244)
            if (!empty($userData)) {
                $user_type = $userData['user_type'] ?? 0;
                $preferred_status = $userData['preferred_status'] ?? 0;
                
                if ($user_type == 2 && $preferred_status == 1) {
                    $pramount = (($ProductOrder['sub_total_amount'] * 10) / 100);
                    $ProductOrder['preffered_customer_discount'] = $pramount;
                    $ProductOrder['total_amount'] = $ProductOrder['total_amount'] - $pramount;
                }
            }
            
            $ProductOrder['total_items'] = $cart->total_items();
            $items = $cart->contents();
            
            // Build order items from cart (lines 249-291)
            foreach ($items as $key => $item) {
                if (is_object($item['options']['attribute_ids'] ?? null)) {
                    unset($item['options']['attribute_ids']->options);
                }
                
                $ProductData = $this->getProductDataById($item['id']);
                
                $ProductOrderItem[$key] = [
                    'id' => '',
                    'order_id' => '',
                    'product_id' => $ProductData['id'],
                    'name' => $ProductData['name'],
                    'name_french' => $ProductData['name_french'],
                    'price' => $item['price'],
                    'short_description' => $ProductData['short_description'],
                    'full_description' => $ProductData['full_description'],
                    'discount' => $ProductData['discount'],
                    'product_image' => $ProductData['product_image'],
                    'cart_images' => json_encode($item['options']['cart_images'] ?? []),
                    'provider_product_id' => $item['options']['provider_product_id'] ?? null,
                    'attribute_ids' => json_encode($item['options']['attribute_ids'] ?? []),
                    'product_size' => json_encode($item['options']['product_size'] ?? []),
                    'product_width_length' => json_encode($item['options']['product_width_length'] ?? []),
                    'page_product_width_length' => json_encode($item['options']['page_product_width_length'] ?? []),
                    'product_depth_length_width' => json_encode($item['options']['product_depth_length_width'] ?? []),
                    'votre_text' => $item['options']['votre_text'] ?? '',
                    'recto_verso' => $item['options']['recto_verso'] ?? '',
                    'code' => $ProductData['code'],
                    'brand' => $ProductData['brand'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                    'delivery_charge' => $ProductData['delivery_charge'],
                    'total_stock' => $ProductData['total_stock'],
                    'shipping_box_length' => $ProductData['shipping_box_length'],
                    'shipping_box_width' => $ProductData['shipping_box_width'],
                    'shipping_box_height' => $ProductData['shipping_box_height'],
                    'shipping_box_weight' => $ProductData['shipping_box_weight'],
                ];
            }
            
            // Apply coupon discount (lines 293-320)
            $coupon_discount_amount = '0';
            if (!empty($coupon_code)) {
                $couponData = $this->getDiscountDataByCode($coupon_code);
                
                if (!empty($couponData)) {
                    $discount = $couponData['discount'];
                    $discount_type = $couponData['discount_type'];
                    $discount_valid_from = $couponData['discount_valid_from'];
                    $discount_valid_to = $couponData['discount_valid_to'];
                    $cdate = date('Y-m-d H:i:s');
                    
                    if (strtotime($discount_valid_from) <= strtotime($cdate) && strtotime($discount_valid_to) >= strtotime($cdate)) {
                        if ($discount_type == 'discount_percent') {
                            $coupon_discount_amount = ($ProductOrder['sub_total_amount'] * $discount) / 100;
                        } else {
                            $coupon_discount_amount = $discount;
                        }
                    } else {
                        $coupon_code = '';
                    }
                } else {
                    $coupon_code = '';
                }
            }
            
            $ProductOrder['coupon_discount_amount'] = $coupon_discount_amount;
            $ProductOrder['coupon_code'] = $coupon_code;
            $ProductOrder['total_amount'] = $ProductOrder['total_amount'] - $coupon_discount_amount;
            $ProductOrder['total_sales_tax'] = '';
        }
        
        // Redirect if cart is empty and no order (lines 328-330)
        $cart = new CartService();
        if (empty($cart->total_items()) && empty($order_id) && empty($product_id)) {
            return redirect('/');
        }
        
        // Handle form submission (Step 2: Address selection) (lines 332-468)
        if ($request->isMethod('post')) {
            $userData = $this->getUserDataById($loginId);
            $PostData = [];
            
            $PostData['delivery_address_id'] = $request->input('delivery_address_id') ?? $this->getProductOrderDataById($order_id)['delivery_address_id'];
            
            $address = $this->getAddressDataById($PostData['delivery_address_id']);
            
            if (!empty($order_id)) {
                $PostData['id'] = $order_id;
            }
            
            // Set user data (lines 341-344)
            $PostData['user_id'] = $loginId;
            $PostData['name'] = $loginName;
            $PostData['email'] = $userData['email'];
            $PostData['mobile'] = $userData['mobile'];
            
            // Set billing address (lines 346-357)
            $PostData['billing_name'] = $address['name'];
            $PostData['billing_company'] = $address['company_name'];
            $PostData['billing_pin_code'] = $address['pin_code'];
            $PostData['billing_mobile'] = $address['mobile'];
            $PostData['billing_address'] = $address['address'];
            $PostData['billing_city'] = $address['city'];
            $PostData['billing_state'] = $address['state'];
            $PostData['billing_country'] = $address['country'];
            $PostData['billing_landmark'] = $address['landmark'];
            $PostData['billing_alternate_phone'] = $address['alternate_phone'];
            $PostData['billing_address_type'] = $address['address_type'];
            
            // Set shipping address (lines 359-369)
            $PostData['shipping_name'] = $address['name'];
            $PostData['shipping_company'] = $address['company_name'];
            $PostData['shipping_pin_code'] = $address['pin_code'];
            $PostData['shipping_mobile'] = $address['mobile'];
            $PostData['shipping_address'] = $address['address'];
            $PostData['shipping_city'] = $address['city'];
            $PostData['shipping_state'] = $address['state'];
            $PostData['shipping_country'] = $address['country'];
            $PostData['shipping_landmark'] = $address['landmark'];
            $PostData['shipping_alternate_phone'] = $address['alternate_phone'];
            $PostData['shipping_address_type'] = $address['address_type'];
            
            // Calculate sales tax (lines 371-378)
            $salesTaxRatesProvinces_Data = $this->salesTaxRatesProvincesById($PostData['billing_state']);
            $total_tax_rate = $salesTaxRatesProvinces_Data['total_tax_rate'] ?? 0;
            $total_sales_tax = (($ProductOrder['sub_total_amount'] * $total_tax_rate) / 100);
            
            $PostData['total_amount'] = $ProductOrder['total_amount'] + $total_sales_tax;
            $PostData['total_sales_tax'] = $total_sales_tax;
            
            // Set order totals (lines 380-387)
            $PostData['total_items'] = $ProductOrder['total_items'];
            $PostData['preffered_customer_discount'] = $ProductOrder['preffered_customer_discount'];
            $PostData['sub_total_amount'] = $ProductOrder['sub_total_amount'];
            $PostData['currency_id'] = $ProductOrder['currency_id'];
            $PostData['store_id'] = $ProductOrder['store_id'];
            $PostData['payment_mode'] = $ProductOrder['payment_mode'];
            $PostData['coupon_code'] = $ProductOrder['coupon_code'];
            $PostData['coupon_discount_amount'] = $ProductOrder['coupon_discount_amount'];
            
            // Save order (line 391)
            $insert_id = $this->saveProductOrder($PostData);
            
            if ($insert_id > 0) {
                // Update order ID with prefix (lines 394-396)
                $PostDataNew = [];
                $PostDataNew['id'] = $insert_id;
                $PostDataNew['order_id'] = ($main_store_data['order_id_prefix'] ?? '') . $insert_id;
                
                // Handle shipping method (lines 398-416)
                $shipping_method = $request->input('shipping_method_formate') ?? '';
                if (!empty($shipping_method)) {
                    $shipping_method_old = $this->getProductOrderDataById($order_id)['shipping_method_formate'] ?? '';
                    
                    if (!empty($shipping_method_old)) {
                        $delivery_charge_old = explode('-', $shipping_method_old);
                        $ProductOrder['total_amount'] = $ProductOrder['total_amount'] - $delivery_charge_old[1];
                    }
                    
                    $PostDataNew['shipping_method_formate'] = $shipping_method;
                    $delivery_charge = explode('-', $shipping_method);
                    $PostDataNew['delivery_charge'] = $delivery_charge[1];
                    $PostDataNew['total_amount'] = $ProductOrder['total_amount'] + $delivery_charge[1];
                    
                    if ($delivery_charge[0] == 'flagship') {
                        $PostDataNew['flag_shiping_cost'] = !empty($delivery_charge[3]) ? $delivery_charge[3] : 0;
                    } else {
                        $PostDataNew['flag_shiping_cost'] = 0;
                    }
                }
                
                $this->saveProductOrder($PostDataNew);
                
                // Save order items (lines 420-461)
                foreach ($ProductOrderItem as $ProductData) {
                    $ProductOrderItemSaveData = [
                        'id' => $ProductData['id'],
                        'product_id' => $ProductData['product_id'],
                        'order_id' => $insert_id,
                        'name' => $ProductData['name'],
                        'name_french' => $ProductData['name_french'],
                        'price' => $ProductData['price'],
                        'short_description' => $ProductData['short_description'],
                        'short_description_french' => $ProductData['short_description_french'] ?? '',
                        'full_description' => $ProductData['full_description'],
                        'full_description_french' => $ProductData['full_description_french'] ?? '',
                        'discount' => $ProductData['discount'],
                        'product_image' => $ProductData['product_image'],
                        'code' => $ProductData['code'],
                        'brand' => $ProductData['brand'],
                        'quantity' => $ProductData['quantity'],
                        'subtotal' => $ProductData['subtotal'],
                        'delivery_charge' => $ProductData['delivery_charge'],
                        'total_stock' => $ProductData['total_stock'],
                        'cart_images' => $ProductData['cart_images'],
                        'attribute_ids' => $ProductData['attribute_ids'],
                        'product_size' => $ProductData['product_size'],
                        'product_width_length' => $ProductData['product_width_length'],
                        'page_product_width_length' => $ProductData['page_product_width_length'],
                        'product_depth_length_width' => $ProductData['product_depth_length_width'],
                        'votre_text' => $ProductData['votre_text'],
                        'recto_verso' => $ProductData['recto_verso'],
                        'shipping_box_length' => $ProductData['shipping_box_length'],
                        'shipping_box_width' => $ProductData['shipping_box_width'],
                        'shipping_box_height' => $ProductData['shipping_box_height'],
                        'shipping_box_weight' => $ProductData['shipping_box_weight'],
                    ];
                    
                    $this->saveProductOrderItem($ProductOrderItemSaveData);
                }
                
                // Advance to next step (lines 463-464)
                $stap = $stap + 1;
                return redirect('Checkouts/index/' . base64_encode($stap) . '/' . base64_encode($insert_id) . "/" . base64_encode($product_id) . "/" . $coupon_code);
            } else {
                session()->flash('message_error', 'oder save  Unsuccessfully.');
            }
        }
        
        // Get pickup stores (line 470)
        $PickupStoresList = $this->getPickupStoresList();
        
        // Set view data (lines 472-486)
        $data['order_id'] = base64_encode($order_id);
        $data['stap'] = base64_encode($stap);
        $data['product_id'] = base64_encode($product_id);
        $data['ProductOrder'] = $ProductOrder;
        $data['ProductOrderItem'] = $ProductOrderItem;
        $data['total_charges_ups'] = $total_charges_ups;
        $data['CanedaPostShiping'] = $CanedaPostShiping;
        $data['FlagShiping'] = $FlagShiping;
        $data['PickupStoresList'] = $PickupStoresList;
        $data['salesTaxRatesProvinces_Data'] = $salesTaxRatesProvinces_Data;
        $data['our_company_shiping_cost'] = $our_company_shiping_cost;
        $data['coupon_code'] = $coupon_code;
        
        return view('public.checkout.index', $data);
    }
    
    // ========== Private Helper Methods ==========
    
    private function getDiscountDataByCode($code)
    {
        $discount = DB::table('discounts')->where('code', $code)->where('status', 1)->first();
        return $discount ? (array) $discount : [];
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
    
    private function getAddressListByUserId($user_id)
    {
        $addresses = DB::table('addresses')->where('user_id', $user_id)->get();
        return array_map(function($item) {
            return (array) $item;
        }, $addresses->toArray());
    }
    
    private function getCountries()
    {
        return DB::table('countries')->get()->toArray();
    }
    
    private function getUserDataById($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        return $user ? (array) $user : [];
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
    
    private function calculateShippingCost($total_amount)
    {
        // Implement shipping cost calculation logic
        return 0;
    }
    
    private function getOrderProductCount($order_id)
    {
        return DB::table('product_order_items')
            ->join('provider_products', 'product_order_items.product_id', '=', 'provider_products.product_id')
            ->where('product_order_items.order_id', $order_id)
            ->count();
    }
    
    private function sinaShippingMethods($order_id)
    {
        // Implement Sina shipping methods
        // This would call the Sina API to get shipping methods
        return [];
    }
    
    private function getStoreDataById($id)
    {
        $store = DB::table('stores')->where('id', $id)->first();
        return $store ? (array) $store : [];
    }
    
    private function getProductDataById($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        return $product ? (array) $product : [];
    }
    
    private function getAddressDataById($id)
    {
        $address = DB::table('addresses')->where('id', $id)->first();
        return $address ? (array) $address : [];
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
    
    private function saveProductOrderItem($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            DB::table('product_order_items')->where('id', $id)->update($data);
            return $id;
        } else {
            unset($data['id']);
            return DB::table('product_order_items')->insertGetId($data);
        }
    }
    
    private function getPickupStoresList()
    {
        return DB::table('stores')->where('pickup_available', 1)->get()->toArray();
    }
}
