@extends('layouts.admin')

@section('styles')
<style>
.account-area-inner-box-single {
    padding: 35px 25px 40px 25px;
    border: 1px solid rgba(0,0,0,0.1);
    margin-top: 20px;
    background: #fff;
}
.account-area-inner-box-single .universal-small-dark-title {
    padding-bottom: 20px;
    border-bottom: 2px dashed rgba(0,0,0,0.1);
}
.account-area-inner-box-single .quote-bottom-row {
    margin-top: 25px;
}
.summary-deatil-inner ul {
    list-style: none;
    margin: 0px;
    padding: 0px;
}
.summary-deatil-inner ul li {
    padding: 10px 10px 10px 10px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}
.summary-deatil-inner ul li span {
    font-weight: 300;
    color: #555;
    font-size: 15px;
}
.summary-deatil-inner ul li strong {
    font-weight: 400;
    display: inline-block;
    color: #000;
    font-size: 15px;
}
.summary-deatil-inner ul li:last-child {
    border-bottom: none;
}
.account-area .product-information {
    margin: 60px 0px 0px 0px;
    border: none;
}
.quant-cart.text-left {
    justify-content: left !important;
}
.account-area .shop-cart-table {
    border-collapse: collapse;
    width: 100%;
}
.account-area .shop-cart-table thead th {
    font-size: 14px;
    font-weight: 500;
    color: #aaa;
    text-transform: uppercase;
    padding: 15px 5px;
    vertical-align: middle;
}
.account-area .quant-cart button:last-child {
    margin-left: 10px;
}
.account-area .shop-cart-table tbody td {
    padding: 20px 5px;
}
.account-area .shop-cart-table tr {
    border-bottom: 1px solid rgba(0,0,0,0.1);
    transition: .3s;
    vertical-align: top;
}
.account-area .shop-cart-table a.remove {
    width: 30px;
    height: 30px;
    font-size: 25px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #193e73;
    transition: .3s;
    border-radius: 50%;
}
.account-area .shop-cart-table a.remove:hover {
    color: #fff;
    background-color: #193e73;
    transition: .3s;
}
.account-area .shop-cart-table .product-thumbnail img {
    width: 100px;
    height: auto;
}
.account-area .shop-cart-table td.product-name a {
    font-size: 15px;
    font-weight: 600;
    color: #303030;
    transition: .3s;
}
.account-area .shop-cart-table td.product-name a:hover {
    color: #f58634;
    transition: .3s;
}
.account-area .shop-cart-table td.product-price1 {
    font-size: 15px;
    color: #303030;
    font-weight: 400;
    white-space: nowrap;
}
.account-area .shop-cart-table td.product-subtotal {
    font-size: 15px;
    color: #303030;
    font-weight: 400;
    white-space: nowrap;
}
.account-area .shop-cart-table .quant-cart {
    margin-top: 0px;
    justify-content: center;
}
.account-area .shop-cart-table .quant-cart input {
    width: 50px;
}
.account-area .shop-cart-table .coupon button {
    border: 1px solid #f58634;
    height: 40px;
    color: #fff;
    background: #f58634;
    padding: 5px 20px;
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
    transition: 0.3s;
    white-space: nowrap;
}
.account-area .shop-cart-table .coupon {
    text-align: left;
}
.account-area .shop-cart-table .checkout {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    width: 100%;
}
.account-area .shop-cart-table .cart-total span {
    font-size: 15px;
    font-weight: 400;
    color: #303030;
    word-spacing: 2px;
}
.account-area .shop-cart-table .cart-total {
    margin-right: 40px;
}
.account-area .cart-total span font {
    color: #193e73;
    font-size: 22px;
    font-weight: 600;
}
.account-area .shopping-product-display {
    overflow: initial !important;
    height: auto !important;
}
.account-area .shopping-product-display table {
    border-left: none !important;
    border-right: none !important;
}
</style>
@endsection

@section('content')

@php
// Currency setup (CI project style)
$currency_id = $orderData['currency_id'] ?? 1;
$CurrencyList = [
    1 => ['symbols' => '$'],
    2 => ['symbols' => '€'],
    3 => ['symbols' => '£'],
];
$OrderCurrencyData = $CurrencyList[$currency_id] ?? ['symbols' => '$'];
$order_currency_currency_symbol = $OrderCurrencyData['symbols'];
@endphp

