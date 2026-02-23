<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductAttribute extends Model
{
    protected $table = 'product_attributes';
    
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
     * Relationship with ProductAttributeItems
     */
    public function items()
    {
        return $this->hasMany(ProductAttributeItem::class, 'product_attribute_id');
    }

    /**
     * Get attributes list (CI equivalent: getAttributesList)
     */
    public static function getAttributesList($id = null)
    {
        $query = DB::table('product_attributes')->orderBy('name', 'asc');
        
        if ($id) {
            return $query->where('id', $id)->first();
        }
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get attributes dropdown list (CI equivalent)
     */
    public static function getAttributesListDropDown()
    {
        return self::where('status', 1)
                   ->orderBy('name', 'asc')
                   ->pluck('name', 'id')
                   ->toArray();
    }

    /**
     * Get attribute data by ID (CI equivalent: getAttributesDataById)
     */
    public static function getAttributesDataById($id)
    {
        $result = DB::table('product_attributes')->where('id', $id)->first();
        return $result ? (array) $result : null;
    }

    /**
     * Save attributes (CI equivalent: saveAttributes)
     */
    public static function saveAttributes($data)
    {
        $id = isset($data['id']) ? $data['id'] : '';
        
        if (!empty($id)) {
            // Update existing
            $data['updated'] = now();
            $result = DB::table('product_attributes')->where('id', $id)->update($data);
            return $result ? $id : 0;
        } else {
            // Create new
            $data['created'] = now();
            $data['updated'] = now();
            return DB::table('product_attributes')->insertGetId($data);
        }
    }

    /**
     * Delete attributes (CI equivalent: deleteAttributes)
     */
    public static function deleteAttributes($id)
    {
        $result = DB::table('product_attributes')->where('id', $id)->delete();
        return $result ? 1 : 0;
    }

    /**
     * Get multiple attributes dropdown (CI equivalent)
     */
    public static function getMultipleAttributesDropDown()
    {
        return self::with('items')
                   ->where('status', 1)
                   ->orderBy('name', 'asc')
                   ->get()
                   ->map(function ($attribute) {
                       return [
                           'id' => $attribute->id,
                           'name' => $attribute->name,
                           'items' => $attribute->items->pluck('item_name', 'id')->toArray()
                       ];
                   })
                   ->toArray();
    }
}

/**
 * Product Attribute Item Model
 */
class ProductAttributeItem extends Model
{
    protected $table = 'product_attribute_items';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $fillable = [
        'product_attribute_id',
        'item_name',
        'item_name_french'
    ];

    /**
     * Relationship with ProductAttribute
     */
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }
}

/**
 * Product Size Multiple Attribute Model
 */
class ProductSizeMultipleAttribute extends Model
{
    protected $table = 'product_size_multiple_attributes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'product_id',
        'qty',
        'size_id',
        'attribute_id',
        'attribute_item_id',
        'extra_price'
    ];
    
    protected $casts = [
        'product_id' => 'integer',
        'qty' => 'integer',
        'size_id' => 'integer',
        'attribute_id' => 'integer',
        'attribute_item_id' => 'integer',
        'extra_price' => 'decimal:2',
    ];

    /**
     * Relationship with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship with ProductAttribute
     */
    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    /**
     * Relationship with ProductAttributeItem
     */
    public function attributeItem()
    {
        return $this->belongsTo(ProductAttributeItem::class, 'attribute_item_id');
    }

    /**
     * Save size multiple attributes data (CI equivalent)
     */
    public static function saveSizeMultipleAttributesData($data, $productId)
    {
        $id = $data['id'] ?? null;
        
        if (!empty($id)) {
            // Update existing
            return DB::table('product_size_multiple_attributes')
                    ->where('id', $id)
                    ->where('product_id', $productId)
                    ->update([
                        'qty' => $data['qty'],
                        'size_id' => $data['size_id'],
                        'attribute_id' => $data['attribute_id'],
                        'attribute_item_id' => $data['attribute_item_id'],
                        'extra_price' => $data['extra_price'],
                        'updated_at' => now()
                    ]);
        } else {
            // Create new
            return DB::table('product_size_multiple_attributes')->insertGetId([
                'product_id' => $productId,
                'qty' => $data['qty'],
                'size_id' => $data['size_id'],
                'attribute_id' => $data['attribute_id'],
                'attribute_item_id' => $data['attribute_item_id'],
                'extra_price' => $data['extra_price'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Get product size multiple attributes by ID (CI equivalent)
     */
    public static function getProductSizeMultipleAttributeBYId($id)
    {
        return DB::table('product_size_multiple_attributes')
                ->where('id', $id)
                ->first();
    }

    /**
     * Get product only size multiple attributes dropdown (CI equivalent)
     */
    public static function getProductOnlySizeMultipleAttributesDropDwon($productId, $quantityId, $sizeId, $attributeId)
    {
        return DB::table('product_size_multiple_attributes')
                ->where('product_id', $productId)
                ->where('qty', $quantityId)
                ->where('size_id', $sizeId)
                ->where('attribute_id', $attributeId)
                ->pluck('attribute_item_id', 'attribute_item_id')
                ->toArray();
    }
}
