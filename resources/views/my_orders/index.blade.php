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
        background: #f28738;
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
                    @if(!empty($orderData))
                        @foreach($orderData as $list)
                            @php
                                $currency_id = $list['currency_id'] ?? 1;
                                $OrderCurrencyData = $CurrencyList[$currency_id] ?? [];
                                $order_currency_currency_symbol = $OrderCurrencyData['symbols'] ?? '$';
                            @endphp
                            <div class="single-order-display">

                                <div class="email-field1">
                                    <div class="row align-items-center">
                                        <div class="col-7 col-md-4 col-lg-3 col-xl-3">
                                            <div class="order-id">
                                                <button type="submit">{{ $list['order_id'] }}</button>
                                            </div>
                                        </div>
                                        <div class="col-5 col-md-3 col-lg-3 col-xl-3">
                                            <div class="status-btn">
                                                {!! $language_name == 'french' ? getOrderSatusClassFrench($list['status']) : getOrderSatusClass($list['status']) !!}
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-5 col-lg-6 col-xl-6 text-right">
                                            <div class="order-id-button">
                                                @if(in_array($list['status'], [6, 7, 8]))
                                                    <a href="{{ url('MyOrders/deleteOrder/' . base64_encode($list['id'])) }}"
                                                        onclick="return confirm('Are you sure you want to delete this order?');">
                                                        <button type="button" class="view-details-btn">
                                                            {{ $language_name == 'french' ? 'supprimer' : 'delete' }}
                                                        </button>
                                                    </a>
                                                @endif
                                                @if(in_array($list['status'], [2, 3, 4]))
                                                    <a href="javascript:void(0)" onclick="changeOrderStatus('{{ $list['id'] }}',6)">
                                                        <button type="submit" class="view-details-btn">
                                                            {{ $language_name == 'french' ? 'Annuler' : 'cancel' }}
                                                        </button>
                                                    </a>
                                                @endif
                                                <a href="{{ url('MyOrders/view/' . base64_encode($list['id'])) }}">
                                                    <button class="view-details-btn" type="button">
                                                        {{ $language_name == 'french' ? "Voir l'ordre" : 'View Order' }}
                                                    </button>
                                                </a>
                                                @php
                                                    if ($language_name == 'french') {
                                                        $file_name = $list['order_id'] . "-fr-invoice.pdf";
                                                        $file_name = strtolower($file_name);
                                                        $InvoiceText = 'Facture Pdf';
                                                    } else {
                                                        $file_name = $list['order_id'] . "-invoice.pdf";
                                                        $file_name = strtolower($file_name);
                                                        $InvoiceText = 'Invoice Pdf';
                                                    }
                                                    $linkInvoice = url('MyOrders/downloadOrderPdf/' . $list['id'] . '/invoice');
                                                @endphp

                                                <a href="{{ $linkInvoice }}">
                                                    <button class="view-details-btn" type="button">
                                                        <i class="fa fas fa-file-download"></i> {{ $InvoiceText }}
                                                    </button>
                                                </a>
                                                @php
                                                    if ($language_name == 'french') {
                                                        $file_name = $list['order_id'] . "-fr-order.pdf";
                                                        $file_name = strtolower($file_name);
                                                        $OrderText = 'Commander Pdf';
                                                    } else {
                                                        $file_name = $list['order_id'] . "-order.pdf";
                                                        $file_name = strtolower($file_name);
                                                        $OrderText = 'Order Pdf';
                                                    }
                                                    $linkOrder = url('MyOrders/downloadOrderPdf/' . $list['id'] . '/order');
                                                @endphp
                                                <a href="{{ $linkOrder }}">
                                                    <button class="view-details-btn" type="button">
                                                        <i class="fa fas fa-file-download"></i> {{ $OrderText }}
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="cart-product-display">
                                    <table>
                                        <tbody>
                                            @foreach($list['OrderItem'] as $item)
                                            <tr>
                                                <td style="width: 80px;">
                                                    <div class="cart-product-img">
                                                        <a href="{{ url('Products/view/' . base64_encode($item['product_id'])) }}">
                                                            @php
                                                                $imageurl = getProductImage($item['product_image']);
                                                                $personailise = $item['personailise'];
                                                                $personailise_image = $item['personailise_image'];
                                                                $Personalised = 'Unpersonalised';
                                                                if ($personailise == 1 && !empty($personailise_image)) {
                                                                    $Personalised = 'Personalised';
                                                                    $imageurl = asset('uploads/personailise/' . $personailise_image);
                                                                }
                                                            @endphp
                                                            <img src="{{ $imageurl }}">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="cart-product-desc">
                                                        <div class="cart-product-title">
                                                            <a href="{{ url('Products/view/' . base64_encode($item['product_id'])) }}">
                                                                <span>
                                                                    {{ $language_name == 'french' ? $item['name_french'] : $item['name'] }}
                                                                </span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="cart-product-price">
                                                        <span>{{ $item['quantity'] }}</span>X<span>{{ $order_currency_currency_symbol . number_format($item['price'], 2) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="email-text1">
                                                        <div class="cart-product-price">
                                                            <span>{{ $order_currency_currency_symbol . number_format($item['subtotal'], 2) }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="email-field1">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-sm-7 col-md-7">
                                            <div class="order-id">
                                                <span>
                                                    {{ $language_name == 'french' ? 'Commandé le' : 'Ordered On' }}
                                                    <strong>{{ dateFormate($list['created']) }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-5 col-md-5 text-right">
                                            <div class="order-id">
                                                <span>
                                                    {{ $language_name == 'french' ? 'Sous-total' : 'Sub Total' }}:
                                                    <strong>
                                                        {{ $order_currency_currency_symbol . number_format($list['sub_total_amount'], 2) }}
                                                    </strong>
                                                </span>
                                            </div>
                                            @if(!empty($list['preffered_customer_discount']) && $list['preffered_customer_discount'] != "0.00")
                                                <div class="order-id">
                                                    <span>
                                                        {{ $language_name == 'french' ? 'Remise client privilégiée' : 'Preffered Customer Discount' }}:
                                                        <strong>
                                                            {{ '-' . $order_currency_currency_symbol . number_format($list['preffered_customer_discount'], 2) }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            @endif
                                            @if(!empty($list['coupon_discount_amount']) && $list['coupon_discount_amount'] != "0.00")
                                                <div class="order-id">
                                                    <span>
                                                        {{ $language_name == 'french' ? 'Remise du coupon' : 'Coupon Discount' }}:
                                                        <strong>
                                                            {{ '-' . $order_currency_currency_symbol . number_format($list['coupon_discount_amount'], 2) }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="order-id">
                                                <span>
                                                    {{ $language_name == 'french' ? "Frais d'expédition" : 'Shipping Fee' }}:
                                                    <strong>
                                                        {{ $order_currency_currency_symbol . number_format($list['delivery_charge'], 2) }}
                                                    </strong>
                                                </span>
                                            </div>
                                            @if(!empty($list['total_sales_tax']) && $list['total_sales_tax'] != '0.00')
                                                @php
                                                    // Note: You'll need to implement this helper function in Laravel
                                                    // $salesTaxRatesProvinces_Data = getSalesTaxRatesProvincesById($list['billing_state']);
                                                    $taxType = 'Tax'; // Default fallback
                                                    $taxRate = 0; // Default fallback
                                                @endphp
                                                <div class="order-id">
                                                    <span>
                                                        Total {{ $taxType }} {{ number_format($taxRate, 2) }}%:
                                                        <strong>
                                                            {{ $order_currency_currency_symbol . number_format($list['total_sales_tax'], 2) }}
                                                        </strong>
                                                    </span>
                                                </div>
                                            @endif

                                            <div class="order-id">
                                                <span>{{ $language_name == 'french' ? "Total de la commande" : 'Order Total' }}:
                                                    <strong>
                                                        {{ $order_currency_currency_symbol . number_format($list['total_amount'], 2) }}
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

<!-- Cancellation Modal (matches CI MyOrders/index.php) -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $language_name == 'french' ? "Raison de l'annulation" : 'Cancellation Reason' }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form class="form-horizontal" name="commentform" method="post" action="" id="changeOrderStatusForm">
                <input type="hidden" name="order_id" id="cl_order_id">
                <input type="hidden" name="status" id="cl_status">
                
                <div class="modal-body">
                    <div id="MsgError"></div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="InputMessage" class="col-lg-12 control-label">{{ $language_name == 'french' ? 'Raison' : 'Reason' }}</label>
                            <div class="col-lg-12">
                                <textarea name="mobileMsg" id="mobileMsg" class="form-control" rows="5" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit">{{ $language_name == 'french' ? 'Soumettre' : 'Submit' }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Change order status function (matches CI assets/js/custom.min.js)
function changeOrderStatus(order_id, status) {
    $("#mobileMsg").html("");
    $("#cl_order_id").val(order_id);
    $("#cl_status").val(status);
    $("#myModal").modal("show");
}

// Handle form submission (matches CI assets/js/custom.min.js)
$(document).ready(function() {
    $("#changeOrderStatusForm").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        $("#btnSubmit").attr("disabled", true);
        
        $.ajax({
            type: "POST",
            url: "{{ url('MyOrders/changeOrderStatus') }}",
            data: form.serialize(),
            success: function(response) {
                $("#myModal").modal("hide");
                $("#btnSubmit").attr("disabled", false);
                
                var result = JSON.parse(response);
                if (result.status == 1) {
                    // Reload page to show updated status
                    location.reload();
                } else {
                    $("#MsgError").html('<div class="alert alert-danger">' + result.msg + '</div>');
                }
            },
            error: function() {
                $("#myModal").modal("hide");
                $("#btnSubmit").attr("disabled", false);
                $("#MsgError").html('<div class="alert alert-danger">Error occurred. Please try again.</div>');
            }
        });
    });
});
</script>
@endpush
