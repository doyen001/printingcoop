<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $casts = [
        'status' => 'boolean',
        'menu_id' => 'integer',
        'category_order' => 'integer',
        'show_main_menu' => 'boolean',
        'show_our_printed_product' => 'boolean',
        'show_footer_menu' => 'boolean',
    ];
}
