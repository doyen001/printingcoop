<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $casts = [
        'discount' => 'float',
        'discount_valid_from' => 'datetime',
        'discount_valid_to' => 'datetime',
        'discount_requirement_quantity' => 'integer',
        'status' => 'boolean',
        'discount_code_limit' => 'integer',
    ];
}
