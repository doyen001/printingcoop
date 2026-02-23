<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOrderItem extends Model
{
    protected $table = 'product_order_items';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $casts = [
        'product_id' => 'integer',
        'order_id' => 'integer',
        'personailise' => 'boolean',
        'price' => 'decimal:2',
        'discount' => 'integer',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'total_stock' => 'integer',
        'cart_images' => 'array',
        'attribute_ids' => 'array',
        'product_size' => 'array',
        'product_width_length' => 'array',
        'page_product_width_length' => 'array',
        'product_depth_length_width' => 'array',
        'shipping_box_length' => 'decimal:2',
        'shipping_box_width' => 'decimal:2',
        'shipping_box_height' => 'decimal:2',
        'shipping_box_weight' => 'decimal:2',
    ];
}
