<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductMultipleAttributeItem extends Model
{
    protected $table = 'product_multiple_attribute_items';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $fillable = [
        'product_attribute_id',
        'item_name',
        'item_name_french',
        'extra_price',
        'use_percentage',
        'value_min',
        'value_max'
    ];
    
    protected $casts = [
        'extra_price' => 'decimal:2',
        'use_percentage' => 'boolean',
    ];

    /**
     * Relationship with ProductMultipleAttribute
     */
    public function attribute()
    {
        return $this->belongsTo(ProductMultipleAttribute::class, 'product_attribute_id');
    }

    /**
     * Get multiple attribute items data by attribute ID (CI equivalent: getMultipleAttributeItems)
     */
    public static function getMultipleAttributeItems($attributeId)
    {
        return DB::table('product_multiple_attribute_items')
                ->where('product_attribute_id', $attributeId)
                ->get()
                ->map(function($item) {
                    return (array) $item;
                })->toArray();
    }

    /**
     * Save multiple attribute items (CI equivalent: saveMultipleAttributeItem)
     */
    public static function saveMultipleAttributeItem($data, $attributeId)
    {
        if (!empty($attributeId) && !empty($data)) {
            // Get existing items (CI logic)
            $old_data = DB::table('product_multiple_attribute_items')
                ->select('*')
                ->where('product_attribute_id', $attributeId)
                ->get()
                ->map(function($item) {
                    return (array) $item;
                })->toArray();
            
            $old_data_ids = array_column($old_data, 'id');
            $update_data_ids = array();

            // Update or insert items (CI logic)
            foreach ($data as $val) {
                if (!empty($val['item_name'])) {
                    if (!empty($val['id'])) {
                        // Update existing item
                        unset($val['created']);
                        DB::table('product_multiple_attribute_items')
                            ->where('id', $val['id'])
                            ->update($val);
                        
                        $update_data_ids[] = $val['id'];
                    } else {
                        // Insert new item
                        unset($val['id']);
                        // Don't override product_attribute_id - use what's already in $val
                        $val['created'] = now();
                        $val['updated'] = now();
                        DB::table('product_multiple_attribute_items')->insert($val);
                    }
                }
            }

            // Delete items that are no longer present (CI logic)
            foreach ($old_data as $old_item) {
                $id = $old_item['id'];
                if (!in_array($id, $update_data_ids)) {
                    DB::table('product_multiple_attribute_items')->where('id', $id)->delete();
                }
            }
        }
        
        return true;
    }
}
