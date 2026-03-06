<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use App\Services\CartService;
use App\Services\Shipping\UpsProvider;
use App\Services\Shipping\CanadaPostProvider;
use App\Services\Shipping\FlagShipProvider;

/**
 * CheckoutsController
 * Complete checkout process implementation
 * CI: application/controllers/Checkouts.php
 */
class CheckoutsController extends Controller
{
    protected $cart;
    
    public function __construct()
    {
        $this->cart = new CartService();
    }
    
    /**
     * Checkout index - Multi-step checkout process
     * CI: Checkouts->index() lines 20-520
     */
    public function index(Request $request, $step = 1, $order_id = 0)
    {
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        // Redirect to login if not authenticated
        if (empty($loginId)) {
            return redirect('/Logins')->with('message_error', 'Please login to continue checkout');
        }
        
        // Decode step and order_id if encoded
        if ($step == 1) {
            $step = base64_encode($step);
        }
        $step = base64_decode($step);
        
        if ($order_id != '0') {
            $order_id = base64_decode($order_id);
        }
        // Skip step 1 if user is logged in
        if ($step == 1 && !empty($loginId)) {
            $step = 2;
        }
        
        // Validate step
        if (!in_array($step, ['1', '2', '3', '4'])) {
            return redirect('/');
        }
        
        // Check if cart is empty
        if (empty($this->cart->totalItems()) && empty($order_id)) {
            return redirect('/')->with('message_error', 'Your cart is empty');
        }
        
        // Get user data
        $userData = DB::table('users')->where('id', $loginId)->first();
        
        // Get addresses (cast to array to match legacy CI view expectations)
        $addresses = DB::table('addresses')
            ->where('user_id', $loginId)
            ->get()
            ->map(function ($row) {
                return (array) $row;
            })
            ->toArray();
        
        // Get countries for address form (cast to array)
        $countries = DB::table('countries')
            ->where('flag', 1)
            ->get()
            ->map(function ($row) {
                return (array) $row;
            })
            ->toArray();
        
        // Shipping / tax related data (kept close to CI implementation)
        $total_charges_ups = [];
        $CanedaPostShiping = ['list' => []];
        $FlagShiping = [];
        $PickupStoresList = $this->getPickupStoresList();
        $salesTaxRatesProvinces_Data = [];
        $our_company_shiping_cost = 0;
        $coupon_code = null;
        
        // Initialize order data
        $ProductOrder = [];
        $ProductOrderItems = [];
        if (!empty($order_id)) {
            // Load existing order (CI: ProductOrder_Model->getProductOrderDataById / getProductOrderItemDataById)
            $ProductOrderRow = DB::table('product_orders')->where('id', $order_id)->first();
            if (empty($ProductOrderRow)) {
                return redirect('/');
            }
            $ProductOrder = (array) $ProductOrderRow;
            
            // Cast order items to array to support $item['field'] access in Blade
            $ProductOrderItems = DB::table('product_order_items')
                ->where('order_id', $order_id)
                ->get()
                ->map(function ($item) {
                    return (array) $item;
                })
                ->toArray();
            
            // === Shipping / tax calculation for existing order (mirrors CI + Public\CheckoutController) ===
            if (!empty($ProductOrder['shipping_state'] ?? null) &&
                !empty($ProductOrder['shipping_country'] ?? null) &&
                !empty($ProductOrder['shipping_city'] ?? null) &&
                !empty($ProductOrder['shipping_pin_code'] ?? null)
            ) {
                $stateData = $this->getStateById($ProductOrder['shipping_state']);
                $CountryData = $this->getCountryById($ProductOrder['shipping_country']);
                $cityData = $this->getCityById($ProductOrder['shipping_city']);
                
                if (!empty($stateData) && !empty($CountryData) && !empty($cityData)) {
                    $shipping_pin_code = strtoupper(str_replace(' ', '', $ProductOrder['shipping_pin_code']));
                    
                    $upsProvider = new UpsProvider();
                    $upsProvider->addField('ShipTo_Name', $ProductOrder['shipping_name'] ?? '');
                    $upsProvider->addField('ShipTo_AddressLine', [
                        $ProductOrder['shipping_address'] ?? '',
                        $ProductOrder['shipping_address'] ?? '',
                    ]);
                    $upsProvider->addField('ShipTo_City', $cityData['name']);
                    $upsProvider->addField('ShipTo_StateProvinceCode', $stateData['iso2']);
                    $upsProvider->addField('ShipTo_PostalCode', $shipping_pin_code);
                    $upsProvider->addField('ShipTo_CountryCode', $CountryData['iso2']);
                    
                    $dimensions = [];
                    $dimensions[0]['Weight'] = 1; // Kg
                    $dimensions[0]['Qty'] = $ProductOrder['total_items'] ?? count($ProductOrderItems);
                    $upsProvider->addField('dimensions', $dimensions);
                    
                    [$response, $status] = $upsProvider->processRate();
                    $ups_response = json_decode($response);
                    
                    if ($status == 200 && isset($ups_response->RateResponse->RatedShipment)) {
                        $total_charges_ups = $ups_response->RateResponse->RatedShipment;
                    }
                    
                    // Canada Post / Sina provider shipping (provider_product_count)
                    $provider_product_count = $this->getOrderProductCount($order_id);
                    if ($provider_product_count > 0) {
                        $methods = $this->sinaShippingMethods($order_id);
                        $CanedaPostShiping = ['statu' => 200, 'msg' => ''];
                        foreach ($methods as $method) {
                            $CanedaPostShiping['list'] = [[
                                'service_name' => $method[1],
                                'price' => $method[2],
                            ]];
                        }
                    } else {
                        $canadaPostProvider = new CanadaPostProvider();
                        $CanedaPostShiping = $canadaPostProvider->getRates($shipping_pin_code);
                    }
                    $ProductOrder['provider_product_count'] = $provider_product_count;
                    
                    // Tax rates for billing state
                    $salesTaxRatesProvinces_Data = $this->salesTaxRatesProvincesById($ProductOrder['billing_state'] ?? null);
                    
                    // Store / FlagShip shipping
                    $storeData = $this->getStoreDataById($ProductOrder['store_id'] ?? null);
                    $our_company_shiping_cost = $this->calculateShippingCost($ProductOrder['total_amount'] ?? 0);
                    
                    $flag_ship = $storeData['flag_ship'] ?? 'no';
                    if ($flag_ship === 'yes') {
                        $flagShipProvider = new FlagShipProvider();
                        $FlagShiping = $flagShipProvider->getRates($ProductOrder, $ProductOrderItems, $CountryData, $stateData, $cityData, $storeData);
                    }
                }
            }
        } else {
            // Create new order from cart (matches CI logic - no order_id set yet)
            $ProductOrder['sub_total_amount'] = $this->cart->total();
            $ProductOrder['total_amount'] = $this->cart->total();
            $ProductOrder['preffered_customer_discount'] = 0;
            $ProductOrder['total_items'] = $this->cart->totalItems();
            $ProductOrder['coupon_code'] = '';
            $ProductOrder['coupon_discount_amount'] = 0;
            $ProductOrder['total_sales_tax'] = 0;
            
            // Check for preferred customer discount
            if (!empty($userData)) {
                if ($userData->user_type == 2 && $userData->preferred_status == 1) {
                    $discount = ($ProductOrder['sub_total_amount'] * 10) / 100;
                    $ProductOrder['preffered_customer_discount'] = $discount;
                    $ProductOrder['total_amount'] = $ProductOrder['total_amount'] - $discount;
                }
            }
            
            // Get main store data (matches CI main_store_data)
            $main_store_data = DB::table('stores')->where('main_store', 1)->first();
            if (!$main_store_data) {
                $main_store_data = DB::table('stores')->first();
            }
            
            // Set store-related fields (matches CI)
            $ProductOrder['currency_id'] = $main_store_data->default_currency_id ?? 1;
            $ProductOrder['store_id'] = $main_store_data->id;
            $ProductOrder['payment_mode'] = $main_store_data->paypal_payment_mode ?? 'paypal';
            
            // Note: order_id is NOT set here - it's generated when order is saved (matches CI logic)
            // The order_id will be created in saveAddress() method using: prefix + insert_id items from cart
            $items = $this->cart->contents();
            foreach ($items as $key => $item) {
                $productData = DB::table('products')->where('id', $item['id'])->first();
                
                $ProductOrderItems[$key] = [
                    'product_id' => $item['id'],
                    'name' => $productData->name ?? '',
                    'name_french' => $productData->name_french ?? '',
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                    'product_image' => $productData->product_image ?? '',
                    'cart_images' => json_encode($item['options']['cart_images'] ?? []),
                    'attribute_ids' => json_encode($item['options']['attribute_ids'] ?? []),
                    'product_size' => json_encode($item['options']['product_size'] ?? []),
                    'product_width_length' => json_encode($item['options']['product_width_length'] ?? []),
                    'page_product_width_length' => json_encode($item['options']['page_product_width_length'] ?? []),
                    'product_depth_length_width' => json_encode($item['options']['product_depth_length_width'] ?? []),
                    'votre_text' => $item['options']['votre_text'] ?? '',
                    'recto_verso' => $item['options']['recto_verso'] ?? '',
                ];
            }
        }
        
        // Prepare view data (align with legacy CI view variables)
        $data = [
            'page_title' => $language_name == 'french' ? 'Check-out' : 'Checkout',
            'language_name' => $language_name,
            'step' => $step,
            'order_id' => $order_id,
            // Legacy view expects $address (singular) not $addresses
            'address' => $addresses,
            'addresses' => $addresses,
            'countries' => $countries,
            // These are used by the "new address" form; keep defaults matching CI structure
            'states' => [],
            'citys' => [],
            'postData' => [],
            'ProductOrder' => $ProductOrder,
            // Legacy view variable name (singular)
            'ProductOrderItem' => $ProductOrderItems,
            'ProductOrderItems' => $ProductOrderItems,
            'userData' => $userData,
            'loginName' => session('loginName', ''),
            // Shipping-related variables expected by the legacy checkout view.
            'total_charges_ups' => $total_charges_ups,
            'CanedaPostShiping' => $CanedaPostShiping,
            'FlagShiping' => $FlagShiping,
            'PickupStoresList' => $PickupStoresList,
            'salesTaxRatesProvinces_Data' => $salesTaxRatesProvinces_Data,
            'our_company_shiping_cost' => $our_company_shiping_cost,
            'coupon_code' => $coupon_code,
        ];
        
        return view('checkouts.index', $data);
    }
    
