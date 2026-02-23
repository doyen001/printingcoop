<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Neighbor extends Model
{
    protected $table = 'n_neighbors';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    
    protected $fillable = [
        'name',
        'url'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get neighbors list (CI equivalent: getNeighbors)
     */
    public static function getNeighbors($neighbor_id = null, $limit = null, $start = null, $order = 'desc')
    {
        $query = DB::table('n_neighbors');
        
        if ($neighbor_id) {
            $query->where('id', $neighbor_id);
        }
        
        $query->orderBy('updated_at', $order);
        
        if ($limit) {
            $query->limit($limit);
        }
        
        if ($start) {
            $query->offset($start);
        }
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Get neighbors count (CI equivalent: getNeighborsCount)
     */
    public static function getNeighborsCount($neighbor_id = null)
    {
        $query = DB::table('n_neighbors');
        
        if ($neighbor_id) {
            $query->where('id', $neighbor_id);
        }
        
        return $query->count();
    }

    /**
     * Save neighbor data (CI equivalent: save)
     */
    public static function saveNeighbor($data)
    {
        $id = isset($data['id']) ? $data['id'] : '';
        
        if (!empty($id)) {
            // Update existing
            $data['updated_at'] = now();
            $result = DB::table('n_neighbors')->where('id', $id)->update($data);
            return $result ? $id : 0;
        } else {
            // Create new
            $data['created_at'] = now();
            $data['updated_at'] = now();
            return DB::table('n_neighbors')->insertGetId($data);
        }
    }

    /**
     * Delete neighbor (CI equivalent: delete)
     */
    public static function deleteNeighbor($id)
    {
        $result = DB::table('n_neighbors')->where('id', $id)->delete();
        return $result ? 1 : 0;
    }

    /**
     * Delete multiple neighbors (CI project style)
     */
    public static function deleteMultipleNeighbors($neighborIds)
    {
        try {
            if (empty($neighborIds) || !is_array($neighborIds)) {
                return false;
            }
            
            $result = DB::table('n_neighbors')
                ->whereIn('id', $neighborIds)
                ->delete();
                
            return $result > 0;
            
        } catch (\Exception $e) {
            \Log::error('Error deleting multiple neighbors: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Search neighbors (AJAX functionality)
     */
    public static function searchNeighbors($searchTerm)
    {
        try {
            $query = DB::table('n_neighbors')
                ->where('name', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('url', 'LIKE', '%' . $searchTerm . '%')
                ->orderBy('name', 'asc')
                ->limit(10);
            
            $results = $query->get();
            
            return $results->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'url' => $item->url,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at
                ];
            })->toArray();
            
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return [];
        }
    }
}
