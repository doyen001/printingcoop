<div class="step-fields-inner universal-bg-white">
    <div class="universal-small-dark-title">
        <span>Shipping Method</span>
    </div>
    <div class="quote-bottom-row summary-deatil">
        <div class="summary-deatil-inner">
            <div class="shipping-method-fields">
                @php
                $shipping_method_formate = $PostData['shipping_method_formate'] ?? '';
                $upsServiceCode = upsServiceCode();
                @endphp

                @foreach($total_charges_ups as $key => $val)
                    @php
                    $value = 'ups-' . $val->TotalCharges->MonetaryValue . '-' . $val->Service->Code;
                    @endphp
                    <div class="shipping-metthod-single">
                        <label>
                            <input type="radio" name="shipping_method_formate" value="{{ $value }}" {{ $shipping_method_formate == $value ? 'checked' : '' }} class="shipping_method_formate">
                            <div class="row">
                                <div class="col-md-12 col-lg-3 col-xl-2">
                                    <strong>{{ CURREBCY_SYMBOL }}{{ $val->TotalCharges->MonetaryValue }}</strong>
                                </div>
                                <div class="col-md-9 col-lg-6 col-xl-7 p-0">
                                    <span>{{ $upsServiceCode[$val->Service->Code] }}</span>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3">
                                    <span>UPS</span>
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach

                @foreach($CanedaPostShiping['list'] as $key => $val)
                    @php
                    $value = 'canadapost-' . $val['price'] . '-' . $val['service_name'];
                    @endphp
                    <div class="shipping-metthod-single">
                        <label>
                            <input type="radio" name="shipping_method_formate" value="{{ $value }}" {{ $shipping_method_formate == $value ? 'checked' : '' }} class="shipping_method_formate">
                            <div class="row">
                                <div class="col-md-12 col-lg-3 col-xl-2">
                                    <strong>{{ CURREBCY_SYMBOL }}{{ $val['price'] }}</strong>
                                </div>
                                <div class="col-md-9 col-lg-6 col-xl-7 p-0">
                                    <span>{{ $val['service_name'] }}</span>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3">
                                    <span>Canada Post</span>
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach

                @foreach($PickupStoresList as $key => $val)
                    @php
                    $value = 'pickupinstore-0.00-' . $val['id'];
                    @endphp
                    <div class="shipping-metthod-single">
                        <label>
                            <input type="radio" name="shipping_method_formate" value="{{ $value }}" {{ $shipping_method_formate == $value ? 'checked' : '' }} class="shipping_method_formate">
                            <div class="row">
                                <div class="col-md-2">
                                    <strong>Free Delivery</strong>
                                </div>
                                <div class="col-md-7 p-0">
                                    <span>{{ $val['name'] }}</span><br>
                                    <span>{{ $val['address'] }}</span><br>
                                    <span>{{ $val['phone'] }}</span>
                                </div>
                                <div class="col-md-3">
                                    <span>Pickup In Store</span>
                                </div>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
