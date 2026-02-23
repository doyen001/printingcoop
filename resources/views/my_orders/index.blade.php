{{-- CI: application/views/MyOrders/index.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'My Orders')

@section('content')
<div class="account-section universal-spacing universal-bg-white">
    <div class="container">
        <div class="account-section-inner">
            @include('elements.my-account-menu')
            
            <div class="account-area">
                <div class="universal-dark-title">
                    <span>
                        {{ $language_name == 'french' ? 'Vos commandes' : 'Your Orders' }}
                    </span>
                </div>
                
                <div class="text-center" style="color:red">
                    {{ session('message_error') }}
                </div>
                <div class="text-center" style="color:green">
                    {{ session('message_success') }}
                </div><br>
                
                <div class="order-display-section">
                    @if(!empty($orders) && count($orders) > 0)
                        @foreach($orders as $order)
                            <div class="single-order-display">
                                <div class="email-field1">
                                    <div class="row align-items-center">
                                        <div class="col-7 col-md-4 col-lg-3 col-xl-3">
                                            <div class="order-id">
                                                <button type="submit">{{ $order->order_id ?? 'N/A' }}</button>
                                            </div>
                                        </div>
                                        <div class="col-5 col-md-3 col-lg-3 col-xl-3">
                                            <div class="status-btn">
                                                {{-- Order status badge --}}
                                                @php
                                                    $statusClass = '';
                                                    $statusText = '';
                                                    switch($order->status ?? 0) {
                                                        case 1:
                                                            $statusClass = 'pending';
                                                            $statusText = $language_name == 'french' ? 'En attente' : 'Pending';
                                                            break;
                                                        case 2:
                                                            $statusClass = 'processing';
                                                            $statusText = $language_name == 'french' ? 'Traitement' : 'Processing';
                                                            break;
                                                        case 3:
                                                            $statusClass = 'shipped';
                                                            $statusText = $language_name == 'french' ? 'Expédié' : 'Shipped';
                                                            break;
                                                        case 4:
                                                            $statusClass = 'delivered';
                                                            $statusText = $language_name == 'french' ? 'Livré' : 'Delivered';
                                                            break;
                                                        case 5:
                                                            $statusClass = 'completed';
                                                            $statusText = $language_name == 'french' ? 'Complété' : 'Completed';
                                                            break;
                                                        case 6:
                                                            $statusClass = 'cancelled';
                                                            $statusText = $language_name == 'french' ? 'Annulé' : 'Cancelled';
                                                            break;
                                                        default:
                                                            $statusClass = 'pending';
                                                            $statusText = $language_name == 'french' ? 'En attente' : 'Pending';
                                                    }
                                                @endphp
                                                <span class="status-{{ $statusClass }}">{{ $statusText }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12 col-md-5 col-lg-6 col-xl-6 text-right">
                                            <div class="order-id-button">
                                                <a href="{{ url('MyOrders/view/' . base64_encode($order->id)) }}">
                                                    <button class="view-details-btn" type="button">
                                                        {{ $language_name == 'french' ? "Voir l'ordre" : 'View Order' }}
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="email-field1">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-sm-7 col-md-7">
                                            <div class="order-id">
                                                <span>
                                                    {{ $language_name == 'french' ? 'Commandé le' : 'Ordered On' }}
                                                    <strong>{{ $order->created ? date('M d, Y', strtotime($order->created)) : 'N/A' }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-5 col-md-5 text-right">
                                            <div class="order-id">
                                                <span>
                                                    {{ $language_name == 'french' ? "Total de la commande" : 'Order Total' }}:
                                                    <strong>
                                                        ${{ number_format($order->total_amount ?? 0, 2) }}
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center">
                            <h2 class="lead">{{ $language_name == 'french' ? "L'historique des commandes est vide" : 'Order History Is Empty' }}</h2>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
