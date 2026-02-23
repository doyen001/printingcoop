<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    protected $table = 'product_orders';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $casts = [
        'user_id' => 'integer',
        'total_amount' => 'decimal:2',
        'total_sales_tax' => 'decimal:2',
        'sub_total_amount' => 'decimal:2',
        'preffered_customer_discount' => 'decimal:2',
        'payment_status' => 'integer',
        'delivery_charge' => 'decimal:2',
        'total_items' => 'integer',
        'status' => 'integer',
        'billing_country' => 'integer',
        'billing_state' => 'integer',
        'billing_alternate_phone' => 'integer',
        'shipping_country' => 'integer',
        'shipping_state' => 'integer',
        'shipping_alternate_phone' => 'integer',
        'delivery_address_id' => 'integer',
        'admin_delete' => 'boolean',
        'user_delete' => 'boolean',
        'order_date' => 'date',
        'store_id' => 'integer',
        'currency_id' => 'integer',
        'coupon_discount_amount' => 'decimal:2',
        'order_admin' => 'integer',
        'flag_shiping_cost' => 'decimal:2',
        'labels_regular' => 'array',
        'labels_thermal' => 'array',
        'shipment_data' => 'array',
        'paypal_responce' => 'array',
    ];
}
