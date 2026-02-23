{{-- 
    Order detail page (replicate CI MyOrders/view.php)
    
    Available variables:
    - $orderData: Order data array
    - $OrderItemData: Order items array
    - $cityData, $stateData, $countryData: Address data
    - $salesTaxRatesProvinces_Data: Tax information
--}}

@extends('elements.app')

@section('content')
<div class="order-detail-page">
    {{-- Page Header --}}
    <div class="page-header">
        <h1>{{ $page_title }}</h1>
        <div class="order-actions">
            <a href="{{ url('MyOrders') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ config('store.language_name') == 'French' ? 'Retour aux commandes' : 'Back to Orders' }}
            </a>
            <a href="{{ url('MyOrders/downloadOrderPdf/' . $orderData['id'] . '/invoice') }}" class="btn btn-primary">
                <i class="fas fa-download"></i> {{ config('store.language_name') == 'French' ? 'Télécharger la facture' : 'Download Invoice' }}
            </a>
            <a href="{{ url('MyOrders/downloadOrderPdf/' . $orderData['id'] . '/order') }}" class="btn btn-primary">
                <i class="fas fa-download"></i> {{ config('store.language_name') == 'French' ? 'Télécharger la commande' : 'Download Order' }}
            </a>
        </div>
    </div>
    
    {{-- Order Information --}}
    <div class="row">
        <div class="col-md-8">
            {{-- Order Summary --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3>{{ config('store.language_name') == 'French' ? 'Résumé de la commande' : 'Order Summary' }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>{{ config('store.language_name') == 'French' ? 'N° de commande' : 'Order ID' }}:</strong> {{ $orderData['order_id'] }}</p>
                            <p><strong>{{ config('store.language_name') == 'French' ? 'Date' : 'Date' }}:</strong> {{ date('M d, Y H:i', strtotime($orderData['created'])) }}</p>
                            <p><strong>{{ config('store.language_name') == 'French' ? 'Statut' : 'Status' }}:</strong> 
                                @php
                                    $statusLabels = [
                                        1 => config('store.language_name') == 'French' ? 'En attente' : 'Pending',
                                        2 => config('store.language_name') == 'French' ? 'Confirmé' : 'Confirmed',
                                        3 => config('store.language_name') == 'French' ? 'En cours' : 'Processing',
                                        4 => config('store.language_name') == 'French' ? 'Expédié' : 'Shipped',
                                        5 => config('store.language_name') == 'French' ? 'Livré' : 'Delivered',
                                        6 => config('store.language_name') == 'French' ? 'Annulé' : 'Cancelled',
                                        7 => config('store.language_name') == 'French' ? 'Échoué' : 'Failed',
                                    ];
                                    $statusClasses = [
                                        1 => 'warning', 2 => 'success', 3 => 'info',
                                        4 => 'primary', 5 => 'success', 6 => 'danger', 7 => 'danger',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusClasses[$orderData['status']] ?? 'secondary' }}">
                                    {{ $statusLabels[$orderData['status']] ?? 'Unknown' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>{{ config('store.language_name') == 'French' ? 'Méthode de paiement' : 'Payment Method' }}:</strong> {{ strtoupper($orderData['payment_method'] ?? 'N/A') }}</p>
                            <p><strong>{{ config('store.language_name') == 'French' ? 'Statut de paiement' : 'Payment Status' }}:</strong> 
                                @php
                                    $paymentLabels = [
                                        1 => config('store.language_name') == 'French' ? 'En attente' : 'Pending',
                                        2 => config('store.language_name') == 'French' ? 'Payé' : 'Paid',
                                        3 => config('store.language_name') == 'French' ? 'Échoué' : 'Failed',
                                    ];
                                @endphp
                                <span class="badge badge-{{ $orderData['payment_status'] == 2 ? 'success' : ($orderData['payment_status'] == 3 ? 'danger' : 'warning') }}">
                                    {{ $paymentLabels[$orderData['payment_status']] ?? 'Unknown' }}
                                </span>
                            </p>
                            @if(!empty($orderData['transition_id']))
                            <p><strong>{{ config('store.language_name') == 'French' ? 'ID de transaction' : 'Transaction ID' }}:</strong> {{ $orderData['transition_id'] }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Order Items --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3>{{ config('store.language_name') == 'French' ? 'Articles commandés' : 'Order Items' }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ config('store.language_name') == 'French' ? 'Produit' : 'Product' }}</th>
                                    <th>{{ config('store.language_name') == 'French' ? 'Prix' : 'Price' }}</th>
                                    <th>{{ config('store.language_name') == 'French' ? 'Quantité' : 'Quantity' }}</th>
                                    <th>{{ config('store.language_name') == 'French' ? 'Sous-total' : 'Subtotal' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($OrderItemData as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if(!empty($item['product_image']))
                                            <img src="{{ url('uploads/products/small/' . $item['product_image']) }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="mr-3" 
                                                 style="width: 50px;">
                                            @endif
                                            <div>
                                                <strong>{{ config('store.language_name') == 'French' ? $item['name_french'] : $item['name'] }}</strong>
                                                @if(!empty($item['code']))
                                                <br><small>{{ config('store.language_name') == 'French' ? 'Code' : 'Code' }}: {{ $item['code'] }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ config('store.product_price_currency_symbol') }}{{ number_format($item['price'], 2) }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>{{ config('store.product_price_currency_symbol') }}{{ number_format($item['subtotal'], 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            {{-- Billing Address --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3>{{ config('store.language_name') == 'French' ? 'Adresse de facturation' : 'Billing Address' }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>{{ $orderData['billing_name'] }}</strong></p>
                    @if(!empty($orderData['billing_company']))
                    <p>{{ $orderData['billing_company'] }}</p>
                    @endif
                    <p>{{ $orderData['billing_address'] }}</p>
                    @if(!empty($orderData['billing_landmark']))
                    <p>{{ $orderData['billing_landmark'] }}</p>
                    @endif
                    <p>{{ $cityData['name'] ?? '' }}, {{ $stateData['name'] ?? '' }} {{ $orderData['billing_pin_code'] }}</p>
                    <p>{{ $countryData['name'] ?? '' }}</p>
                    <p>{{ config('store.language_name') == 'French' ? 'Tél' : 'Phone' }}: {{ $orderData['billing_mobile'] }}</p>
                    @if(!empty($orderData['billing_alternate_phone']))
                    <p>{{ config('store.language_name') == 'French' ? 'Tél alternatif' : 'Alt Phone' }}: {{ $orderData['billing_alternate_phone'] }}</p>
                    @endif
                </div>
            </div>
            
            {{-- Shipping Address --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3>{{ config('store.language_name') == 'French' ? 'Adresse de livraison' : 'Shipping Address' }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>{{ $orderData['shipping_name'] }}</strong></p>
                    @if(!empty($orderData['shipping_company']))
                    <p>{{ $orderData['shipping_company'] }}</p>
                    @endif
                    <p>{{ $orderData['shipping_address'] }}</p>
                    @if(!empty($orderData['shipping_landmark']))
                    <p>{{ $orderData['shipping_landmark'] }}</p>
                    @endif
                    <p>{{ $orderData['shipping_city'] }}, {{ $orderData['shipping_state'] }} {{ $orderData['shipping_pin_code'] }}</p>
                    <p>{{ $orderData['shipping_country'] }}</p>
                    <p>{{ config('store.language_name') == 'French' ? 'Tél' : 'Phone' }}: {{ $orderData['shipping_mobile'] }}</p>
                </div>
            </div>
            
            {{-- Order Totals --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h3>{{ config('store.language_name') == 'French' ? 'Totaux' : 'Order Totals' }}</h3>
                </div>
                <div class="card-body">
                    <div class="order-totals">
                        <div class="total-row">
                            <span>{{ config('store.language_name') == 'French' ? 'Sous-total' : 'Subtotal' }}:</span>
                            <span>{{ config('store.product_price_currency_symbol') }}{{ number_format($orderData['sub_total_amount'], 2) }}</span>
                        </div>
                        
                        @if($orderData['preffered_customer_discount'] > 0)
                        <div class="total-row discount">
                            <span>{{ config('store.language_name') == 'French' ? 'Remise client préféré' : 'Preferred Customer Discount' }}:</span>
                            <span>-{{ config('store.product_price_currency_symbol') }}{{ number_format($orderData['preffered_customer_discount'], 2) }}</span>
                        </div>
                        @endif
                        
                        @if($orderData['coupon_discount_amount'] > 0)
                        <div class="total-row discount">
                            <span>{{ config('store.language_name') == 'French' ? 'Coupon' : 'Coupon' }} ({{ $orderData['coupon_code'] }}):</span>
                            <span>-{{ config('store.product_price_currency_symbol') }}{{ number_format($orderData['coupon_discount_amount'], 2) }}</span>
                        </div>
                        @endif
                        
                        @if($orderData['total_sales_tax'] > 0)
                        <div class="total-row">
                            <span>{{ config('store.language_name') == 'French' ? 'Taxe de vente' : 'Sales Tax' }} ({{ $salesTaxRatesProvinces_Data['total_tax_rate'] ?? 0 }}%):</span>
                            <span>{{ config('store.product_price_currency_symbol') }}{{ number_format($orderData['total_sales_tax'], 2) }}</span>
                        </div>
                        @endif
                        
                        @if($orderData['delivery_charge'] > 0)
                        <div class="total-row">
                            <span>{{ config('store.language_name') == 'French' ? 'Frais de livraison' : 'Shipping' }}:</span>
                            <span>{{ config('store.product_price_currency_symbol') }}{{ number_format($orderData['delivery_charge'], 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="total-row total">
                            <span><strong>{{ config('store.language_name') == 'French' ? 'Total' : 'Total' }}:</strong></span>
                            <span><strong>{{ config('store.product_price_currency_symbol') }}{{ number_format($orderData['total_amount'], 2) }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Order Comment --}}
            @if(!empty($orderData['order_comment']))
            <div class="card mb-4">
                <div class="card-header">
                    <h3>{{ config('store.language_name') == 'French' ? 'Commentaire' : 'Order Comment' }}</h3>
                </div>
                <div class="card-body">
                    <p>{{ $orderData['order_comment'] }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.order-totals .total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #eee;
}

.order-totals .total-row.total {
    border-top: 2px solid #333;
    border-bottom: none;
    margin-top: 10px;
    padding-top: 10px;
    font-size: 1.2em;
}

.order-totals .total-row.discount {
    color: #28a745;
}
</style>
@endsection
