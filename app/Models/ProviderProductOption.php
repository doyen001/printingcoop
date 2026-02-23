<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderProductOption extends Model
{
    protected $table = 'provider_product_options';
    
    public $timestamps = false;
    
    protected $fillable = [
        'provider_id',
        'provider_product_id',
        'option_id',
        'provider_option_value_id',
        'value',
        'price_rate'
    ];
    
    protected $casts = [
        'price_rate' => 'float'
    ];
    
    public function option()
    {
        return $this->belongsTo(ProviderOption::class, 'option_id');
    }
    
    public function optionValue()
    {
        return $this->belongsTo(ProviderOptionValue::class, 'provider_option_value_id');
    }
}
