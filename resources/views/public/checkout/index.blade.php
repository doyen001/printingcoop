{{-- 
    Checkout page - Steps 1-2 (replicate CI Checkouts/index.php)
    
    Step 1: Login check (automatic redirect)
    Step 2: Address selection
    
    Available variables:
    - $stap: Current step (base64 encoded)
    - $order_id: Order ID (base64 encoded)
    - $product_id: Product ID (base64 encoded)
    - $address: User addresses array
    - $countries: Countries list
    - $ProductOrder: Order data
    - $ProductOrderItem: Order items
    - $salesTaxRatesProvinces_Data: Tax rates
    - $coupon_code: Applied coupon code
--}}

@extends('elements.app')

@section('content')
<div class="checkout-page">
    {{-- Page Header --}}
    <div class="page-header">
        <h1>{{ $page_title }}</h1>
        
        {{-- Checkout Steps Progress --}}
        <div class="checkout-steps">
            <div class="step {{ base64_decode($stap) >= 1 ? 'active' : '' }}">
                <span class="step-number">1</span>
                <span class="step-title">Login</span>
            </div>
            <div class="step {{ base64_decode($stap) >= 2 ? 'active' : '' }}">
                <span class="step-number">2</span>
                <span class="step-title">Address</span>
            </div>
            <div class="step {{ base64_decode($stap) >= 3 ? 'active' : '' }}">
                <span class="step-number">3</span>
                <span class="step-title">Shipping</span>
            </div>
            <div class="step {{ base64_decode($stap) >= 4 ? 'active' : '' }}">
                <span class="step-number">4</span>
                <span class="step-title">Payment</span>
            </div>
        </div>
    </div>
    
    {{-- Flash Messages --}}
    @if(session('code_success'))
    <div class="alert alert-success">
        {{ session('code_success') }}
    </div>
    @endif
    
    @if(session('code_error'))
    <div class="alert alert-danger">
        {{ session('code_error') }}
    </div>
    @endif
    
    @if(session('message_error'))
    <div class="alert alert-danger">
        {{ session('message_error') }}
    </div>
    @endif
    
    {{-- Step 2: Address Selection --}}
    @if(base64_decode($stap) == 2)
    <div class="checkout-step-2">
        <div class="row">
            {{-- Address Selection Form --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Select Delivery Address</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ url('Checkouts/index/' . $stap . '/' . $order_id . '/' . $product_id . '/' . $coupon_code) }}">
                            @csrf
                            
                            {{-- Address List --}}
                            <div class="address-list">
                                @forelse($address as $addr)
                                <div class="address-item">
                                    <label class="address-radio">
                                        <input type="radio" 
                                               name="delivery_address_id" 
                                               value="{{ $addr['id'] }}" 
                                               {{ isset($ProductOrder['delivery_address_id']) && $ProductOrder['delivery_address_id'] == $addr['id'] ? 'checked' : '' }}
                                               required>
                                        <div class="address-details">
                                            <h5>{{ $addr['name'] }}</h5>
                                            <p>{{ $addr['address'] }}</p>
                                            @if(!empty($addr['landmark']))
                                            <p>Landmark: {{ $addr['landmark'] }}</p>
                                            @endif
                                            <p>
                                                {{ $addr['city'] }}, {{ $addr['state'] }} - {{ $addr['pin_code'] }}
                                            </p>
                                            <p>Phone: {{ $addr['mobile'] }}</p>
                                            @if(!empty($addr['alternate_phone']))
                                            <p>Alt Phone: {{ $addr['alternate_phone'] }}</p>
                                            @endif
                                            @if(!empty($addr['company_name']))
                                            <p>Company: {{ $addr['company_name'] }}</p>
                                            @endif
                                            <span class="badge badge-info">{{ ucfirst($addr['address_type']) }}</span>
                                        </div>
                                    </label>
                                </div>
                                @empty
                                <div class="alert alert-warning">
                                    No addresses found. Please add a delivery address.
                                </div>
                                @endforelse
                            </div>
                            
                            {{-- Add New Address Button --}}
                            <div class="mt-3">
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addAddressModal">
                                    <i class="fas fa-plus"></i> Add New Address
                                </button>
                            </div>
                            
                            {{-- Shipping Method Selection (if order exists) --}}
                            @if(!empty($order_id) && base64_decode($order_id) > 0)
                            <div class="shipping-methods mt-4">
                                <h4>Select Shipping Method</h4>
                                
                                {{-- Shipping options would be displayed here --}}
                                <div class="form-group">
                                    <label>Shipping Method</label>
                                    <select name="shipping_method_formate" class="form-control">
                                        <option value="">Select Shipping Method</option>
                                        {{-- Shipping methods populated dynamically --}}
                                    </select>
                                </div>
                            </div>
                            @endif
                            
                            {{-- Continue Button --}}
                            <div class="checkout-actions mt-4">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    Continue to Shipping
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            {{-- Order Summary Sidebar --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Order Summary</h3>
                    </div>
                    <div class="card-body">
                        {{-- Order Items --}}
                        <div class="order-items">
                            @if(isset($ProductOrderItem) && count($ProductOrderItem) > 0)
                                @foreach($ProductOrderItem as $item)
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="{{ url('uploads/products/small/' . $item['product_image']) }}" 
                                             alt="{{ $item['name'] }}">
                                    </div>
                                    <div class="item-details">
                                        <h6>{{ config('store.language_name') == 'French' ? $item['name_french'] : $item['name'] }}</h6>
                                        <p>Qty: {{ $item['quantity'] }}</p>
                                        <p class="item-price">
                                            {{ config('store.product_price_currency_symbol') }}{{ number_format($item['subtotal'], 2) }}
                                        </p>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>
                        
                        {{-- Order Totals --}}
                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotal ({{ $ProductOrder['total_items'] ?? 0 }} items)</span>
                                <span>{{ config('store.product_price_currency_symbol') }}{{ number_format($ProductOrder['sub_total_amount'] ?? 0, 2) }}</span>
                            </div>
                            
                            @if(isset($ProductOrder['preffered_customer_discount']) && $ProductOrder['preffered_customer_discount'] > 0)
                            <div class="total-row discount">
                                <span>Preferred Customer Discount (10%)</span>
                                <span>-{{ config('store.product_price_currency_symbol') }}{{ number_format($ProductOrder['preffered_customer_discount'], 2) }}</span>
                            </div>
                            @endif
                            
                            @if(isset($ProductOrder['coupon_discount_amount']) && $ProductOrder['coupon_discount_amount'] > 0)
                            <div class="total-row discount">
                                <span>Coupon Discount ({{ $ProductOrder['coupon_code'] }})</span>
                                <span>-{{ config('store.product_price_currency_symbol') }}{{ number_format($ProductOrder['coupon_discount_amount'], 2) }}</span>
                            </div>
                            @endif
                            
                            @if(isset($ProductOrder['total_sales_tax']) && $ProductOrder['total_sales_tax'] > 0)
                            <div class="total-row">
                                <span>Sales Tax ({{ $salesTaxRatesProvinces_Data['total_tax_rate'] ?? 0 }}%)</span>
                                <span>{{ config('store.product_price_currency_symbol') }}{{ number_format($ProductOrder['total_sales_tax'], 2) }}</span>
                            </div>
                            @endif
                            
                            @if(isset($ProductOrder['delivery_charge']) && $ProductOrder['delivery_charge'] > 0)
                            <div class="total-row">
                                <span>Shipping</span>
                                <span>{{ config('store.product_price_currency_symbol') }}{{ number_format($ProductOrder['delivery_charge'], 2) }}</span>
                            </div>
                            @endif
                            
                            <div class="total-row total">
                                <span><strong>Total</strong></span>
                                <span><strong>{{ config('store.product_price_currency_symbol') }}{{ number_format($ProductOrder['total_amount'] ?? 0, 2) }}</strong></span>
                            </div>
                        </div>
                        
                        {{-- Coupon Code Form --}}
                        <div class="coupon-form mt-3">
                            <form method="GET" action="{{ url('Checkouts/index/' . $stap . '/' . $order_id . '/' . $product_id) }}">
                                <div class="input-group">
                                    <input type="text" 
                                           name="coupon_code" 
                                           class="form-control" 
                                           placeholder="Enter coupon code"
                                           value="{{ $coupon_code ?? '' }}">
                                    <input type="hidden" name="apply_code" value="1">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-secondary">Apply</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Add Address Modal --}}
<div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addAddressForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Full Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Mobile *</label>
                                <input type="text" name="mobile" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Address *</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Country *</label>
                                <select name="country" class="form-control" required>
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>State/Province *</label>
                                <select name="state" class="form-control" required>
                                    <option value="">Select State</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>City *</label>
                                <select name="city" class="form-control" required>
                                    <option value="">Select City</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Postal Code *</label>
                                <input type="text" name="pin_code" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Landmark</label>
                                <input type="text" name="landmark" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alternate Phone</label>
                                <input type="text" name="alternate_phone" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company Name</label>
                                <input type="text" name="company_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Address Type *</label>
                                <select name="address_type" class="form-control" required>
                                    <option value="home">Home</option>
                                    <option value="office">Office</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveAddress()">Save Address</button>
            </div>
        </div>
    </div>
</div>

<script>
function saveAddress() {
    // Implement AJAX address save
    const formData = new FormData(document.getElementById('addAddressForm'));
    
    fetch('{{ url("Addresses/save") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status == 'success') {
            alert('Address saved successfully');
            location.reload();
        } else {
            alert('Error saving address');
        }
    });
}
</script>
@endsection
