<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeItem extends Model
{
    protected $table = 'attribute_items';
    
    public $timestamps = false;
    
    protected $fillable = [
        'attribute_id',
        'name',
        'name_fr'
    ];
    
    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }
}
