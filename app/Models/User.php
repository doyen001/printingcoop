<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class User extends Model
{
    protected $table = 'users';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $casts = [
        'status' => 'boolean',
        'email_verification' => 'boolean',
        'user_type' => 'integer',
        'preferred_status' => 'boolean',
    ];

    /**
     * Get user list for display (CI equivalent: getUserList)
     */
    public static function getUserList($status = null)
    {
        try {
            $query = DB::table('users');
            
            if (!empty($status)) {
                $query->where('status', $status === 'active' ? 1 : 0);
            }
            
            return $query->orderBy('id', 'desc')->get()->map(function($item) {
                return (array) $item;
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error in User::getUserList: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get users for DataTable (CI equivalent: getDatatableUsers)
     */
    public static function getDatatableUsers($status = null, $start = 0, $length = 10, $search = '')
    {
        try {
            $query = DB::table('users');
            
            if (!empty($status)) {
                $query->where('status', $status === 'active' ? 1 : 0);
            }
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('email', 'LIKE', '%' . $search . '%')
                      ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                      ->orWhere('customer_code', 'LIKE', '%' . $search . '%');
                });
            }
            
            return $query->offset($start)
                       ->limit($length)
                       ->orderBy('id', 'desc')
                       ->get()
                       ->toArray();
        } catch (\Exception $e) {
            Log::error('Error in User::getDatatableUsers: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get total count for DataTable (CI equivalent: getDatatableUsersCount)
     */
    public static function getDatatableUsersCount($status = null, $search = '')
    {
        try {
            $query = DB::table('users');
            
            if (!empty($status)) {
                $query->where('status', $status === 'active' ? 1 : 0);
            }
            
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('email', 'LIKE', '%' . $search . '%')
                      ->orWhere('mobile', 'LIKE', '%' . $search . '%')
                      ->orWhere('customer_code', 'LIKE', '%' . $search . '%');
                });
            }
            
            return $query->count();
        } catch (\Exception $e) {
            Log::error('Error in User::getDatatableUsersCount: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get user by ID (CI equivalent: getUserById)
     */
    public static function getUserById($id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();
            return $user ? (array) $user : null;
        } catch (\Exception $e) {
            Log::error('Error in User::getUserById: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create user with default values for required fields
     * Helper method to ensure all required fields are provided
     */
    public static function createUserWithDefaults($data)
    {
        // Set default values for required fields that might be missing
        $defaults = [
            'status' => 1,
            'user_type' => 1,
            'preferred_status' => 0,
            'email_verification' => 0,
            'active_area' => '',
            'company_name' => '',
            'responsible_name' => '',
            'cp' => '',
            'address' => '',
            'country' => '',
            'region' => '',
            'city' => '',
            'zip_code' => '',
            'request' => '',
            'store_id' => 1, // Use valid store_id (1 exists in stores table)
            'last_login' => null,
            'last_login_ip' => '',
            'created' => now(),
            'updated' => now(),
        ];
        
        // Merge provided data with defaults
        $userData = array_merge($defaults, $data);
        
        return DB::table('users')->insertGetId($userData);
    }

    /**
     * Save user (CI equivalent: saveUser)
     */
    public static function saveUser($data)
    {
        try {
            $id = isset($data['id']) ? $data['id'] : '';
            unset($data['id']);
            
            if (!empty($id)) {
                // Update existing
                $data['updated'] = now();
                $result = DB::table('users')->where('id', $id)->update($data);
                return $result ? $id : 0;
            } else {
                // Create new - set default values for required fields
                $data['created'] = now();
                $data['updated'] = now();
                
                // Set default values for required fields that might be missing
                $data['status'] = $data['status'] ?? 1;
                $data['user_type'] = $data['user_type'] ?? 1;
                $data['preferred_status'] = $data['preferred_status'] ?? 0;
                $data['email_verification'] = $data['email_verification'] ?? 0;
                
                // Set empty defaults for optional fields that might be required
                $data['active_area'] = $data['active_area'] ?? '';
                $data['company_name'] = $data['company_name'] ?? '';
                $data['responsible_name'] = $data['responsible_name'] ?? '';
                $data['cp'] = $data['cp'] ?? '';
                $data['address'] = $data['address'] ?? '';
                $data['country'] = $data['country'] ?? '';
                $data['region'] = $data['region'] ?? '';
                $data['city'] = $data['city'] ?? '';
                $data['zip_code'] = $data['zip_code'] ?? '';
                $data['request'] = $data['request'] ?? '';
                $data['store_id'] = $data['store_id'] ?? 1; // Use valid store_id (1 exists in stores table)
                $data['last_login'] = $data['last_login'] ?? null;
                $data['last_login_ip'] = $data['last_login_ip'] ?? '';
                
                return DB::table('users')->insertGetId($data);
            }
        } catch (\Exception $e) {
            Log::error('Error in User::saveUser: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Delete user (CI equivalent: deleteUser)
     */
    public static function deleteUser($id)
    {
        try {
            $result = DB::table('users')->where('id', $id)->delete();
            return $result ? 1 : 0;
        } catch (\Exception $e) {
            Log::error('Error in User::deleteUser: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Toggle user status (CI equivalent: activeInactive)
     */
    public static function toggleStatus($id, $status)
    {
        try {
            $result = DB::table('users')->where('id', $id)->update(['status' => $status]);
            return $result ? 1 : 0;
        } catch (\Exception $e) {
            Log::error('Error in User::toggleStatus: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Export users to CSV (CI equivalent: exportCSV)
     */
    public static function exportCSV($status = null)
    {
        try {
            $query = DB::table('users');
            
            if (!empty($status)) {
                $query->where('status', $status === 'active' ? 1 : 0);
            }
            
            return $query->orderBy('id', 'desc')->get()->toArray();
        } catch (\Exception $e) {
            Log::error('Error in User::exportCSV: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Import users from CSV (CI equivalent: importCSV)
     */
    public static function importCSV($data)
    {
        try {
            $imported = 0;
            foreach ($data as $row) {
                if (!empty($row['email'])) {
                    // Check if email already exists
                    $exists = DB::table('users')->where('email', $row['email'])->first();
                    if (!$exists) {
                        self::createUserWithDefaults($row);
                        $imported++;
                    }
                }
            }
            return $imported;
        } catch (\Exception $e) {
            Log::error('Error in User::importCSV: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get preferred customer list (CI equivalent: getPreferredCustomerUserList)
     */
    public static function getPreferredCustomerUserList($status = null)
    {
        try {
            $query = DB::table('users')->where('user_type', 2);
            
            if ($status == 'active') {
                $query->where('users.status', 1);
            } elseif ($status == 'inactive') {
                $query->where('users.status', 0);
            }
            
            return $query->orderBy('users.id', 'desc')->get()->toArray();
        } catch (\Exception $e) {
            Log::error('Error in User::getPreferredCustomerUserList: ' . $e->getMessage());
            return [];
        }
    }
}
