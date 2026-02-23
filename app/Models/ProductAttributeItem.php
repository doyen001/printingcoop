<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductAttributeItem extends Model
{
    protected $table = 'product_attribute_items';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $fillable = [
        'product_attribute_id',
        'item_name',
        'item_name_french',
        'extra_price',
        'use_percentage',
        'value_min',
        'value_max',
        'show_order'
    ];
    
    protected $casts = [
        'extra_price' => 'decimal:2',
        'use_percentage' => 'boolean',
        'show_order' => 'integer',
    ];

    /**
     * Relationship with ProductAttribute
     */
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    /**
     * Get attribute items data by attribute ID (CI equivalent: getAttributesItemDataById)
     * Exact CI implementation: Product_Model->getAttributesItemDataById()
     */
    public static function getAttributesItemDataById($id)
    {
        try {
            $data = DB::table('product_attribute_items')
                    ->select('*')
                    ->where('product_attribute_id', $id)
                    ->get()
                    ->toArray();
            
            // Convert to array format like CI result_array()
            $result = [];
            foreach ($data as $item) {
                $result[] = (array) $item;
            }
            
            return $result;
        } catch (\Exception $e) {
            // Log error and return empty array like CI would
            return [];
        }
    }

    /**
     * Save attribute items (CI equivalent: saveAttributeItem)
     */
    public static function saveAttributeItem($data, $attributeId)
    {
        // First delete existing items for this attribute
        DB::table('product_attribute_items')->where('product_attribute_id', $attributeId)->delete();
        
        // Insert new items
        foreach ($data as $item) {
            $itemData = [
                'product_attribute_id' => $attributeId,
                'item_name' => $item['item_name'],
                'item_name_french' => $item['item_name_french'] ?? '',
                'created' => now(),
                'updated' => now(),
            ];
            
            if (isset($item['id']) && !empty($item['id'])) {
                $itemData['id'] = $item['id'];
            }
            
            DB::table('product_attribute_items')->insert($itemData);
        }
        
        return true;
    }

    /**
     * Get attribute items dropdown (CI equivalent)
     */
    public static function getAttributeItemsDropDown($attributeId)
    {
        return DB::table('product_attribute_items')
                ->where('product_attribute_id', $attributeId)
                ->orderBy('item_name', 'asc')
                ->pluck('item_name', 'id')
                ->toArray();
    }

    /**
     * Delete attribute items by attribute ID
     */
    public static function deleteAttributeItemsByAttributeId($attributeId)
    {
        return DB::table('product_attribute_items')
                ->where('product_attribute_id', $attributeId)
                ->delete();
    }
}
