<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>Order Total Item</label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label id="total_item_label">{{ session('total_item', 0) }}</label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>Sub Total:</label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>
            @php
            $sub_total = session('sub_total', 0);
            echo CURREBCY_SYMBOL . number_format($sub_total, 2);
            @endphp
        </label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>Coupon Discount:</label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>
            @php
            $discount_amount = session('discount_amount', 0);
            echo "-" . CURREBCY_SYMBOL . number_format($discount_amount, 2);
            @endphp
        </label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>Preffered Customer Discount:</label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>
            @php
            $preffered_customer_discount = session('preffered_customer_discount', 0);
            echo "-" . CURREBCY_SYMBOL . number_format($preffered_customer_discount, 2);
            @endphp
        </label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>Shipping Method:</label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>
            @php
            $shipping_fee = session('shipping_fee', 0);
            echo CURREBCY_SYMBOL . number_format($shipping_fee, 2);
            @endphp
        </label>
    </div>
</div>

@if(session('total_sales_tax'))
    @php
    $state_id = session('state_id', 0);
    $salesTaxRatesProvinces_Data = app('App\Models\Address')->salesTaxRatesProvincesById($state_id);
    @endphp
    <div class="col-6 col-md-6">
        <div class="table-filter-fields">
            <label>{{ $salesTaxRatesProvinces_Data['type'] }} {{ number_format($salesTaxRatesProvinces_Data['total_tax_rate'], 2) }}%:</label>
        </div>
    </div>
    <div class="col-6 col-md-6">
        <div class="table-filter-fields">
            <label>
                @php
                $total_sales_tax = session('total_sales_tax', 0);
                echo CURREBCY_SYMBOL . number_format($total_sales_tax, 2);
                @endphp
            </label>
        </div>
    </div>
@endif

<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>Total Amount:</label>
    </div>
</div>
<div class="col-6 col-md-6">
    <div class="table-filter-fields">
        <label>
            @php
            $total_amount = session('total_amount', 0);
            echo CURREBCY_SYMBOL . number_format($total_amount, 2);
            @endphp
        </label>
    </div>
</div>

<input type='hidden' id="shipping_fee" value="{{ session('shipping_fee', 0) }}" name="shipping_fee">
<input type='hidden' id="discount_amount" value="{{ session('discount_amount', 0) }}" name="coupon_discount_amount">
<input type='hidden' id="preffered_customer_discount" value="{{ session('preffered_customer_discount', 0) }}" name="preffered_customer_discount">
<input type='hidden' id="total_item" value="{{ session('total_item', 0) }}" name="total_items">
<input type='hidden' id="total_sales_tax" value="{{ session('total_sales_tax', 0) }}" name="total_sales_tax">
<input type='hidden' id="sub_total" value="{{ session('sub_total', 0) }}" name="sub_total_amount">
<input type='hidden' id="total_amount" value="{{ session('total_amount', 0) }}" name="total_amount">
