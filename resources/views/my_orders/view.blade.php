@extends('elements.app')

@section('content')
@php
    $language_name = strtolower(config('store.language_name', 'english'));
    $currency_id = $orderData->currency_id ?? 1;
    // CI-style static currency list (can be replaced by DB lookup if needed)
    $CurrencyList = [
        1 => ['symbols' => '$'],
        2 => ['symbols' => '€'],
        3 => ['symbols' => '£'],
    ];
    $OrderCurrencyData = $CurrencyList[$currency_id] ?? ['symbols' => '$'];
    $order_currency_currency_symbol = $OrderCurrencyData['symbols'];
@endphp

<div class="my-account-main-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="my-account-section">
            @include('elements.my-account-menu')

            <div class="account-area">
                <div class="universal-dark-title">
                    <span>{{ $page_title }}</span>
                </div>

                <div class="text-center" style="color:red">
                    {{ session('message_error') }}
                </div>
                <div class="text-center" style="color:green">
                    {{ session('message_success') }}
                </div>
                <br>

                <div class="account-area-inner-boxes">
                    <div class="row">
                        <div class="col-md-6">
                            {{-- Order Information --}}
                            <div class="account-area-inner-box-single">
                                <div class="universal-small-dark-title">
                                    <span>
                                        {{ $language_name == 'french' ? 'Informations sur la commande' : 'Order Information' }}
                                    </span>
                                </div>
                                <div class="quote-bottom-row summary-deatil">
                                    <div class="summary-deatil-inner">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Numéro de commande' : 'Order Id' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ $orderData->order_id }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Code client' : 'Customer Code' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>
                                                            @if(!empty($orderData->user_id))
                                                                {{ (config('store.customer_id_prefix', 'CUST') . $orderData->user_id) }}
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
                                                        <span>{{ $language_name == 'french' ? 'Nom du client' : 'Customer Name' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ ucfirst($orderData->name) }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Mobile client' : 'Customer Mobile' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ $orderData->mobile }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Email client' : 'Customer Email' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ $orderData->email }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Montant de la commande' : 'Order Amount' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ $order_currency_currency_symbol . number_format($orderData->total_amount, 2) }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Statut de la commande' : 'Order Status' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{!!
                                                            $language_name == 'french'
                                                                ? getOrderSatusClassFrench($orderData->status ?? $orderData->order_status)
                                                                : getOrderSatusClass($orderData->status ?? $orderData->order_status)
                                                        !!}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Date de commande' : 'Order Date' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ \Carbon\Carbon::parse($orderData->created)->format('Y-m-d H:i') }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Mode de livraison' : 'Shipping Method' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @php
                                                            // CI: getShipingName($orderData) with pickupinstore fallback
                                                            $shippingLabel = getShipingName($orderData);
                                                            $pickupStore = null;
                                                            if (empty($shippingLabel) && !empty($orderData->shipping_method_formate ?? null)) {
                                                                $parts = explode('-', $orderData->shipping_method_formate);
                                                                if (!empty($parts) && $parts[0] === 'pickupinstore' && !empty($parts[2])) {
                                                                    $pickupStore = \Illuminate\Support\Facades\DB::table('pickup_stores')
                                                                        ->where('id', $parts[2])
                                                                        ->first();
                                                                }
                                                            }
                                                        @endphp
                                                        <strong>
                                                            @if (!empty($shippingLabel))
                                                                {!! $shippingLabel !!}
                                                            @elseif ($pickupStore)
                                                                {{ $language_name == 'french' ? 'Ramassage en magasin' : 'Pickup In Store' }}<br>
                                                                {{ $pickupStore->name ?? '' }}<br>
                                                                {{ $pickupStore->address ?? '' }}<br>
                                                                {{ $pickupStore->phone ?? '' }}
                                                            @endif
                                                        </strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Commentaire de commande' : 'Order Comment' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ $orderData->order_comment }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Information (simplified) --}}
                            <div class="account-area-inner-box-single">
                                <div class="universal-small-dark-title">
                                    <span>{{ $language_name == 'french' ? 'Informations de paiement' : 'Payment Information' }}</span>
                                </div>
                                <div class="quote-bottom-row summary-deatil">
                                    <div class="summary-deatil-inner">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Type de paiement' : 'Payment Type' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ ucfirst($orderData->payment_type ?? '') }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Statut de paiement' : 'Payment Status' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{!!
                                                            $language_name == 'french'
                                                                ? getOrderPaymentStatusFrench($orderData->payment_status)
                                                                : getOrderPaymentStatus($orderData->payment_status)
                                                        !!}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'ID de transaction' : 'Payment Transaction Id' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>{{ $orderData->transition_id ?? '' }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Billing & Shipping (simplified) --}}
                        <div class="col-md-6">
                            <div class="account-area-inner-box-single">
                                <div class="universal-small-dark-title">
                                    <span>{{ $language_name == 'french' ? 'Détails de facturation' : 'Billing Information' }}</span>
                                </div>
                                <div class="quote-bottom-row summary-deatil">
                                    <div class="summary-deatil-inner">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Adresse de facturation' : 'Billing Address' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>
                                                            {{ $orderData->billing_name }}<br>
                                                            {{ $orderData->billing_mobile }}
                                                            @if(!empty($orderData->billing_alternate_phone))
                                                                , {{ $orderData->billing_alternate_phone }}
                                                            @endif
                                                            <br>
                                                            @if(!empty($orderData->billing_company))
                                                                {{ $orderData->billing_company }}<br>
                                                            @endif
                                                            {{ $orderData->billing_address }}<br>
                                                            {{ $cityData->name ?? '' }},
                                                            {{ $stateData->name ?? '' }},
                                                            {{ $countryData->iso2 ?? '' }},
                                                            {{ $orderData->billing_pin_code }}
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
                                    <span>{{ $language_name == 'french' ? 'Informations sur la livraison' : 'Shipping Information' }}</span>
                                </div>
                                <div class="quote-bottom-row summary-deatil">
                                    <div class="summary-deatil-inner">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Adresse de livraison' : 'Shipping Address' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <strong>
                                                            {{ $orderData->shipping_name }}<br>
                                                            {{ $orderData->shipping_mobile }}
                                                            @if(!empty($orderData->shipping_alternate_phone))
                                                                , {{ $orderData->shipping_alternate_phone }}
                                                            @endif
                                                            <br>
                                                            @if(!empty($orderData->shipping_company))
                                                                {{ $orderData->shipping_company }}<br>
                                                            @endif
                                                            {{ $orderData->shipping_address }}<br>
                                                            {{ $shippingCity->name ?? '' }},
                                                            {{ $shippingState->name ?? '' }},
                                                            {{ $shippingCountry->iso2 ?? '' }},
                                                            {{ $orderData->shipping_pin_code }}
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
                                    <span>{{ $language_name == 'french' ? 'Facture PDF' : 'Invoice PDF' }}</span>
                                </div>
                                <div class="quote-bottom-row summary-deatil">
                                    <div class="summary-deatil-inner">
                                        <ul>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Facture PDF' : 'Invoice PDF' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @php
                                                            $invoiceUrl = url('MyOrders/downloadOrderPdf/' . $orderData->id . '/invoice');
                                                        @endphp
                                                        <strong>
                                                            <a href="{{ $invoiceUrl }}">
                                                                <button type="button" class="btn btn-sm btn-danger">
                                                                    <i class="fa fas fa-file-download"></i>
                                                                    {{ $language_name == 'french' ? 'Télécharger' : 'Download' }}
                                                                </button>
                                                            </a>
                                                        </strong>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <span>{{ $language_name == 'french' ? 'Commander le PDF' : 'Order PDF' }}</span>
                                                    </div>
                                                    <div class="col-md-6">
                                                        @php
                                                            $orderUrl = url('MyOrders/downloadOrderPdf/' . $orderData->id . '/order');
                                                        @endphp
                                                        <strong>
                                                            <a href="{{ $orderUrl }}">
                                                                <button type="button" class="btn btn-sm btn-danger">
                                                                    <i class="fa fas fa-file-download"></i>
                                                                    {{ $language_name == 'french' ? 'Télécharger' : 'Download' }}
                                                                </button>
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

                        {{-- Order Items & Totals --}}
                        <div class="col-md-12">
                            <div class="product-information">
                                <div class="shopping-product-section">
                                    <div class="shopping-product-display">
                                        <table class="shop-cart-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>{{ $language_name == 'french' ? 'Détails des articles' : 'Items Details' }}</th>
                                                    <th>{{ $language_name == 'french' ? 'Prix' : 'Price' }}</th>
                                                    <th>{{ $language_name == 'french' ? 'Quantité' : 'Quantity' }}</th>
                                                    <th>{{ $language_name == 'french' ? 'Total' : 'Subtotal' }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($OrderItemData as $rowid => $item)
                                                    @php
                                                        $item = (array) $item;
                                                        $cart_images = json_decode($item['cart_images'] ?? '[]', true);

                                                        // Attribute IDs (provider vs standard)
                                                        if (!empty(json_decode($item['attribute_ids'], true)['provider_product_id'])) {
                                                            $attribute_ids = sina_options_map($item['attribute_ids']);
                                                        } else {
                                                            $rawAttributeIds = $item['attribute_ids'] ?? '[]';
                                                            $attribute_ids = is_array($rawAttributeIds)
                                                                ? $rawAttributeIds
                                                                : json_decode($rawAttributeIds, true);
                                                        }

                                                        // Support both JSON strings and already-decoded arrays
                                                        $product_size = is_array($item['product_size'] ?? null)
                                                            ? $item['product_size']
                                                            : json_decode($item['product_size'] ?? '[]', true);

                                                        $product_width_length = is_array($item['product_width_length'] ?? null)
                                                            ? $item['product_width_length']
                                                            : json_decode($item['product_width_length'] ?? '[]', true);

                                                        $page_product_width_length = is_array($item['page_product_width_length'] ?? null)
                                                            ? $item['page_product_width_length']
                                                            : json_decode($item['page_product_width_length'] ?? '[]', true);

                                                        $product_depth_length_width = is_array($item['product_depth_length_width'] ?? null)
                                                            ? $item['product_depth_length_width']
                                                            : json_decode($item['product_depth_length_width'] ?? '[]', true);

                                                        $votre_text = $item['votre_text'] ?? '';
                                                        $recto_verso = $item['recto_verso'] ?? '';
                                                        $recto_verso_french = $recto_verso;

                                                        $product_id = $item['product_id'] ?? null;
                                                        $imageurl = getProductImage($item['product_image'] ?? null);
                                                    @endphp
                                                    <tr>
                                                        <td class="product-thumbnail">
                                                            @if($product_id)
                                                                <a href="{{ url('Products/view/' . base64_encode($product_id)) }}" target="_blank">
                                                                    <img src="{{ $imageurl }}">
                                                                </a>
                                                            @else
                                                                <img src="{{ $imageurl }}">
                                                            @endif
                                                        </td>
                                                        <td class="product-name">
                                                            @if($product_id)
                                                                <a href="{{ url('Products/view/' . base64_encode($product_id)) }}" target="_blank">
                                                                    {{ $language_name == 'french'
                                                                        ? ucfirst($item['name_french'] ?? $item['name'] ?? '')
                                                                        : ucfirst($item['name'] ?? '') }}
                                                                </a>
                                                            @else
                                                                {{ $language_name == 'french'
                                                                    ? ucfirst($item['name_french'] ?? $item['name'] ?? '')
                                                                    : ucfirst($item['name'] ?? '') }}
                                                            @endif

                                                            <div class="product-name-detail">
                                                                <div class="row">
                                                                    {{-- product_width_length --}}
                                                                    @if (!empty($product_width_length))
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Longueur(pouces)' : 'Length(Inch)' }}: {{ $product_width_length['product_length'] ?? '' }}</strong></span>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Largeur (pouces)' : 'Width(Inch)' }}: {{ $product_width_length['product_width'] ?? '' }}</strong></span>
                                                                        </div>
                                                                        @if (!empty($product_width_length['length_width_color_show']))
                                                                            <div class="col-md-6">
                                                                                <span><strong>
                                                                                    @if ($language_name == 'french')
                                                                                        {{ 'Couleursv:' . ($product_width_length['length_width_color_french'] ?? '') }}
                                                                                    @else
                                                                                        {{ 'Colors:' . ($product_width_length['length_width_color'] ?? '') }}
                                                                                    @endif
                                                                                </strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if (!empty($product_width_length['product_total_page']))
                                                                            <div class="col-md-12 col-lg-12 col-xl-6">
                                                                                <span><strong>{{ $language_name == 'french' ? 'Quantité' : 'Quantity' }}: {{ $product_width_length['product_total_page'] ?? '' }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    {{-- product_depth_length_width --}}
                                                                    @if (!empty($product_depth_length_width))
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Longueur (pouces)' : 'Length(Inch)' }}: {{ $product_depth_length_width['product_depth_length'] ?? '' }}</strong></span>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Largeur (pouces)' : 'Width(Inch)' }}: {{ $product_depth_length_width['product_depth_width'] ?? '' }}</strong></span>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Profondeur (pouces)' : 'Depth(Inch)' }}: {{ $product_depth_length_width['product_depth'] ?? '' }}</strong></span>
                                                                        </div>
                                                                        @if (!empty($product_depth_length_width['depth_color_show']))
                                                                            <div class="col-md-12 col-lg-12 col-xl-6">
                                                                                <span><strong>
                                                                                    @if ($language_name == 'french')
                                                                                        {{ 'Couleursv:' . ($product_depth_length_width['depth_color_french'] ?? '') }}
                                                                                    @else
                                                                                        {{ 'Colors:' . ($product_depth_length_width['depth_color'] ?? '') }}
                                                                                    @endif
                                                                                </strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if (!empty($product_depth_length_width['product_depth_total_page']))
                                                                            <div class="col-md-12 col-lg-12 col-xl-6">
                                                                                <span><strong>{{ $language_name == 'french' ? 'Quantité' : 'Quantity' }}: {{ $product_depth_length_width['product_depth_total_page'] ?? '' }}</strong></span>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    {{-- page_product_width_length --}}
                                                                    @if (!empty($page_product_width_length))
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Longueur (pouces)' : 'Length(Inch)' }}: {{ $page_product_width_length['page_product_length'] ?? '' }}</strong></span>
                                                                        </div>
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Largeur(pouces)' : 'Width(Inch)' }}: {{ $page_product_width_length['page_product_width'] ?? '' }}</strong></span>
                                                                        </div>
                                                                        @if (!empty($page_product_width_length['page_length_width_color_show']))
                                                                            <div class="col-md-12 col-lg-12 col-xl-6">
                                                                                <span><strong>
                                                                                    @if ($language_name == 'french')
                                                                                        {{ 'Couleursv:' . ($page_product_width_length['page_length_width_color_french'] ?? '') }}
                                                                                    @else
                                                                                        {{ 'Colors:' . ($page_product_width_length['page_length_width_color'] ?? '') }}
                                                                                    @endif
                                                                                </strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if (!empty($page_product_width_length['page_product_total_page']))
                                                                            <div class="col-md-12 col-lg-12 col-xl-6">
                                                                                <span><strong>
                                                                                    @if ($language_name == 'french')
                                                                                        {{ 'Des pages:' . ($page_product_width_length['page_product_total_page_french'] ?? '') }}
                                                                                    @else
                                                                                        {{ 'Pages:' . ($page_product_width_length['page_product_total_page'] ?? '') }}
                                                                                    @endif
                                                                                </strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if (!empty($page_product_width_length['page_product_total_sheets']))
                                                                            <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                <span><strong>
                                                                                    @if ($language_name == 'french')
                                                                                        {{ 'Feuille par bloc:' . ($page_product_width_length['page_product_total_sheets_french'] ?? '') }}
                                                                                    @else
                                                                                        {{ 'Sheet Per Pad:' . ($page_product_width_length['page_product_total_sheets'] ?? '') }}
                                                                                    @endif
                                                                                </strong></span>
                                                                            </div>
                                                                        @endif
                                                                        @if (!empty($page_product_width_length['page_product_total_quantity']))
                                                                            <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                <span><strong>
                                                                                    {{ ($language_name == 'french' ? 'Quantité:' : 'Quantity:') . ($page_product_width_length['page_product_total_quantity'] ?? '') }}
                                                                                </strong></span>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    {{-- product_size + attributes --}}
                                                                    @if (!empty($product_size))
                                                                        @php
                                                                            if ($language_name == 'french') {
                                                                                $size_name = $product_size['product_size_french'] ?? '';
                                                                                $label_qty = $product_size['product_quantity_french'] ?? '';
                                                                            } else {
                                                                                $size_name = $product_size['product_size'] ?? '';
                                                                                $label_qty = $product_size['product_quantity'] ?? '';
                                                                            }
                                                                            $attribute = $product_size['attribute'] ?? '';
                                                                        @endphp

                                                                        @if ($label_qty)
                                                                            <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                <span><strong>{{ $language_name == 'french' ? 'Quantité' : 'Quantity' }} : {{ $label_qty }}</strong></span>
                                                                            </div>
                                                                        @endif

                                                                        @if ($size_name)
                                                                            <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                <span><strong>{{ $language_name == 'french' ? 'Taille' : 'Size' }}: {{ $size_name }}</strong></span>
                                                                            </div>
                                                                        @endif

                                                                        @if ($attribute)
                                                                            @foreach ($attribute as $akey => $aval)
                                                                                @php
                                                                                    $multiple_attribute_name = $aval['attributes_name'] ?? '';
                                                                                    $multiple_attribute_item_name = $aval['attributes_item_name'] ?? '';
                                                                                    if ($language_name == 'french') {
                                                                                        $multiple_attribute_name = $aval['attributes_name_french'] ?? $multiple_attribute_name;
                                                                                        $multiple_attribute_item_name = $aval['attributes_item_name_french'] ?? $multiple_attribute_item_name;
                                                                                    }
                                                                                @endphp
                                                                                <div class="col-md-12 col-lg-6 col-xl-6">
                                                                                    <span><strong>{{ $multiple_attribute_name . ':' . $multiple_attribute_item_name }}</strong></span>
                                                                                </div>
                                                                            @endforeach
                                                                        @endif
                                                                    @endif

                                                                    {{-- Expand attribute_ids (shared partial) --}}
                                                                    @include('products.expand_attribute_ids', ['attribute_ids' => $attribute_ids, 'language_name' => $language_name == 'french' ? 'French' : 'english'])

                                                                    {{-- Recto/Verso --}}
                                                                    @if (!empty($recto_verso))
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Recto verso:' . $recto_verso_french : 'Recto/Verso:' . $recto_verso }}</strong></span>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Votre text --}}
                                                                    @if (!empty($votre_text))
                                                                        <div class="col-md-12 col-lg-12 col-xl-6">
                                                                            <span><strong>{{ $language_name == 'french' ? 'Votre TEXTE - Votre TEXTE' : 'Your TEXT - Votre TEXT' }}: {{ $votre_text }}</strong></span>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            {{-- Uploaded files --}}
                                                            <div class="uploaded-file-detail" id="upload-file-data">
                                                                @if (!empty($cart_images))
                                                                    @foreach ($cart_images as $key => $return_arr)
                                                                        @php
                                                                            $return_arr = (array) $return_arr;
                                                                            $fileSrc = $return_arr['src'] ?? '';
                                                                            $fileName = $return_arr['name'] ?? '';
                                                                            $fileComment = $return_arr['cumment'] ?? '';
                                                                            // Prefer direct file_base_url if present, else fallback to download route (CI style)
                                                                            $fileBaseUrl = $return_arr['file_base_url'] ?? null;
                                                                            if ($fileBaseUrl) {
                                                                                $downloadUrl = $fileBaseUrl;
                                                                            } else {
                                                                                $location = $return_arr['location'] ?? '';
                                                                                $downloadUrl = url('MyOrders/download/' . urlencode($location) . '/' . urlencode($fileName));
                                                                            }
                                                                        @endphp
                                                                        <div class="uploaded-file-single" id="teb-{{ $return_arr['skey'] ?? $key }}">
                                                                            <div class="uploaded-file-single-inner">
                                                                                @if(!empty($fileSrc))
                                                                                    <div class="uploaded-file-img" style="background-image: url({{ $fileSrc }})"></div>
                                                                                @endif
                                                                                <div class="uploaded-file-info">
                                                                        <div class="uploaded-file-name">
                                                                            <span><?= $return_arr['name'] ?></span>
                                                                        </div>
                                                                        <div class="upload-field">
                                                                            <?php $link = $BASE_URL . "MyOrders/download/" . urlencode($return_arr['location']) . "/" . urlencode($return_arr['name']); ?><br>

                                                                            <div class="uploaded-file-info">
                                                                                <a href="<?= $link ?>">
                                                                                    <i class="fa fas fa-file-download"></i>
                                                                                    <?= ($language_name == 'French') ? 'Télécharger' : 'Download' ?>
                                                                                </a>

                                                                                <?php if (!empty($return_arr['cumment'])) { ?>
                                                                                    <div class="upload-field">
                                                                                        <?= ($language_name == 'French') ? 'Commentaire' : 'Comment' ?> : <?= $return_arr['cumment'] ?>
                                                                                    </div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="product-price1">
                                                            <span>{{ $order_currency_currency_symbol . number_format($item['price'] ?? 0, 2) }}</span>
                                                        </td>
                                                        <td class="quant-cart text-left">
                                                            {{ $item['quantity'] ?? 0 }}
                                                        </td>
                                                        <td class="product-subtotal">
                                                            @php
                                                                $subtotal = ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
                                                            @endphp
                                                            <span>{{ $order_currency_currency_symbol . number_format($subtotal, 2) }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td colspan="5" class="text-right">
                                                        <div class="cart-total">
                                                            <span>
                                                                {{ $language_name == 'french' ? 'Montant sous-total' : 'Subtotal Amount' }}:
                                                                <font class="cart-sub-total">
                                                                    {{ $order_currency_currency_symbol . number_format($orderData->sub_total_amount, 2) }}
                                                                </font>
                                                            </span>
                                                        </div>

                                                        @if (!empty($orderData->preffered_customer_discount) && $orderData->preffered_customer_discount != '0.00')
                                                            <div class="cart-total">
                                                                <span>
                                                                    {{ $language_name == 'french' ? 'Remise client privilégiée' : 'Preffered Customer Discount' }}:
                                                                    <font class="cart-sub-total">
                                                                        {{ '-' . $order_currency_currency_symbol . number_format($orderData->preffered_customer_discount, 2) }}
                                                                    </font>
                                                                </span>
                                                            </div>
                                                        @endif

                                                        @if (!empty($orderData->coupon_discount_amount) && $orderData->coupon_discount_amount != '0.00')
                                                            <div class="cart-total">
                                                                <span>
                                                                    {{ $language_name == 'french' ? 'Remise de coupon' : 'Coupon Discount' }}:
                                                                    <font class="cart-sub-total">
                                                                        {{ '-' . $order_currency_currency_symbol . number_format($orderData->coupon_discount_amount, 2) }}
                                                                    </font>
                                                                </span>
                                                            </div>
                                                        @endif

                                                        <div class="cart-total">
                                                            <span>
                                                                {{ $language_name == 'french' ? "Frais d'expédition" : 'Shipping Fee' }} :
                                                                <font class="cart-sub-total">
                                                                    {{ $order_currency_currency_symbol . number_format($orderData->delivery_charge, 2) }}
                                                                </font>
                                                            </span>
                                                        </div>

                                                        @if (!empty($orderData->total_sales_tax) && $orderData->total_sales_tax != '0.00' && !empty($salesTaxRatesProvinces_Data))
                                                            @php
                                                                $rate = (array) $salesTaxRatesProvinces_Data;
                                                            @endphp
                                                            <div class="cart-total">
                                                                <span>
                                                                    {{ 'Total ' . ($rate['type'] ?? '') . ' ' . number_format($rate['total_tax_rate'] ?? 0, 2) . '%' }}:
                                                                    <font class="cart-sub-total">
                                                                        {{ $order_currency_currency_symbol . number_format($orderData->total_sales_tax, 2) }}
                                                                    </font>
                                                                </span>
                                                            </div>
                                                        @endif

                                                        <div class="cart-total">
                                                            <span>
                                                                {{ $language_name == 'french' ? 'Montant total de la commande' : 'Order Total Amount' }}:
                                                                <font class="cart-sub-total">
                                                                    {{ $order_currency_currency_symbol . number_format($orderData->total_amount, 2) }}
                                                                </font>
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
@endsection

