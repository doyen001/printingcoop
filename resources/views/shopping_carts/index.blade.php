@extends('elements.app')

@section('title', $language_name == 'french' ? 'Panier' : 'Shopping Cart')

@section('content')
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
