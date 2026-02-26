{{-- CI: application/views/MyOrders/index.php --}}
@extends('elements.app')

@section('title', $page_title ?? 'My Orders')

@section('content')
<style>
    /* Simple and Clean My Orders Styles */
    .account-section {
        padding: 60px 0;
        background: #f8f9fa;
        min-height: 100vh;
    }

    .account-section-inner {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 30px;
        margin-bottom: 30px;
    }

    /* Title Styling */
    .universal-dark-title {
        margin-bottom: 30px;
        text-align: center;
    }

    .universal-dark-title span {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        position: relative;
        display: inline-block;
    }

    .universal-dark-title span::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background: #f28738;
        border-radius: 2px;
    }

    /* Message Styling */
    /* .text-center[style*="color:red"] {
        background: #f8d7da;
        color: #721c24;
        padding: 12px 20px;
        border-radius: 6px;
        border: 1px solid #f5c6cb;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .text-center[style*="color:green"] {
        background: #d4edda;
        color: #155724;
        padding: 12px 20px;
        border-radius: 6px;
        border: 1px solid #c3e6cb;
        margin-bottom: 20px;
        font-weight: 500;
    } */

    /* Order Display Section */
    .order-display-section {
        margin-top: 20px;
    }

    .single-order-display {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .single-order-display:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    /* Email Field Styling */
    .email-field1 {
        margin-bottom: 15px;
    }

    .email-field1:last-child {
        margin-bottom: 0;
    }

    /* Order ID Button */
    .order-id button {
        background: #2c3e50;
        color: #ffffff;
        border: none;
        padding: 10px 16px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .order-id button:hover {
        background: #34495e;
        transform: translateY(-1px);
    }

    /* Status Badge */
    .status-btn {
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .status-pending {
        background: #ffc107;
        color: #212529;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-processing {
        background: #17a2b8;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-shipped {
        background: #007bff;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-delivered {
        background: #28a745;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-completed {
        background: #6f42c1;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-cancelled {
        background: #dc3545;
        color: #ffffff;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* View Details Button */
    .view-details-btn {
        background: #f28738;
        color: #ffffff;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .view-details-btn:hover {
        background: #e67628;
        transform: translateY(-1px);
        text-decoration: none;
        color: #ffffff;
    }

    .order-id-button a {
        text-decoration: none;
    }

    /* Order Info Text */
    .order-id span {
        color: #6c757d;
        font-size: 14px;
        line-height: 1.5;
    }

    .order-id span strong {
        color: #2c3e50;
        font-weight: 700;
    }

    /* Empty State */
    .text-center h2.lead {
        color: #6c757d;
        font-size: 24px;
        margin: 60px 0;
        font-weight: 500;
    }

    /* Responsive Design */
    @media (max-width: 991px) {
        .account-section {
            padding: 40px 0;
        }

        .account-section-inner {
            padding: 20px;
        }

        .universal-dark-title span {
            font-size: 1.8rem;
        }

        .single-order-display {
            padding: 15px;
        }
    }

    @media (max-width: 767px) {
        .account-section {
            padding: 20px 0;
        }

        .account-section-inner {
            padding: 15px;
            border-radius: 8px;
        }

        .universal-dark-title span {
            font-size: 1.6rem;
        }

        .single-order-display {
            padding: 15px;
        }

        .order-id button,
        .view-details-btn {
            width: 100%;
            margin-bottom: 10px;
        }

        .status-btn {
            justify-content: center;
            margin-bottom: 10px;
        }

        .text-right {
            text-align: center !important;
        }
    }

    @media (max-width: 480px) {
        .universal-dark-title span {
            font-size: 1.4rem;
        }

        .single-order-display {
            padding: 12px;
        }

        .order-id span {
            font-size: 13px;
        }
    }
</style>

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
