@extends('elements.app')

@section('content')
<div class="product-title-section">
    <div class="product-title-section-img">
    </div>
</div>
<div class="container-fluid checkout-main-section">
    <div class="container p-0" style="margin-bottom: 200px; margin-top:100px;">
        <div class="checkout-section">
            <div class="row">
                <div class="col-md-12">
                    <div class="checkout-section-box">
                        <div class="text-center" style="color:red">
                            {{ session('message_error') }}
                        </div>
                        <div class="text-center" style="color:green">
                            {{ session('message_success') }}
                        </div>

                        <div class="shopping-product-display">
                            <table>
                                <tr>
                                    <td>Order Id</td>
                                    <td>{{ $orderData['order_id'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Status</td>
                                    <td>{{ (request('status') ?? '') === 'Success' ? 'success' : 'Failed' }}</td>
                                </tr>
                                <tr>
                                    <td>Payment Transition Id</td>
                                    <td>{{ request('payuMoneyId') ?? '' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(function () {
        window.location.href = "{{ url('MyOrders') }}";
    }, 2000);
</script>
@endsection

