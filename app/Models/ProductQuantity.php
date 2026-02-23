<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductQuantity extends Model
{
    protected $table = 'product_quantity';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'product_id',
        'qty',
        'price'
    ];
    
    protected $casts = [
        'qty' => 'integer',
        'product_id' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Relationship with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship with Quantity definition
     */
    public function quantityDefinition()
    {
        return $this->belongsTo(Quantity::class, 'qty');
    }

    /**
     * Get quantity dropdown list (CI equivalent)
     */
    public static function getQuantityListDropDown()
    {
        return DB::table('quantity')
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
    }

    /**
     * Get product quantities for dropdown (CI equivalent)
     */
    public static function getProductOnlyQuantityDropDwon($productId)
    {
        return DB::table('product_quantity')
                ->where('product_id', $productId)
                ->leftJoin('quantity', 'quantity.id', '=', 'product_quantity.qty')
                ->pluck('quantity.name', 'product_quantity.qty')
                ->toArray();
    }

    /**
     * Save product quantity (CI equivalent)
     */
    public static function saveProductQty($data, $productId)
    {
        $id = $data['id'] ?? null;
        
        if (!empty($id)) {
            // Update existing
            return DB::table('product_quantity')
                    ->where('id', $id)
                    ->where('product_id', $productId)
                    ->update([
                        'qty' => $data['qty'],
                        'price' => $data['price'],
                        'updated_at' => now()
                    ]);
        } else {
            // Create new
            return DB::table('product_quantity')->insertGetId([
                'product_id' => $productId,
                'qty' => $data['qty'],
                'price' => $data['price'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Delete product quantity (CI equivalent)
     */
    public static function deleteProductQty($productId, $id)
    {
        return DB::table('product_quantity')
                ->where('id', $id)
                ->where('product_id', $productId)
                ->delete();
    }

    /**
     * Get quantity list (CI equivalent: getQuantityList)
     */
    public static function getQuantityList($id = null)
    {
        $query = DB::table('quantity')->orderBy('set_order', 'asc');
        
        if ($id) {
            return $query->where('id', $id)->first();
        }
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get quantity data by ID (CI equivalent: getQtyById)
     */
    public static function getQtyById($id)
    {
        $result = DB::table('quantity')->where('id', $id)->first();
        return $result ? (array) $result : null;
    }

    /**
     * Save quantity (CI equivalent: saveQuantity)
     */
    public static function saveQuantity($data)
    {
        $id = isset($data['id']) ? $data['id'] : '';
        unset($data['id']); // Remove id from data array
        
        if (!empty($id)) {
            // Update existing (without timestamps for CI compatibility)
            $result = DB::table('quantity')->where('id', $id)->update($data);
            return $result ? $id : 0;
        } else {
            // Create new (without timestamps for CI compatibility)
            return DB::table('quantity')->insertGetId($data);
        }
    }

    /**
     * Delete quantity (CI equivalent: deleteQuantity)
     */
    public static function deleteQuantity($id)
    {
        $result = DB::table('quantity')->where('id', $id)->delete();
        return $result ? 1 : 0;
    }
}
