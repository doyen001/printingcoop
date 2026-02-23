<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AdminAuthService;

class CheckAdminPermissions
{
    /**
     * Handle an incoming request to check admin permissions
     * Based on CI Admin_Controller permission system
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get current authenticated admin (CI-compatible first, then Laravel)
        $admin = AdminAuthService::getCurrentAdmin() ?: auth()->guard('admin')->user();
        
        if (!$admin) {
            return redirect('pcoopadmin')->with('message_error', 'Please login to access admin panel');
        }
        
        // Allow full access to main admin
        if ($admin->role === 'admin') {
            return $next($request);
        }
        
        // Get the current route URL pattern
        $route = $request->route();
        if (!$route) {
            return $next($request);
        }
        
        $uri = $request->path();
        
        // Parse URI to get controller/method/parameters
        $uri_parts = explode('/', $uri);
        
        // Skip permission check for certain routes
        $allowed_routes = [
            'admin/Dashboards',
            'admin/Accounts/logout',
            'admin/Accounts/changePassword'
        ];
        
        $current_route = '';
        if (count($uri_parts) >= 2) {
            $current_route = $uri_parts[0] . '/' . $uri_parts[1];
            if (isset($uri_parts[2])) {
                $current_route .= '/' . $uri_parts[2];
            }
        }
        
        if (in_array($current_route, $allowed_routes)) {
            return $next($request);
        }
        
        // Check if subadmin has permission for this route
        if (!$this->hasPermission($admin->id, $current_route)) {
            return redirect('admin/Dashboards')
                ->with('message_error', 'You are not authorized to access this page');
        }
        
        return $next($request);
    }
    
    /**
     * Check if admin has permission for specific URL
     * Based on CI Module_Model->getSubModuleIdByUrl
     */
    private function hasPermission($admin_id, $url)
    {
        // Get sub_module_id for this URL
        $sub_module = $this->getSubModuleIdByUrl($url);
        
        if (empty($sub_module)) {
            // If no specific sub_module found, allow access (or deny based on requirements)
            return true;
        }
        
        // Check if admin has this sub_module permission
        $permission = DB::table('admin_sub_modules')
            ->where('admin_id', $admin_id)
            ->where('sub_module_id', $sub_module)
            ->first();
            
        return !empty($permission);
    }
    
    /**
     * Get sub_module_id by URL
     * Based on CI Module_Model->getSubModuleIdByUrl
     */
    private function getSubModuleIdByUrl($url)
    {
        $url_data = explode("/", $url);
        $class = isset($url_data[1]) ? $url_data[1] : ''; // admin/Controller
        $action = isset($url_data[2]) ? $url_data[2] : 'index';
        $param = isset($url_data[3]) ? $url_data[3] : '';
        
        if ($class) {
            $mainurl = $class . "/" . $action;
            if (!empty($param)) {
                $mainurl = $mainurl . "/" . $param;
            }
            
            $sub_module = DB::table('sub_modules')
                ->where('url', $mainurl)
                ->where('status', 1)
                ->first();
                
            return $sub_module ? $sub_module->id : null;
        }
        
        return null;
    }
}
