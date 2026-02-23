<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PageSize extends Model
{
    protected $table = 'page_size';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'name',
        'name_french',
        'status',
        'show_page_size',
        'set_order',
        'total_page'
    ];
    
    protected $casts = [
        'status' => 'integer',
        'show_page_size' => 'integer',
        'set_order' => 'integer',
        'total_page' => 'integer',
    ];

    /**
     * Get size options by table type (CI equivalent: sizeOptions)
     */
    public static function sizeOptions($table)
    {
        $query = DB::table($table);
        
        if ($table == 'page_size') {
            $query->orderBy('total_page', 'asc');
        } else {
            $query->orderBy('name', 'asc');
        }
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get product pages (CI equivalent: getProductPages)
     */
    public static function getProductPages()
    {
        return DB::table('page_size')
                ->where('status', 1)
                ->orderBy('total_page', 'asc')
                ->get()
                ->map(function($item) {
                    return (array) $item;
                })->toArray();
    }

    /**
     * Get page size list (CI equivalent: getPageSizeList)
     */
    public static function getPageSizeList()
    {
        return DB::table('page_size')
                ->where('status', 1)
                ->get()
                ->map(function($item) {
                    return (array) $item;
                })->toArray();
    }

    /**
     * Get page size data by ID
     */
    public static function getPageSizeDataById($id, $table = 'page_size')
    {
        $result = DB::table($table)->where('id', $id)->first();
        return $result ? (array) $result : null;
    }

    /**
     * Save size options data to the correct table (CI compatibility)
     */
    public static function savePageSize($data, $table = 'page_size')
    {
        $id = isset($data['id']) ? $data['id'] : '';
        unset($data['id']); // Remove id from data array
        
        // Only use fields that exist in CI database (based on configSizeOptions)
        $allowedFields = ['name', 'name_french', 'total_page', 'status'];
        $filteredData = array_intersect_key($data, array_flip($allowedFields));
        
        if (!empty($id)) {
            // Update existing (without timestamps for CI compatibility)
            $result = DB::table($table)->where('id', $id)->update($filteredData);
            // Return the ID if update was successful (CI project behavior)
            return $result !== false ? $id : 0;
        } else {
            // Create new (without timestamps for CI compatibility)
            $filteredData['status'] = $filteredData['status'] ?? 1;
            $insertId = DB::table($table)->insertGetId($filteredData);
            return $insertId ? $insertId : 0;
        }
    }

    /**
     * Delete size option from correct table
     */
    public static function deletePageSize($id, $table = 'page_size')
    {
        $result = DB::table($table)->where('id', $id)->delete();
        return $result ? 1 : 0;
    }

    /**
     * Toggle status
     */
    public static function toggleStatus($id, $status)
    {
        return DB::table('page_size')->where('id', $id)->update([
            'status' => $status,
            'updated_at' => now()
        ]);
    }

    /**
     * Scope for active page sizes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for shown page sizes
     */
    public function scopeShown($query)
    {
        return $query->where('show_page_size', 1);
    }

    /**
     * Scope for ordered page sizes
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('total_page', 'asc');
    }
}
