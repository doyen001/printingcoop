<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';
    
    public $timestamps = false;
    
    protected $fillable = [
        'name',
        'label',
        'label_fr',
        'type'
    ];
    
    protected $casts = [
        'type' => 'integer'
    ];
    
    public function attributeItems()
    {
        return $this->hasMany(AttributeItem::class, 'attribute_id');
    }
}
