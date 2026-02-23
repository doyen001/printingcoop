{{-- 
    Order listing page (replicate CI MyOrders/index.php)
    
    Available variables:
    - $orderData: Array of user's orders
--}}

@extends('elements.app')

@section('content')
<div class="my-orders-page">
    {{-- Page Header --}}
    <div class="page-header">
        <h1>{{ $page_title }}</h1>
    </div>
    
    {{-- Flash Messages --}}
    @if(session('message_success'))
    <div class="alert alert-success">
        {{ session('message_success') }}
    </div>
    @endif
    
    @if(session('message_error'))
    <div class="alert alert-danger">
        {{ session('message_error') }}
    </div>
    @endif
    
    {{-- Orders Table --}}
    <div class="orders-list">
        @if(count($orderData) > 0)
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ config('store.language_name') == 'French' ? 'N° de commande' : 'Order ID' }}</th>
                        <th>{{ config('store.language_name') == 'French' ? 'Date' : 'Date' }}</th>
                        <th>{{ config('store.language_name') == 'French' ? 'Total' : 'Total' }}</th>
                        <th>{{ config('store.language_name') == 'French' ? 'Statut' : 'Status' }}</th>
                        <th>{{ config('store.language_name') == 'French' ? 'Paiement' : 'Payment' }}</th>
                        <th>{{ config('store.language_name') == 'French' ? 'Actions' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderData as $order)
                    <tr>
                        <td>
                            <a href="{{ url('MyOrders/view/' . base64_encode($order['id'])) }}">
                                {{ $order['order_id'] }}
                            </a>
                        </td>
                        <td>{{ date('M d, Y', strtotime($order['created'])) }}</td>
                        <td>{{ config('store.product_price_currency_symbol') }}{{ number_format($order['total_amount'], 2) }}</td>
                        <td>
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
                                    1 => 'warning',
                                    2 => 'success',
                                    3 => 'info',
                                    4 => 'primary',
                                    5 => 'success',
                                    6 => 'danger',
                                    7 => 'danger',
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusClasses[$order['status']] ?? 'secondary' }}">
                                {{ $statusLabels[$order['status']] ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $paymentLabels = [
                                    1 => config('store.language_name') == 'French' ? 'En attente' : 'Pending',
                                    2 => config('store.language_name') == 'French' ? 'Payé' : 'Paid',
                                    3 => config('store.language_name') == 'French' ? 'Échoué' : 'Failed',
                                ];
                            @endphp
                            <span class="badge badge-{{ $order['payment_status'] == 2 ? 'success' : ($order['payment_status'] == 3 ? 'danger' : 'warning') }}">
                                {{ $paymentLabels[$order['payment_status']] ?? 'Unknown' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ url('MyOrders/view/' . base64_encode($order['id'])) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> {{ config('store.language_name') == 'French' ? 'Voir' : 'View' }}
                            </a>
                            
                            @if($order['status'] != 6)
                            <button type="button" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="cancelOrder({{ $order['id'] }})">
                                <i class="fas fa-times"></i> {{ config('store.language_name') == 'French' ? 'Annuler' : 'Cancel' }}
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info">
            {{ config('store.language_name') == 'French' ? 'Aucune commande trouvée.' : 'No orders found.' }}
        </div>
        @endif
    </div>
</div>

{{-- Cancel Order Modal --}}
<div class="modal fade" id="cancelOrderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ config('store.language_name') == 'French' ? 'Annuler la commande' : 'Cancel Order' }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="cancelOrderForm">
                    @csrf
                    <input type="hidden" name="order_id" id="cancel_order_id">
                    <input type="hidden" name="status" value="6">
                    
                    <div class="form-group">
                        <label>{{ config('store.language_name') == 'French' ? 'Raison de l\'annulation' : 'Cancellation Reason' }}</label>
                        <textarea name="mobileMsg" class="form-control" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ config('store.language_name') == 'French' ? 'Fermer' : 'Close' }}
                </button>
                <button type="button" class="btn btn-danger" onclick="submitCancelOrder()">
                    {{ config('store.language_name') == 'French' ? 'Confirmer l\'annulation' : 'Confirm Cancellation' }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    document.getElementById('cancel_order_id').value = orderId;
    $('#cancelOrderModal').modal('show');
}

function submitCancelOrder() {
    const formData = new FormData(document.getElementById('cancelOrderForm'));
    
    fetch('{{ url("MyOrders/changeOrderStatus") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status == 1) {
            alert(data.msg);
            location.reload();
        } else {
            alert(data.msg);
        }
    });
}
</script>
@endsection
