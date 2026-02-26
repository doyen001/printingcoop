@extends('elements.app')

@section('title', $language_name == 'french' ? 'Panier' : 'Shopping Cart')

@section('content')
<style>
    /* Simple and Clean Shopping Cart Styles */
    .cart-section {
        padding: 60px 0;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .cart-section-inner {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
    }

    /* Table Styles */
    .shop-cart-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    /* .shop-cart-table th {
        background: #2c3e50;
        color: #ffffff;
        padding: 15px 12px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        border: none;
    } */

    .shop-cart-table th:first-child {
        border-radius: 8px 0 0 0;
    }

    .shop-cart-table th:last-child {
        border-radius: 0 8px 0 0;
    }

    .shop-cart-table td {
        padding: 20px 12px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .shop-cart-table tr:last-child td {
        border-bottom: none;
    }

    /* Product Remove Button */
    .product-remove .remove {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #dc3545;
        color: #ffffff;
        text-decoration: none;
        border-radius: 50%;
        font-size: 18px;
        font-weight: bold;
        transition: all 0.2s ease;
    }

    .product-remove .remove:hover {
        background: #c82333;
        transform: scale(1.1);
        text-decoration: none;
        color: #ffffff;
    }

    /* Product Thumbnail */
    .product-thumbnail img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    /* Product Name */
    .product-name a {
        color: #2c3e50;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        transition: color 0.2s ease;
    }

    .product-name a:hover {
        color: #f28738;
        text-decoration: none;
    }

    .product-name-detail {
        margin-top: 10px;
    }

    .product-name-detail .row {
        margin: 0;
    }

    .product-name-detail span {
        display: block;
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .product-name-detail strong {
        color: #495057;
        font-weight: 600;
    }

    /* Uploaded Files */
    .uploaded-file-detail {
        margin-top: 15px;
    }

    .uploaded-file-single {
        margin-bottom: 10px;
        background: #f8f9fa;
        border-radius: 6px;
        padding: 10px;
        border: 1px solid #e9ecef;
    }

    .uploaded-file-single-inner {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .uploaded-file-img {
        background-size: cover;
        background-position: center;
        border-radius: 4px;
        border: 1px solid #dee2e6;
        flex-shrink: 0;
    }

    .uploaded-file-info {
        flex: 1;
    }

    .uploaded-file-name a {
        color: #007bff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
    }

    .uploaded-file-name a:hover {
        text-decoration: underline;
    }

    .upload-field textarea {
        width: 100%;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 5px;
        font-size: 11px;
        resize: none;
        background: #ffffff;
        margin-top: 5px;
    }

    /* Price and Quantity */
    .product-price1 span {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }

    .quant-cart input {
        width: 80px;
        padding: 8px 12px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        text-align: center;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .quant-cart input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.1);
    }

    .product-subtotal span {
        font-size: 16px;
        font-weight: 700;
        color: #f28738;
    }

    /* Action Buttons */
    .actions {
        text-align: left;
    }

    .coupon button,
    .checkout .coupon button {
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .coupon button {
        border: none !important;
    }

    .coupon button:hover {
        background: #b86527;
    }

    .checkout .coupon button {
        background: #28a745;
        color: #ffffff;
    }

    .checkout .coupon button:hover {
        background: #218838;
        text-decoration: none;
        color: #ffffff;
    }

    /* Cart Total */
    .cart-total {
        margin-bottom: 20px;
        text-align: right;
    }

    .cart-total span {
        font-size: 18px;
        color: #2c3e50;
    }

    .cart-sub-total {
        font-weight: 700;
        color: #f28738;
        font-size: 20px;
    }

    /* Empty Cart */
    .text-center h4 {
        color: #6c757d;
        font-size: 24px;
        margin: 60px 0;
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .cart-section {
            padding: 40px 0;
        }

        .cart-section-inner {
            padding: 20px;
        }

        .shop-cart-table th,
        .shop-cart-table td {
            padding: 10px 8px;
            font-size: 14px;
        }

        .product-thumbnail img {
            width: 60px;
            height: 60px;
        }
    }

    @media (max-width: 767px) {
        .mobile-hide {
            display: none !important;
        }

        .cart-section {
            padding: 20px 0;
        }

        .cart-section-inner {
            padding: 15px;
            border-radius: 8px;
        }

        .shop-cart-table {
            display: block;
            overflow-x: auto;
        }

        .actions {
            text-align: center;
            margin-bottom: 20px;
        }

        .cart-total {
            text-align: center;
        }

        .coupon button,
        .checkout .coupon button {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    @media (max-width: 480px) {
        .product-thumbnail img {
            width: 50px;
            height: 50px;
        }

        .quant-cart input {
            width: 60px;
        }

        .uploaded-file-single-inner {
            flex-direction: column;
        }
    }
</style>

<div class="cart-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="cart-section-inner" id="shoping-cart-container">
            @if(!empty($cart->contents()))
                <table class="shop-cart-table" id="shop-cart-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>{{ $language_name == 'french' ? 'Produit' : 'Product' }}</th>
                            <th>{{ $language_name == 'french' ? 'Prix' : 'Price' }}</th>
                            <th>{{ $language_name == 'french' ? "Combien d'ensembles" : "How many sets" }}</th>
                            <th>{{ $language_name == 'french' ? 'Total' : 'Total' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart->contents() as $rowid => $item)
                            @php
                                $imageurl = getProductImage($item['options']['product_image']);
                                $cart_images = $item['options']['cart_images'];
                                $product_id = $item['options']['product_id'];
                                $Product = DB::table('products')->where('id', $product_id)->first();

                                $attribute_ids = $item['options']['attribute_ids'];
                                $provider_id = 1;
                                $provider_product = \App\Models\ProviderProduct::where('provider_id', $provider_id)
                                    ->where('product_id', $item['options']['product_id'])
                                    ->first();
                                if ($provider_product) {
                                    $attribute_ids = sina_options_map($attribute_ids);
                                }
                                $product_size = $item['options']['product_size'] ?? [];

                                $product_width_length = $item['options']['product_width_length'] ?? [];
                                $page_product_width_length = $item['options']['page_product_width_length'] ?? [];
                                $product_depth_length_width = $item['options']['product_depth_length_width'] ?? [];

                                $votre_text = $item['options']['votre_text'];

                                $recto_verso = $item['options']['recto_verso'];
                                $recto_verso_french = $item['options']['recto_verso_french'];
                            @endphp
                            <tr class="{{ $rowid }} mobile-hide">
                                <td class="product-remove">
                                    <a href="javascript:void(0)"
                                        onclick="removeCartItem('{{ $rowid }}','{{ $item['id'] }}')"
                                        class="remove">×</a>
                                </td>
                                <td class="product-thumbnail">
                                    <a href="{{ url('Products/view/' . base64_encode($Product->id)) }}">
                                        <img src="{{ $imageurl }}">
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="{{ url('Products/view/' . base64_encode($Product->id)) }}">
                                        @if($language_name == 'French')
                                            {{ ucfirst($Product->name_french) }}
                                        @else
                                            {{ ucfirst($Product->name) }}
                                        @endif
                                    </a>
                                    <div class="product-name-detail">
                                        <div class="row">
                                            @if(!empty($product_width_length))
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Longueur(pouces)' : 'Length(Inch)' }}: {{ $product_width_length['product_length'] }}</strong></span>
                                                </div>
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Largeur (pouces)' : 'Width(Inch)' }}: {{ $product_width_length['product_width'] }}</strong></span>
                                                </div>
                                                @if(!empty($product_width_length['length_width_color_show']))
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Couleursv:'.$product_width_length['length_width_color_french'] : 'Colors:'.$product_width_length['length_width_color'] }}</strong></span>
                                                    </div>
                                                @endif
                                                @if(!empty($product_width_length['product_total_page']))
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Quantité' : 'Quantity' }}: {{ $product_width_length['product_total_page'] }}</strong></span>
                                                    </div>
                                                @endif
                                            @endif

                                            @if(!empty($product_depth_length_width))
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Longueur (pouces)': 'Length(Inch)' }}: {{ $product_depth_length_width['product_depth_length'] }}</strong></span>
                                                </div>
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Largeur (pouces)' : 'Width(Inch)' }}: {{ $product_depth_length_width['product_depth_width'] }}</strong></span>
                                                </div>
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Profondeur (pouces)' : 'Depth(Inch)' }}: {{ $product_depth_length_width['product_depth'] }}</strong></span>
                                                </div>
                                                @if(!empty($product_depth_length_width['depth_color_show']))
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Couleursv:'.$product_depth_length_width['depth_color_french'] : 'Colors:'.$product_depth_length_width['depth_color'] }}</strong></span>
                                                </div>
                                                @endif
                                                @if(!empty($product_depth_length_width['product_depth_total_page']))
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Quantité' : 'Quantity' }}: {{ $product_depth_length_width['product_depth_total_page'] }}</strong></span>
                                                    </div>
                                                @endif
                                            @endif
                                            @if(!empty($page_product_width_length))
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Longueur(pouces)' : 'Length(Inch)' }}: {{ $page_product_width_length['page_product_length'] }}</strong></span>
                                                </div>
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Largeur(pouces)' : 'Width(Inch)' }}: {{ $page_product_width_length['page_product_width'] }}</strong></span>
                                                </div>

                                                @if(!empty($page_product_width_length['page_length_width_color_show']))
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Couleursv:'.$page_product_width_length['page_length_width_color_french'] : 'Colors:'.$page_product_width_length['page_length_width_color'] }}</strong></span>
                                                    </div>
                                                @endif
                                                @if(!empty($page_product_width_length['page_product_total_page']))
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Des pages:'.$page_product_width_length['page_product_total_page_french'] : 'Pages:'.$page_product_width_length['page_product_total_page'] }}</strong></span>
                                                    </div>
                                                @endif
                                                @if(!empty($page_product_width_length['page_product_total_sheets']))
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Feuille par bloc:'.$page_product_width_length['page_product_total_sheets_french'] : 'Sheet Per Pad:'.$page_product_width_length['page_product_total_sheets'] }}</strong></span>
                                                    </div>
                                                @endif
                                                @if (!empty($page_product_width_length['page_product_total_quantity']))
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Quantité:'.$page_product_width_length['page_product_total_quantity'] : 'Quantity:'.$page_product_width_length['page_product_total_quantity'] }}</strong></span>
                                                    </div>
                                                @endif
                                            @endif
                                            @if (!empty($product_size)) 
                                                @php
                                                    if ($language_name == 'French') {
                                                        $size_name = $product_size['product_size_french'] ?? '';
                                                        $label_qty = $product_size['product_quantity_french'] ?? '';
                                                    } else {
                                                        $size_name = $product_size['product_size'] ?? '';
                                                        $label_qty = $product_size['product_quantity'] ?? '';
                                                    }

                                                    $attribute = isset($product_size['attribute']) ? $product_size['attribute'] : '';
                                                @endphp
                                                @if($label_qty)
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Quantité' : 'Quantity' }}: {{ $label_qty }}</strong></span>
                                                    </div>
                                                @endif
                                                @if($size_name)
                                                    <div class="col-md-12 col-lg-6 col-xl-6">
                                                        <span><strong>{{ $language_name == 'French' ? 'Taille' : 'Size' }}: {{ $size_name }}</strong></span>
                                                    </div>
                                                @endif

                                                @if($attribute)
                                                    @foreach($attribute as $akey => $aval)
                                                        @php
                                                            $multiple_attribute_name = $aval['attributes_name'];
                                                            $multiple_attribute_item_name = $aval['attributes_item_name'];

                                                            if ($language_name == 'French') {
                                                                $multiple_attribute_name = $aval['attributes_name_french'];
                                                                $multiple_attribute_item_name = $aval['attributes_item_name_french'];
                                                            }
                                                        @endphp

                                                        <div class="col-md-12 col-lg-6 col-xl-6">
                                                            <span>
                                                                <strong>
                                                                    {{ $multiple_attribute_name . ':' . $multiple_attribute_item_name }}
                                                                </strong>
                                                            </span>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif

                                            @include('products.expand_attribute_ids', ['attribute_ids' => $attribute_ids, 'language_name' => $language_name])

                                            @if(!empty($recto_verso))
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Recto verso:'.$recto_verso_french : 'Recto/Verso:'.$recto_verso }}</strong></span>
                                                </div>
                                            @endif
                                            @if(!empty($votre_text))
                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                    <span><strong>{{ $language_name == 'French' ? 'Votre TEXTE - Votre TEXTE' : 'Your TEXT - Votre TEXT' }}: {{ $votre_text }}</strong></span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="uploaded-file-detail" id="upload-file-data">
                                        @if(!empty($cart_images))
                                            @foreach($cart_images as $key => $return_arr)
                                                <div class="uploaded-file-single" id="teb-{{ $return_arr['skey'] }}">
                                                    <div class="uploaded-file-single-inner">
                                                        <a href="{{ $return_arr['file_base_url'] }}" target="_blank">
                                                            <div class="uploaded-file-img" style="background-image: url({{ $return_arr['src'] }})"></div>
                                                        </a>
                                                        <div class="uploaded-file-info">
                                                            <div class="uploaded-file-name">
                                                                <span><a href="{{ $return_arr['file_base_url'] }}" target="_blank">{{ $return_arr['name'] }}</a></span>
                                                            </div>
                                                            <div class="upload-field">
                                                                <textarea readonly>{{ $return_arr['cumment'] }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </td>
                                <td class="product-price1" id="">
                                    <span>{{ $product_price_currency_symbol }}{{ number_format($item['price'], 2) }}</span>
                                </td>
                                <td>
                                    <div class="quant-cart">
                                        <input type="text" onchange="updateCartItem('{{ $item['id'] }}', '{{ $rowid }}',$(this).val())" value="{{ $item['qty'] }}" onkeypress="javascript:return isNumber(event)">
                                    </div>
                                </td>
                                <td class="product-subtotal">
                                    <span class="{{ $rowid }}-product-row-sub-total">{{ $product_price_currency_symbol }}{{ number_format($item['subtotal'], 2) }}</span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2" class="actions">
                                <div class="coupon">
                                    <a href="{{ url('Products') }}"><button type="submit">
                                        {{ $language_name == 'French' ? 'Continuer vos achats' : 'Continue Shopping' }}</button>
                                    </a>
                                </div>
                            </td>
                            <td colspan="4">
                                <div class="checkout">
                                    <div class="cart-total">
                                        <span>
                                            {{ $language_name == 'French' ? 'Sous-total' : 'Sub Total' }}:
                                            <font class="cart-sub-total">
                                                {{ $product_price_currency_symbol }}{{ number_format($cart->total(), 2) }}
                                            </font>
                                        </span>
                                    </div>
                                    <div class="coupon">
                                        <a href="{{ url('Checkouts') }}">
                                            <button type="submit">
                                                {{ $language_name == 'French' ? 'Passer à la caisse' : 'Proceed to Checkout' }}
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="text-center">
                    <h4 class="lead">{{ $language_name == 'French' ? 'Le panier d\'achat est vide' : 'Shopping Cart Is Empty' }}</h4>
                </div>
            @endif
        </div>
    </div>
</div>
<script>
function isNumber(evt) {
    var iKeyCode = (evt.which) ? evt.which : evt.keyCode
    if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
        return false;
    return true;
}
</script>
@endsection