<div class="content-wrapper" style="min-height: 687px;">
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="inner-head-section">
                            <div class="inner-title">
                                <span>{{ ucfirst($page_title) }}</span>
                            </div>
                        </div>

                        <div class="my-account-main-section universal-spacing universal-bg-white">
                            <div class="my-account-section">
                                <div class="account-area">
                                    <div class="account-area-inner-boxes">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="account-area-inner-box-single">
                                                    <div class="universal-small-dark-title">
                                                        <span>Order Information</span>
                                                    </div>
                                                    <div class="quote-bottom-row summary-deatil">
                                                        <div class="summary-deatil-inner">
                                                            <ul>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Order Id</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ $orderData['order_id'] }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Customer Code:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>
                                                                                @if (!empty($orderData['user_id']))
                                                                                    CUST{{ str_pad($orderData['user_id'], 6, '0', STR_PAD_LEFT) }}
                                                                                @else
                                                                                    -
                                                                                @endif
                                                                            </strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Website:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ $StoreList[$orderData['store_id']]['name'] ?? 'Default Store' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Customer Name:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ ucfirst($orderData['name']) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Customer Mobile:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ ucfirst($orderData['mobile']) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Customer Email:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ ucfirst($orderData['email']) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Order Amount:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ $order_currency_currency_symbol }}{{ number_format($orderData['total_amount'], 2) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Order Status:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ $orderData['status'] }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Order Date:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ date('M d, Y H:i', strtotime($orderData['created'])) }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Shipping Method:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>
                                                                                @if (!empty($orderData['shipping_method_formate']))
                                                                                    @php
                                                                                    $shipping_method_formate = explode('-', $orderData['shipping_method_formate']);
                                                                                    if ($shipping_method_formate[0] == "pickupinstore") {
                                                                                        echo 'Pickup In Store<br>';
                                                                                        if (isset($StoreList[$shipping_method_formate[2]])) {
                                                                                            echo $StoreList[$shipping_method_formate[2]]['name'] . "<br>" . $StoreList[$shipping_method_formate[2]]['address'] . "<br>" . $StoreList[$shipping_method_formate[2]]['phone'];
                                                                                        }
                                                                                    } else {
                                                                                        echo ucfirst($orderData['shipping_method_formate'] ?? 'Standard');
                                                                                    }
                                                    @endphp
                                                @else
                                                    Standard
                                                @endif
                                            </strong>
                                        </div>
                                    </div>
                                </li>
                                @if (!empty($orderData['flag_shiping_cost']) && $orderData['flag_shiping_cost'] != 0.00)
                                <li>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span>Flagship Shipping Cost:</span>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>
                                                {{ $order_currency_currency_symbol }}{{ number_format($orderData['flag_shiping_cost'], 2) }}
                                            </strong>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="account-area-inner-box-single">
                                                    <div class="universal-small-dark-title">
                                                        <span>Payment Information</span>
                                                    </div>
                                                    <div class="quote-bottom-row summary-deatil">
                                                        <div class="summary-deatil-inner">
                                                            <ul>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Payment Method:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ ucfirst($orderData['payment_type'] ?? 'N/A') }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Payment Status:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ $orderData['payment_status'] }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Payment Transition Id:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>{{ $orderData['transition_id'] ?? 'N/A' }}</strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="account-area-inner-box-single">
                                                    <div class="universal-small-dark-title">
                                                        <span>Billing Information</span>
                                                    </div>
                                                    <div class="quote-bottom-row summary-deatil">
                                                        <div class="summary-deatil-inner">
                                                            <ul>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Billing Address:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>
                                                                                {{ ucfirst($orderData['billing_name']) }}
                                                                                <br>
                                                                                Mobile: {{ ucfirst($orderData['billing_mobile']) }}
                                                                                @if (!empty($orderData['billing_alternate_phone']))
                                                                                    , {{ $orderData['billing_alternate_phone'] }}
                                                                                @endif
                                                                                <br>
                                                                                @if (!empty($orderData['billing_company']))
                                                                                    Company: {{ $orderData['billing_company'] }}
                                                                                    <br>
                                                                                @endif
                                                                                {{ $orderData['billing_address'] }}
                                                                                <br>
                                                                                {{ $cityData['name'] ?? '' }}, {{ $stateData['name'] ?? '' }}, {{ $countryData['name'] ?? '' }}, {{ $orderData['billing_pin_code'] }}
                                                                            </strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="account-area-inner-box-single">
                                                    <div class="universal-small-dark-title">
                                                        <span>Shipping Information</span>
                                                    </div>
                                                    <div class="quote-bottom-row summary-deatil">
                                                        <div class="summary-deatil-inner">
                                                            <ul>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Shipping Address:</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>
                                                                                {{ ucfirst($orderData['shipping_name']) }}
                                                                                <br>
                                                                                Mobile: {{ ucfirst($orderData['shipping_mobile']) }}
                                                                                @if (!empty($orderData['shipping_alternate_phone']))
                                                                                    , {{ $orderData['shipping_alternate_phone'] }}
                                                                                @endif
                                                                                @if (!empty($orderData['shipping_company']))
                                                                                    <br>
                                                                                    Company: {{ $orderData['shipping_company'] }}
                                                                                @endif
                                                                                <br>
                                                                                {{ $orderData['shipping_address'] }}
                                                                                <br>
                                                                                {{ $cityData['name'] ?? '' }}, {{ $stateData['name'] ?? '' }}, {{ $countryData['name'] ?? '' }}, {{ $orderData['shipping_pin_code'] }}
                                                                            </strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="account-area-inner-box-single">
                                                    <div class="universal-small-dark-title">
                                                        <span>Invoice Download</span>
                                                    </div>
                                                    <div class="quote-bottom-row summary-deatil">
                                                        <div class="summary-deatil-inner">
                                                            <ul>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Invoice PDF</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>
                                                                                <a href="{{ url('admin/Orders/downloadInvoice/' . $orderData['id']) }}">
                                                                                    <button type="button" class="btn btn-sm btn-danger"><i class="fa fas fa-file-download"></i> Download</button>
                                                                                </a>
                                                                            </strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <span>Order PDF</span>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <strong>
                                                                                <a href="{{ url('admin/Orders/downloadOrder/' . $orderData['id']) }}">
                                                                                    <button type="button" class="btn btn-sm btn-danger"><i class="fa fas fa-file-download"></i> Download</button>
                                                                                </a>
                                                                            </strong>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-information">
                                    <div class="shopping-product-section">
                                        <div class="shopping-product-display">
                                            <table class="shop-cart-table">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Items Details</th>
                                                        <th>Price</th>
                                                        <th>Quantity</th>
                                                        <th>Subtotal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($OrderItemData as $rowid => $item)
                                                    @php
                                                    // Data is already decoded in controller (CI project style)
                                                    $cart_images = $item['cart_images'] ?? [];
                                                    $attribute_ids = $item['attribute_ids'] ?? [];
                                                    $product_size = $item['product_size'] ?? [];
                                                    $product_width_length = $item['product_width_length'] ?? [];
                                                    $page_product_width_length = $item['page_product_width_length'] ?? [];
                                                    $product_depth_length_width = $item['product_depth_length_width'] ?? [];

                                                    // Handle text fields (CI project style)
                                                    $votre_text = $item['votre_text'] ?? '';
                                                    $recto_verso = $item['recto_verso'] ?? '';
                                                    $product_id = $item['product_id'] ?? '';
                                                    @endphp
                                                    <tr>
                                                        <td class="product-thumbnail">
                                                            <a href="{{ url('Products/view/' . base64_encode($item['id'])) }}">
                                                                @php $imageurl = getProductImage($item['product_image']); @endphp
                                                                <img src="{{ $imageurl }}">
                                                            </a>
                                                        </td>
                                                        <td class="product-name">
                                                            <a href="{{ url('Products/view/' . base64_encode($item['id'])) }}">{{ ucfirst($item['name']) }}</a>
                                                            <div class="product-name-detail">
                                                                <div class="row">
                                                                    @if(!empty($product_width_length))
                                                                        @if(!empty($product_width_length['product_length']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Length(Inch): {{ $product_width_length['product_length'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($product_width_length['product_width']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Width(Inch): {{ $product_width_length['product_width'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($product_width_length['length_width_color_show']) && !empty($product_width_length['length_width_color']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Colors: {{ $product_width_length['length_width_color'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($product_width_length['product_total_page']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Quantity: {{ $product_width_length['product_total_page'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                    
                                                                    @if(!empty($product_depth_length_width))
                                                                        @if(!empty($product_depth_length_width['product_depth_length']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Length(Inch): {{ $product_depth_length_width['product_depth_length'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($product_depth_length_width['product_depth_width']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Width(Inch): {{ $product_depth_length_width['product_depth_width'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($product_depth_length_width['product_depth']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Depth(Inch): {{ $product_depth_length_width['product_depth'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($product_depth_length_width['depth_color_show']) && !empty($product_depth_length_width['depth_color']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Colors: {{ $product_depth_length_width['depth_color'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($product_depth_length_width['product_depth_total_page']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Quantity: {{ $product_depth_length_width['product_depth_total_page'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    @if(!empty($page_product_width_length))
                                                                        @if(!empty($page_product_width_length['page_product_length']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Page Length(Inch): {{ $page_product_width_length['page_product_length'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if(!empty($page_product_width_length['page_product_width']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>Page Width(Inch): {{ $page_product_width_length['page_product_width'] }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                            @if(!empty($page_product_width_length['page_length_width_color_show']) && !empty($page_product_width_length['page_length_width_color']))
                                                                                <div class="col-md-6">
                                                                                    <span><strong>Colors: {{ $page_product_width_length['page_length_width_color'] }}</strong></span>
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($page_product_width_length['page_product_total_page']))
                                                                                <div class="col-md-6">
                                                                                    <span><strong>Pages: {{ $page_product_width_length['page_product_total_page'] }}</strong></span>
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($page_product_width_length['page_product_total_sheets']))
                                                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                    <span><strong>Sheet Per Pad: {{ $page_product_width_length['page_product_total_sheets'] }}</strong></span>
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($page_product_width_length['page_product_total_quantity']))
                                                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                    <span><strong>Quantity: {{ $page_product_width_length['page_product_total_quantity'] }}</strong></span>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    
                                                                    @if(!empty($product_size))
                                                                        @if(!empty($product_size['product_quantity']))
                                                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                    <span><strong>Quantity: {{ $product_size['product_quantity'] }}</strong></span>
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($product_size['product_size']))
                                                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                    <span><strong>Size: {{ $product_size['product_size'] }}</strong></span>
                                                                                </div>
                                                                            @endif
                                                                            @if(!empty($product_size['attribute']))
                                                                                @foreach($product_size['attribute'] as $attribute)
                                                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                        <span>
                                                                                            <strong>{{ $attribute['attributes_name'] ?? '' }}: {{ $attribute['attributes_item_name'] ?? '' }}</strong>
                                                                                        </span>
                                                                                    </div>
                                                                                @endforeach
                                                                            @endif
                                                                        @endif
                                                                    
                                                                    @if(!empty($attribute_ids))
                                                                        @foreach($attribute_ids as $attribute)
                                                                            <div class="col-md-6">
                                                                                <span><strong>{{ $attribute['name'] ?? '' }}: {{ $attribute['value'] ?? '' }}</strong></span>
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                    
                                                                    @if($item['recto_verso'])
                                                                        <div class="col-md-6">
                                                                            <span><strong>Recto/Verso: {{ $item['recto_verso'] }}</strong></span>
                                                                        </div>
                                                                    @endif
                                                                    
                                                                    @if($item['votre_text'])
                                                                        <div class="col-md-6">
                                                                            <span><strong>Your TEXT - Votre TEXT: {{ $item['votre_text'] }}</strong></span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="uploaded-file-detail" id="upload-file-data">
                                                                @if(!empty($cart_images))
                                                                    @foreach($cart_images as $key => $file)
                                                                        <div class="uploaded-file-single" id="teb-{{ $file['skey'] ?? $key }}">
                                                                            <div class="uploaded-file-single-inner">
                                                                                <div class="uploaded-file-img" style="background-image: url({{ asset('defaults/pdf-icon.png') }})">
                                                                                </div>
                                                                                <img src="{{ asset('defaults/pdf-icon.png') }}" width="150">
                                                                                <br>
                                                                                <div class="uploaded-file-info">
                                                                                    <div class="uploaded-file-name">
                                                                                        <span>{{ $file['name'] }}</span>
                                                                                    </div>
                                                                                    @if(!empty($file['cumment']))
                                                                                        <div class="upload-field">
                                                                                            Comment: {{ $file['cumment'] }}
                                                                                        </div>
                                                                                    @endif
                                                                                    <a href="{{ url('admin/Orders/download/' . base64_encode(str_replace('printing.coop' , 'laravel.imprimeriecoop.com' , $file['location']) ?? '') . '/' . base64_encode($file['name'])) }}">
                                                                                        <i class="fa fas fa-file-download"></i> Download
                                                                                    </a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="product-price1">
                                                            <span>{{ $order_currency_currency_symbol }}{{ number_format($item['price'], 2) }}</span>
                                                        </td>
                                                        <td class="quant-cart text-left">
                                                            {{ $item['quantity'] }}
                                                        </td>
                                                        <td class="product-subtotal">
                                                            <span>{{ $order_currency_currency_symbol }}{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="5" class="text-right">
                                                            <div class="cart-total">
                                                                <span>Subtotal Amount: <font class="cart-sub-total">{{ $order_currency_currency_symbol }}{{ number_format($orderData['sub_total_amount'], 2) }}</font></span>
                                                            </div>
                                                            @if(!empty($orderData['preffered_customer_discount']) && $orderData['preffered_customer_discount'] != 0.00)
                                                                <div class="cart-total">
                                                                    <span>
                                                                        Preffered Customer Discount:
                                                                        <font class="cart-sub-total">-{{ $order_currency_currency_symbol }}{{ number_format($orderData['preffered_customer_discount'], 2) }}</font>
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            @if(!empty($orderData['coupon_discount_amount']) && $orderData['coupon_discount_amount'] != 0.00)
                                                                <div class="cart-total">
                                                                    <span>Coupon Discount:
                                                                        <font class="cart-sub-total">-{{ $order_currency_currency_symbol }}{{ number_format($orderData['coupon_discount_amount'], 2) }}</font>
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            <div class="cart-total">
                                                                <span>Shipping Fee:
                                                                    <font class="cart-sub-total">{{ $order_currency_currency_symbol }}{{ number_format($orderData['delivery_charge'], 2) }}</font>
                                                                </span>
                                                            </div>
                                                            <div class="cart-total">
                                                                <span>Tax GST 5%:
                                                                    <font class="cart-sub-total">{{ $order_currency_currency_symbol }}{{ number_format(($orderData['delivery_charge'] + $orderData['sub_total_amount']) * 0.05, 2) }}</font>
                                                                </span>
                                                            </div>
                                                            <div class="cart-total">
                                                                <span>Tax QST 9.975%:
                                                                    <font class="cart-sub-total">{{ $order_currency_currency_symbol }}{{ number_format(($orderData['delivery_charge'] + $orderData['sub_total_amount']) * 0.09975, 2) }}</font>
                                                                </span>
                                                            </div>
                                                            @if(!empty($orderData['total_sales_tax']) && $orderData['total_sales_tax'] != 0)
                                                                <div class="cart-total">
                                                                    <span>Total Sales Tax:
                                                                        <font class="cart-sub-total">{{ $order_currency_currency_symbol }}{{ number_format($orderData['total_sales_tax'], 2) }}</font>
                                                                    </span>
                                                                </div>
                                                            @endif
                                                            <div class="cart-total">
                                                                <span>
                                                                    Order Total Amount:
                                                                    <font class="cart-sub-total">{{ $order_currency_currency_symbol }}{{ number_format($orderData['total_amount'], 2) }}</font>
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    console.log(@json($OrderItemData)); 
</script>
@endsection
