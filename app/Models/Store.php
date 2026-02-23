<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';
    
    public $timestamps = false;
    
    protected $casts = [
        'status' => 'boolean',
        'langue_id' => 'integer',
        'shopping_id' => 'integer',
        'default_currency_id' => 'integer',
        'stor_type' => 'integer',
        'main_store' => 'integer',
        'main_store_id' => 'integer',
        'show_all_categories' => 'boolean',
        'show_language_translation' => 'boolean',
        'clover_mode' => 'integer',
    ];
}
