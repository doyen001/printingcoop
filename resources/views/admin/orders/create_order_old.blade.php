@extends('layouts.admin')

@section('content')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>

<style>
.dashboardcode-bsmultiselect .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: 0px !important;
    transform: translate(0, 0) !important;
    width: 100%;
    max-height: 500px;
    overflow-y: scroll;
    min-width: 100% !important;
    overflow-x: hidden;
}

.dashboardcode-bsmultiselect .custom-control-label {
    font-size: 13px !important;
    margin: 0px !important;
    color: #222;
}

.dashboardcode-bsmultiselect .custom-control.custom-checkbox {
    display: flex;
    align-items: center;
}

.dashboardcode-bsmultiselect .dropdown-menu li {
    margin-bottom: 2px;
}

.dashboardcode-bsmultiselect .form-control .badge {
    position: relative;
    background: #f3f3f3;
    padding: 5px 20px 5px 5px !important;
    font-size: 12px;
    font-weight: 400;
    margin: 0px 5px 5px 0px;
}

.dashboardcode-bsmultiselect .form-control .badge .close {
    background: transparent !important;
    position: absolute;
    top: 45%;
    right: 0px;
    transform: translate(0%, -50%);
    padding: 0px !important;
    height: 15px !important;
    width: 15px !important;
    font-size: 22px !important;
    display: flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
    color: #666;
}
</style>

<div class="content-wrapper" style="min-height: 687px;">
<section class="content">
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="text-center" style="color:red">
                        {{ session('message_error') }}
                    </div>
                    <div class="text-center" style="color:green">
                        {{ session('message_success') }}
                    </div>

                    <div class="inner-head-section">
                        <div class="inner-title">
                            <span>{{ $page_title }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('orders.save') }}" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Customer Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Customer Information</h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name" class="control-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email" class="control-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mobile" class="control-label">Mobile <span class="text-danger">*</span></label>
                                    <input type="text" name="mobile" id="mobile" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Billing Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Billing Information</h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="billing_name" class="control-label">Billing Name <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_name" id="billing_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="billing_mobile" class="control-label">Billing Mobile <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_mobile" id="billing_mobile" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="billing_pin_code" class="control-label">Pin Code <span class="text-danger">*</span></label>
                                    <input type="text" name="billing_pin_code" id="billing_pin_code" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="billing_address" class="control-label">Billing Address <span class="text-danger">*</span></label>
                                    <textarea name="billing_address" id="billing_address" class="form-control" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_city" class="control-label">City <span class="text-danger">*</span></label>
                                            <input type="text" name="billing_city" id="billing_city" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_state" class="control-label">State <span class="text-danger">*</span></label>
                                            <input type="text" name="billing_state" id="billing_state" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="billing_country" class="control-label">Country <span class="text-danger">*</span></label>
                                            <input type="text" name="billing_country" id="billing_country" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Order Information</h4>
                                <hr>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_status" class="control-label">Payment Status <span class="text-danger">*</span></label>
                                    <select name="payment_status" id="payment_status" class="form-control" required>
                                        @foreach($paymentStatus as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="payment_type" class="control-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_type" id="payment_type" class="form-control" required>
                                        @foreach($paymentMethods as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_items" class="control-label">Total Items <span class="text-danger">*</span></label>
                                    <input type="number" name="total_items" id="total_items" class="form-control" min="0" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="shipping_fee" class="control-label">Shipping Fee</label>
                                    <input type="number" name="shipping_fee" id="shipping_fee" class="form-control" min="0" step="0.01" value="0">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sub_total_amount" class="control-label">Sub Total Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="sub_total_amount" id="sub_total_amount" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_amount" class="control-label">Total Amount <span class="text-danger">*</span></label>
                                    <input type="number" name="total_amount" id="total_amount" class="form-control" min="0" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="transition_id" class="control-label">Transaction ID</label>
                                    <input type="text" name="transition_id" id="transition_id" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Create Order
                                    </button>
                                    <a href="{{ route('orders.index') }}" class="btn btn-default">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>

                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
</div>

<script>
$(document).ready(function() {
    // Auto-calculate total when subtotal or shipping changes
    $('#sub_total_amount, #shipping_fee').on('input', function() {
        var subtotal = parseFloat($('#sub_total_amount').val()) || 0;
        var shipping = parseFloat($('#shipping_fee').val()) || 0;
        var total = subtotal + shipping;
        $('#total_amount').val(total.toFixed(2));
    });

    // Copy billing to shipping if needed (simplified version)
    // In CI, this was handled by setting billing = shipping in controller
});
</script>
@endsection
