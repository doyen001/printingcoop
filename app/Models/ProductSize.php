<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProductSize extends Model
{
    protected $table = 'product_size';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'product_id',
        'qty',
        'size_id',
        'extra_price',
        'ncr_number_parts',
        'ncr_number_parts_french',
        'ncr_number_part_price',
        'stock',
        'stock_french',
        'stock_extra_price',
        'paper_quality',
        'paper_quality_french',
        'paper_quality_extra_price',
        'color',
        'color_french',
        'color_extra_price',
        'diameter',
        'diameter_extra_price',
        'shape_paper',
        'shape_paper_french',
        'shape_paper_extra_price',
        'grommets',
        'grommets_french',
        'grommets_extra_price'
    ];
    
    protected $casts = [
        'size_id' => 'integer',
        'qty' => 'integer',
        'product_id' => 'integer',
        'extra_price' => 'decimal:2',
        'ncr_number_part_price' => 'decimal:2',
        'stock_extra_price' => 'decimal:2',
        'paper_quality_extra_price' => 'decimal:2',
        'color_extra_price' => 'decimal:2',
        'diameter_extra_price' => 'decimal:2',
        'shape_paper_extra_price' => 'decimal:2',
        'grommets_extra_price' => 'decimal:2',
    ];

    /**
     * Relationship with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relationship with Size definition
     */
    public function sizeDefinition()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    /**
     * Relationship with Quantity definition
     */
    public function quantityDefinition()
    {
        return $this->belongsTo(Quantity::class, 'qty');
    }

    /**
     * Get size dropdown list (CI equivalent)
     */
    public static function getSizeListDropDown()
    {
        return DB::table('sizes')
                ->where('status', 1)
                ->orderBy('size_name', 'asc')
                ->pluck('size_name', 'id')
                ->toArray();
    }

    /**
     * Get product sizes for dropdown (CI equivalent)
     */
    public static function getProductOnlySizeDropDwon($productId, $quantityId)
    {
        return DB::table('product_size')
                ->where('product_id', $productId)
                ->where('qty', $quantityId)
                ->leftJoin('sizes', 'sizes.id', '=', 'product_size.size_id')
                ->pluck('sizes.size_name', 'product_size.size_id')
                ->toArray();
    }

    /**
     * Save product size data (CI equivalent)
     */
    public static function saveProductSizeData($data, $productId)
    {
        $id = $data['id'] ?? null;
        
        if (!empty($id)) {
            // Update existing
            return DB::table('product_size')
                    ->where('id', $id)
                    ->where('product_id', $productId)
                    ->update([
                        'qty' => $data['qty'],
                        'size_id' => $data['size_id'],
                        'extra_price' => $data['extra_price'],
                        'updated_at' => now()
                    ]);
        } else {
            // Create new
            return DB::table('product_size')->insertGetId([
                'product_id' => $productId,
                'qty' => $data['qty'],
                'size_id' => $data['size_id'],
                'extra_price' => $data['extra_price'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Delete product size (CI equivalent)
     */
    public static function deleteProductSize($productId, $quantityId, $id)
    {
        return DB::table('product_size')
                ->where('id', $id)
                ->where('product_id', $productId)
                ->where('qty', $quantityId)
                ->delete();
    }
}
