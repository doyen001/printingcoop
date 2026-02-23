<form method="POST" action="{{ url('admin/Orders/save') }}" class="form-horizontal" enctype="multipart/form-data">
@csrf
<div class="custom-order-info-section">
    <div class="custom-order-info-title">
        <span>Order Information</span>
    </div>
    <div class="step-fields">
        <div class="row">
            <div class="col-md-4">
                <div class="step-fields-inner universal-bg-white">
                    <div class="universal-small-dark-title">
                        <span>Customer Information</span>
                    </div>
                    <div class="quote-bottom-row summary-deatil">
                        <div class="summary-deatil-inner control-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-filter-fields">
                                        <label>Name</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" placeholder="Name" value="{{ old('name', $PostData['name'] ?? '') }}" name="name">
                                            @error('name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-filter-fields">
                                        <label>Mobile</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" placeholder="Mobile" value="{{ old('mobile', $PostData['mobile'] ?? '') }}" name="mobile">
                                            @error('mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-filter-fields">
                                        <label>Email</label>
                                        <div class="controls">
                                            <input type="email" class="form-control" placeholder="Email" value="{{ old('email', $PostData['email'] ?? '') }}" name="email" id="email">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="step-fields-inner universal-bg-white">
                    <div class="universal-small-dark-title">
                        <span>Shipping/Billing Address</span>
                    </div>
                    <div class="quote-bottom-row summary-deatil">
                        <div class="summary-deatil-inner control-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="table-filter-fields">
                                        <label>Name</label>
                                        <div class="controls">
                                            <input class="form-control" type="text" placeholder="Name*" value="{{ old('billing_name', $PostData['billing_name'] ?? '') }}" name="billing_name">
                                            @error('billing_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="table-filter-fields">
                                        <label>Phone Number</label>
                                        <div class="controls">
                                            <input class="form-control" type="text" placeholder="Phone Number*" value="{{ old('billing_mobile', $PostData['billing_mobile'] ?? '') }}" name="billing_mobile">
                                            @error('billing_mobile')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="table-filter-fields">
                                        <label>Company</label>
                                        <div class="controls">
                                            <input class="form-control" type="text" placeholder="Company" value="{{ old('billing_company', $PostData['billing_company'] ?? '') }}" name="billing_company">
                                            @error('billing_company')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-filter-fields">
                                        <label>Address</label>
                                        <div class="controls">
                                            <textarea class="form-control" style="height: 60px !important;" type="text" placeholder="Address (area & street)*" name="billing_address">{{ old('billing_address', $PostData['billing_address'] ?? '') }}</textarea>
                                            @error('billing_address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="table-filter-fields">
                                        <label>Country</label>
                                        <div class="controls">
                                            <select name="billing_country" onchange="getState($(this).val())" class="form-control">
                                                <option value="">Select Country</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country['id'] }}" {{ (old('billing_country', $PostData['billing_country'] ?? '') == $country['id']) ? 'selected' : '' }}>{{ $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('billing_country')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="table-filter-fields">
                                        <label>State</label>
                                        <div class="controls">
                                            <select name="billing_state" id="stateiD" class="form-control" onchange="getCity($(this).val())">
                                                <option value="">-- Select State --</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state['id'] }}" {{ (old('billing_state', $PostData['billing_state'] ?? '') == $state['id']) ? 'selected' : '' }}>{{ $state['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('billing_state')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="table-filter-fields">
                                        <label>City</label>
                                        <div class="controls">
                                            <select name="billing_city" id="cityId" class="form-control">
                                                <option value="">-- Select City --</option>
                                                @foreach($citys as $city)
                                                    <option value="{{ $city['id'] }}" {{ (old('billing_city', $PostData['billing_city'] ?? '') == $city['id']) ? 'selected' : '' }}>{{ $city['name'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('billing_city')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="table-filter-fields">
                                        <label>Zip/Postal Code</label>
                                        <div class="controls">
                                            <input class="form-control" type="text" placeholder="Zip/Postal Code*" name="billing_pin_code" value="{{ old('billing_pin_code', $PostData['billing_pin_code'] ?? '') }}">
                                            @error('billing_pin_code')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                @include('admin.orders.shipping_method')
            </div>
            <div class="col-md-6">
                <div class="step-fields-inner universal-bg-white">
                    <div class="universal-small-dark-title">
                        <span>Payment Information</span>
                    </div>
                    <div class="quote-bottom-row summary-deatil">
                        <div class="summary-deatil-inner control-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-filter-fields">
                                        <label>Payment Type</label>
                                        <div class="controls">
                                            <select class="form-control" name="payment_type">
                                                <option value="">Select Type</option>
                                                <option value="paypal">Paypal</option>
                                            </select>
                                            @error('payment_status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-filter-fields">
                                        <label>Payment Status</label>
                                        <div class="controls">
                                            <select class="form-control" name="payment_status">
                                                <option value="">Select Status</option>
                                                @foreach($PaymentStatus as $key => $val)
                                                    <option value="{{ $key }}" {{ (old('payment_status', $PostData['payment_status'] ?? '') == $key) ? 'selected' : '' }}>{{ $val }}</option>
                                                @endforeach
                                            </select>
                                            @error('payment_status')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-filter-fields">
                                        <label>Payment Transaction ID</label>
                                        <div class="controls">
                                            <input type="text" class="form-control" placeholder="Transaction ID" name="transition_id" value="{{ old('transition_id', $PostData['transition_id'] ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="step-fields-inner universal-bg-white">
                    <div class="universal-small-dark-title">
                        <span>Discount</span>
                    </div>
                    <div class="quote-bottom-row summary-deatil">
                        <div class="summary-deatil-inner control-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-filter-fields">
                                        <label></label>
                                        <div class="controls">
                                            <input type="text" class="form-control" placeholder="Enter Coupon code" name="coupon_code" id="coupon_code">
                                            <label id="coupon_code_error" class="product_error"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="step-fields-inner universal-bg-white">
                    <div class="universal-small-dark-title">
                        <span>Order Information</span>
                    </div>
                    <div class="quote-bottom-row summary-deatil">
                        <div class="summary-deatil-inner control-group">
                            <div class="row" id="Order-Information">
                                @include('admin.orders.order-information')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="next-step-btn" id="confirmbtn">
            @include('admin.orders.confirm_btn')
        </div>
    </div>
</div>
</form>

<script>
function getState(country_id) {
    $('#stateiD').val('');
    $('#stateiD').html('<option value="">Loading..</option>');
    if (country_id != '') {
        var url = '{{ url("MyAccounts/getStateDropDownListByAjax") }}/' + country_id;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "html",
            success: function(data) {
                $('#stateiD').html(data);
            }
        });
    }
}

function getCity(state_id) {
    $('#cityId').val('');
    $('#cityId').html('<option value="">Loading..</option>');
    if (state_id != '') {
        var url = '{{ url("admin/Orders/getCityDropDownListByAjax") }}/' + state_id;
        $.ajax({
            type: "GET",
            url: url,
            contentType: "html",
            success: function(data) {
                var json = JSON.parse(data);
                var orderinformation = json.orderinformation;
                var confirmbtn = json.confirmbtn;
                $('#Order-Information').html(orderinformation);
                $('#confirmbtn').html(confirmbtn);
                $('#cityId').html(json.options);
            }
        });
    }
}
</script>
