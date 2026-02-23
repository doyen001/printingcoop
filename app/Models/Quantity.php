<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Quantity extends Model
{
    protected $table = 'quantity';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $fillable = [
        'name',
        'name_french',
        'status',
        'show_page_size',
        'set_order'
    ];
    
    protected $casts = [
        'status' => 'boolean',
        'show_page_size' => 'boolean',
        'set_order' => 'integer',
    ];

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
     * Get quantity dropdown list (CI equivalent: getQuantityListDropDwon)
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
        
        if (!empty($id)) {
            // Update existing
            $data['updated'] = now();
            $result = DB::table('quantity')->where('id', $id)->update($data);
            return $result ? $id : 0;
        } else {
            // Create new
            $data['created'] = now();
            $data['updated'] = now();
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
