<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $casts = [
        'status' => 'boolean',
        'menu_id' => 'integer',
        'category_id' => 'integer',
        'sub_category_order' => 'integer',
        'show_main_menu' => 'boolean',
    ];
}
