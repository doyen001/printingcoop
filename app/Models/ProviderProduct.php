<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProduct extends Model
{
    protected $table = 'provider_products';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $casts = [
        'provider_id' => 'integer',
        'provider_product_id' => 'integer',
        'enabled' => 'boolean',
        'product_id' => 'integer',
        'information_type' => 'integer',
        'price_rate' => 'float',
        'deleted' => 'boolean',
        'updating' => 'boolean',
    ];
}