    /**
     * Save address and create/update order
     * CI: Checkouts->index() POST handling lines 340-476
     */
    /**
     * Save address and order (matches CI checkout step 2)
     */
    public function saveAddress(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        if (empty($loginId)) {
            return redirect('/Logins');
        }
        
        $validator = Validator::make($request->all(), [
            'delivery_address_id' => 'required|exists:addresses,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $userData = DB::table('users')->where('id', $loginId)->first();
        $address = DB::table('addresses')->where('id', $request->delivery_address_id)->first();
        
        if (!$address) {
            return redirect()->back()->with('message_error', 'Invalid address selected');
        }
        
        $order_id = $request->order_id ?? 0;
        
        // Get cart data (matches CI cart logic)
        $cart = new CartService();
        $sub_total = $cart->total();
        $total_items = $cart->totalItems();
        
        // Get main store data (matches CI main_store_data)
        $main_store_data_obj = DB::table('stores')->where('main_store', 1)->first();
        if (!$main_store_data_obj) {
            $main_store_data_obj = DB::table('stores')->first();
        }
        $main_store_data = (array) $main_store_data_obj;
        
        // Calculate preferred customer discount (matches CI logic)
        $preffered_discount = 0;
        if ($userData->user_type == 2 && $userData->preferred_status == 1) {
            $preffered_discount = ($sub_total * 10) / 100;
        }
        
        // Get tax rate based on billing state (matches CI salesTaxRatesProvincesById)
        $tax_rate = 0;
        // Prefer new normalized table if it exists
        if (Schema::hasTable('sales_tax_rates_provinces')) {
            $taxData = DB::table('sales_tax_rates_provinces')
                ->where('state_id', $address->state)
                ->first();
            $tax_rate = $taxData->total_tax_rate ?? 0;
        }
        // Fallback to legacy CI table name if present
        elseif (Schema::hasTable('sales-tax-rates-provinces')) {
            $taxData = DB::table('sales-tax-rates-provinces')
                ->where('state_id', $address->state)
                ->first();
            $tax_rate = $taxData->total_tax_rate ?? 0;
        }
        
        $total_sales_tax = ($sub_total * $tax_rate) / 100;
        $total_amount = $sub_total - $preffered_discount + $total_sales_tax;
        
        // Prepare order data (matches CI PostData structure)
        $orderData = [
            'user_id' => $loginId,
            'name' => session('loginName'),
            'email' => $userData->email,
            'mobile' => $userData->mobile,
            'delivery_address_id' => $request->delivery_address_id,
            
            'billing_name' => $address->name,
            'billing_company' => $address->company_name,
            'billing_pin_code' => $address->pin_code,
            'billing_mobile' => $address->mobile,
            'billing_address' => $address->address,
            'billing_city' => $address->city,
            'billing_state' => $address->state,
            'billing_country' => $address->country,
            'billing_landmark' => $address->landmark,
            'billing_alternate_phone' => $address->alternate_phone,
            'billing_address_type' => $address->address_type,
            
            'shipping_name' => $address->name,
            'shipping_company' => $address->company_name,
            'shipping_pin_code' => $address->pin_code,
            'shipping_mobile' => $address->mobile,
            'shipping_address' => $address->address,
            'shipping_city' => $address->city,
            'shipping_state' => $address->state,
            'shipping_country' => $address->country,
            'shipping_landmark' => $address->landmark,
            'shipping_alternate_phone' => $address->alternate_phone,
            'shipping_address_type' => $address->address_type,
            
            'sub_total_amount' => $sub_total,
            'preffered_customer_discount' => $preffered_discount,
            'total_sales_tax' => $total_sales_tax,
            'total_amount' => $total_amount,
            'total_items' => $total_items,
            'delivery_charge' => 0,
            'coupon_code' => '',
            'coupon_discount_amount' => 0,
            // CI uses integer status column; 2 = New (matches database default)
            'status' => 2,
            // Payment status: 1 = pending (matches CI PaymentStatus::Pending)
            'payment_status' => 1,
            
            // Additional fields from CI
            'currency_id' => $main_store_data['default_currency_id'] ?? 1,
            'store_id' => $main_store_data['id'],
            'payment_mode' => $main_store_data['paypal_payment_mode'] ?? 'paypal',
        ];
        if (!empty($order_id)) {
            // Update existing order (matches CI logic)
            $orderData['id'] = $order_id;
            DB::table('product_orders')->where('id', $order_id)->update($orderData);
            $insert_id = $order_id;
        } else {
            // Create new order (matches CI logic)
            $orderData['created'] = now();
            $insert_id = DB::table('product_orders')->insertGetId($orderData);
            
            // Update order_id with prefix (matches CI logic)
            // dd("order_id_prefix: ", $main_store_data['order_id_prefix']);
            $order_prefix = $main_store_data['order_id_prefix'] ?? 'ORD';
            DB::table('product_orders')->where('id', $insert_id)->update([
                'order_id' => $order_prefix . $insert_id
            ]);
        }
        
        // Save order items (matches CI ProductOrderItem logic)
        $items = $cart->contents();
        foreach ($items as $item) {
            $productData = DB::table('products')->where('id', $item['id'])->first();
            
            $orderItemData = [
                'order_id' => $insert_id,
                'product_id' => $item['id'],
                'name' => substr($productData->name ?? '', 0, 255),
                'name_french' => substr($productData->name_french ?? '', 0, 250),
                'price' => $item['price'],
                'quantity' => $item['qty'],
                'subtotal' => $item['subtotal'],
                'product_image' => substr($productData->product_image ?? '', 0, 200),
                'short_description' => substr($productData->short_description ?? '', 0, 250),
                'short_description_french' => substr($productData->short_description_french ?? '', 0, 250),
                'full_description' => $productData->full_description ?? '',
                'full_description_french' => $productData->full_description_french ?? '',
                'discount' => $productData->discount ?? 0,
                'code' => substr($productData->code ?? '', 0, 50),
                'brand' => substr($productData->brand ?? '', 0, 50),
                'delivery_charge' => $productData->delivery_charge ?? 0,
                'total_stock' => $productData->total_stock ?? 0,
                'shipping_box_length' => $productData->shipping_box_length ?? 0,
                'shipping_box_width' => $productData->shipping_box_width ?? 0,
                'shipping_box_height' => $productData->shipping_box_height ?? 0,
                'shipping_box_weight' => $productData->shipping_box_weight ?? 0,
                
                // Cart options (JSON encoded like CI)
                'cart_images' => json_encode($item['options']['cart_images'] ?? []),
                'attribute_ids' => json_encode($item['options']['attribute_ids'] ?? []),
                'product_size' => json_encode($item['options']['product_size'] ?? []),
                'product_width_length' => json_encode($item['options']['product_width_length'] ?? []),
                'page_product_width_length' => json_encode($item['options']['page_product_width_length'] ?? []),
                'product_depth_length_width' => json_encode($item['options']['product_depth_length_width'] ?? []),
                'votre_text' => $item['options']['votre_text'] ?? '',
                'recto_verso' => $item['options']['recto_verso'] ?? '',
                // Note: provider_product_id is NOT saved in product_order_items table in CI
                // It's only retrieved via JOIN when reading data
                
                'created' => now(),
            ];
            
            if (!empty($order_id)) {
                // Update existing order item
                DB::table('product_order_items')
                    ->where('order_id', $insert_id)
                    ->where('product_id', $item['id'])
                    ->update($orderItemData);
            } else {
                // Insert new order item
                DB::table('product_order_items')->insert($orderItemData);
            }
        }
        
        // Redirect to next step (matches CI logic)
        $next_step = 3;
        return redirect('Checkouts/index/' . base64_encode($next_step) . '/' . base64_encode($insert_id));
    }
    
    /**
     * Save shipping method
     */
    public function saveShipping(Request $request)
    {
        $order_id = $request->order_id;
        $shipping_method = $request->shipping_method;

        if (empty($order_id) || empty($shipping_method)) {
            return redirect()->back()->with('message_error', 'Please select a shipping method');
        }

        $order = DB::table('product_orders')->where('id', $order_id)->first();
        if (!$order) {
            return redirect()->back()->with('message_error', 'Order not found');
        }

        // Start from current total amount
        $new_total = $order->total_amount;

        // If an old shipping method exists, remove its delivery charge from total
        if (!empty($order->shipping_method_formate)) {
            $old_parts = explode('-', $order->shipping_method_formate);
            $old_delivery = isset($old_parts[1]) ? (float) $old_parts[1] : 0.0;
            $new_total -= $old_delivery;
        }

        // Parse new shipping method (format: method-price-...)
        $shipping_parts = explode('-', $shipping_method);
        $delivery_charge = isset($shipping_parts[1]) ? (float) $shipping_parts[1] : 0.0;

        $updateData = [
            'shipping_method_formate' => $shipping_method,
            'delivery_charge' => $delivery_charge,
            'total_amount' => $new_total + $delivery_charge,
        ];

        // FlagShip-specific original cost (4th segment)
        if (!empty($shipping_parts[0]) && $shipping_parts[0] === 'flagship') {
            $updateData['flag_shiping_cost'] = isset($shipping_parts[3]) ? (float) $shipping_parts[3] : 0.0;
        } else {
            $updateData['flag_shiping_cost'] = 0.0;
        }

        DB::table('product_orders')->where('id', $order_id)->update($updateData);

        // Redirect to payment step
        $next_step = 4;
        return redirect('Checkouts/index/' . base64_encode($next_step) . '/' . base64_encode($order_id));
    }
    
    /**
     * Place order and process payment
     */
    public function placeOrder(Request $request)
    {
        $order_id = $request->order_id;
        $payment_method = $request->payment_method;
        
        if (empty($order_id)) {
            return response()->json(['status' => 0, 'msg' => 'Invalid order']);
        }
        
        // Update order with payment method
        DB::table('product_orders')->where('id', $order_id)->update([
            'payment_method' => $payment_method,
            'order_status' => 'confirmed',
            'updated' => now(),
        ]);
        
        // Clear cart
        $cart = new CartService();
        $cart->destroy();
        
        // Send order confirmation email (implement later)
        
        return response()->json([
            'status' => 1,
            'msg' => 'Order placed successfully',
            'order_id' => $order_id
        ]);
    }

    // ========== Private helper methods (ported from Public\CheckoutController for CI compatibility) ==========

    private function getStateById($id)
    {
        if (empty($id)) {
            return [];
        }
        $state = DB::table('states')->where('id', $id)->first();
        return $state ? (array) $state : [];
    }

    private function getCountryById($id)
    {
        if (empty($id)) {
            return [];
        }
        $country = DB::table('countries')->where('id', $id)->first();
        return $country ? (array) $country : [];
    }

    private function getCityById($id)
    {
        if (empty($id)) {
            return [];
        }
        $city = DB::table('cities')->where('id', $id)->first();
        return $city ? (array) $city : [];
    }

    private function salesTaxRatesProvincesById($state_id)
    {
        if (empty($state_id)) {
            return ['total_tax_rate' => 0];
        }

        // Match CI Address_Model::salesTaxRatesProvincesById, but be tolerant to
        // either the legacy dashed table name or a normalized underscored one.
        if (Schema::hasTable('sales_tax_rates_provinces')) {
            $tax = DB::table('sales_tax_rates_provinces')
                ->where('state_id', $state_id)
                ->first();
        } elseif (Schema::hasTable('sales-tax-rates-provinces')) {
            $tax = DB::table('sales-tax-rates-provinces')
                ->where('state_id', $state_id)
                ->first();
        } else {
            $tax = null;
        }

        return $tax ? (array) $tax : ['total_tax_rate' => 0];
    }

    private function getOrderProductCount($order_id)
    {
        if (empty($order_id)) {
            return 0;
        }

        return DB::table('product_order_items')
            ->join('provider_products', 'product_order_items.product_id', '=', 'provider_products.product_id')
            ->where('product_order_items.order_id', $order_id)
            ->count();
    }

    private function sinaShippingMethods($order_id)
    {
        // Placeholder for Sina API integration. Kept for CI parity.
        // Return structure: [['code', 'service_name', 'price'], ...]
        return [];
    }

    private function getStoreDataById($id)
    {
        if (empty($id)) {
            return [];
        }
        $store = DB::table('stores')->where('id', $id)->first();
        return $store ? (array) $store : [];
    }

    private function calculateShippingCost($total_amount)
    {
        // Implement custom company shipping cost rules if needed.
        // For now, mirror Public\CheckoutController stub and return 0.
        return 0;
    }

    private function getPickupStoresList()
    {
        // CI: Store_Model->getPickupStoresList() reads from `pickup_stores` table
        return DB::table('pickup_stores')
            ->get()
            ->map(function ($item) {
                return (array) $item;
            })
            ->toArray();
    }
}
