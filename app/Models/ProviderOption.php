<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderOption extends Model
{
    protected $table = 'provider_options';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'type',
        'attribute_id',
        'html_type'
    ];
    
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }
}
