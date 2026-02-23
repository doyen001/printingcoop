<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Size extends Model
{
    protected $table = 'sizes';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'size_name',
        'size_name_french',
        'status',
        'set_order'
    ];
    
    protected $casts = [
        'status' => 'integer',
        'set_order' => 'integer',
    ];

    /**
     * Get size list (CI equivalent: getSizeList)
     */
    public static function getSizeList($id = null)
    {
        $query = DB::table('sizes')->orderBy('set_order', 'asc');
        
        if (!empty($id)) {
            $result = $query->where('id', $id)->first();
            return $result ? (array) $result : null;
        }
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get size data by ID (CI equivalent: getSizeDataById)
     */
    public static function getSizeDataById($id)
    {
        $result = DB::table('sizes')->where('id', $id)->first();
        return $result ? (array) $result : null;
    }

    /**
     * Save size data (CI equivalent: saveSize)
     */
    public static function saveSize($data)
    {
        $id = isset($data['id']) ? $data['id'] : '';
        unset($data['id']); // Remove id from data array
        
        if (!empty($id)) {
            // Update existing (without timestamps for CI compatibility)
            $result = DB::table('sizes')->where('id', $id)->update($data);
            return $result ? $id : 0;
        } else {
            // Create new (without timestamps for CI compatibility)
            $data['status'] = $data['status'] ?? 1;
            return DB::table('sizes')->insertGetId($data);
        }
    }

    /**
     * Delete size (CI equivalent: deleteSize)
     */
    public static function deleteSize($id)
    {
        $result = DB::table('sizes')->where('id', $id)->delete();
        return $result ? 1 : 0;
    }

    /**
     * Get size dropdown list (CI equivalent: getSizeListDropDown)
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
     * Toggle status (active/inactive)
     */
    public static function toggleStatus($id, $status)
    {
        return DB::table('sizes')->where('id', $id)->update([
            'status' => $status
        ]);
    }

    /**
     * Scope for active sizes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope for ordered sizes
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('set_order', 'asc');
    }
}
