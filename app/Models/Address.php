<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'addresses';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $casts = [
        'status' => 'boolean',
        'user_id' => 'integer',
        'state' => 'integer',
        'default_delivery_address' => 'boolean',
    ];
}
