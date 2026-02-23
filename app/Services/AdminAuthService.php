<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminAuthService
{
    /**
     * Authenticate admin with MD5 password compatibility (CI project)
     */
    public static function authenticate($username, $password)
    {
        $admin = DB::table('admins')
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();
            
        if (!$admin) {
            return false;
        }
        
        // Check password - support both MD5 (CI) and bcrypt (Laravel)
        if (strlen($admin->password) === 32 && ctype_xdigit($admin->password)) {
            // MD5 hash (from CI project)
            return md5($password) === $admin->password ? $admin : false;
        } else {
            // Laravel hash
            return Hash::check($password, $admin->password) ? $admin : false;
        }
    }
    
    /**
     * Login admin manually (for CI compatibility)
     */
    public static function login($admin)
    {
        session(['admin_login' => $admin]);
    }
    
    /**
     * Get current logged in admin
     */
    public static function getCurrentAdmin()
    {
        return session('admin_login');
    }
    
    /**
     * Logout admin
     */
    public static function logout()
    {
        session()->forget('admin_login');
    }
    
    /**
     * Check if admin is logged in
     */
    public static function check()
    {
        return !is_null(session('admin_login'));
    }
}
