<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductMultipleAttribute extends Model
{
    protected $table = 'product_multiple_attributes';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $fillable = [
        'name',
        'name_french',
        'status'
    ];
    
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Relationship with ProductMultipleAttributeItem
     */
    public function items()
    {
        return $this->hasMany(ProductMultipleAttributeItem::class, 'product_attribute_id');
    }

    /**
     * Get multiple attributes list (CI equivalent: getMultipleAttributes)
     */
    public static function getMultipleAttributes($id = null)
    {
        $query = DB::table('product_multiple_attributes')->orderBy('name', 'asc');
        
        if ($id) {
            return $query->where('id', $id)->first();
        }
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get multiple attributes dropdown list (CI equivalent: getMultipleAttributesDropDown)
     */
    public static function getMultipleAttributesDropDown()
    {
        return self::where('status', 1)
                   ->orderBy('name', 'asc')
                   ->pluck('name', 'id')
                   ->toArray();
    }

    /**
     * Get multiple attribute data by ID (CI equivalent: getMultipleAttribute)
     */
    public static function getMultipleAttribute($id)
    {
        $result = DB::table('product_multiple_attributes')->where('id', $id)->first();
        return $result ? (array) $result : null;
    }

    /**
     * Save multiple attributes (CI equivalent: saveMultipleAttributes)
     */
    public static function saveMultipleAttributes($data)
    {
        $id = isset($data['id']) ? $data['id'] : '';
        unset($data['id']); // Remove id from data array
        
        if (!empty($id)) {
            // Update existing (without timestamps for CI compatibility)
            $result = DB::table('product_multiple_attributes')->where('id', $id)->update($data);
            // Even if no rows were affected (result = 0), if the ID exists, consider it successful
            if ($result >= 0) {
                return $id;
            } else {
                return 0;
            }
        } else {
            // Create new (without timestamps for CI compatibility)
            return DB::table('product_multiple_attributes')->insertGetId($data);
        }
    }

    /**
     * Delete multiple attributes (CI equivalent: deleteMultipleAttributes)
     */
    public static function deleteMultipleAttributes($id)
    {
        $result = DB::table('product_multiple_attributes')->where('id', $id)->delete();
        return $result ? 1 : 0;
    }
}
