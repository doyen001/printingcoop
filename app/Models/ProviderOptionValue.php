<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderOptionValue extends Model
{
    protected $table = 'provider_option_values';
    
    public $timestamps = false;
    
    protected $fillable = [
        'option_id',
        'provider_option_value_id',
        'value'
    ];
    
    public function option()
    {
        return $this->belongsTo(ProviderOption::class, 'option_id');
    }
}
