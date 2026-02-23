<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminMenuService
{
    /**
     * Get all modules with sub-modules for the admin menu (CI Project Structure)
     */
    public function getMenuItems(): array
    {
        $adminId = Auth::id();
        $adminRole = Auth::user()->role ?? 'admin';
        
        // Get modules based on admin role (CI uses $AdminModule array)
        if ($adminRole === 'admin') {
            // Admin gets all active modules
            $modules = DB::table('modules')
                ->where('status', 1)
                ->orderBy('order')
                ->orderBy('module_name')
                ->get()
                ->keyBy('id');
        } else {
            // Non-admin gets only assigned modules (CI: $AdminModule)
            $assignedModuleIds = DB::table('admin_modules')
                ->where('admin_id', $adminId)
                ->pluck('module_id')
                ->toArray();
                
            $modules = DB::table('modules')
                ->whereIn('id', $assignedModuleIds)
                ->where('status', 1)
                ->orderBy('order')
                ->orderBy('module_name')
                ->get()
                ->keyBy('id');
        }
        
        // Get sub-modules based on admin role (CI uses $AdminSubModule array)
        if ($adminRole === 'admin') {
            // Admin gets all active sub-modules that should be shown in menu
            $subModules = DB::table('sub_modules')
                ->where('status', 1)
                ->where('show_menu', 1)
                ->orderBy('module_id')
                ->orderBy('order')
                ->orderBy('sub_module_name')
                ->get()
                ->groupBy('module_id');
        } else {
            // Non-admin gets only assigned sub-modules (CI: $AdminSubModule)
            $assignedSubModuleIds = DB::table('admin_sub_modules')
                ->where('admin_id', $adminId)
                ->pluck('sub_module_id')
                ->toArray();
                
            $subModules = DB::table('sub_modules')
                ->whereIn('id', $assignedSubModuleIds)
                ->where('status', 1)
                ->where('show_menu', 1)
                ->orderBy('module_id')
                ->orderBy('order')
                ->orderBy('sub_module_name')
                ->get()
                ->groupBy('module_id');
        }
        
        // Build menu structure (CI Project Structure)
        $menuItems = [];
        foreach ($modules as $module) {
            $menuItems[$module->id] = [
                'module' => $module,
                'sub_modules' => $subModules->get($module->id, collect())
            ];
        }
        
        return $menuItems;
    }
    
    /**
     * Check if a menu item is active based on current route
     */
    public function isMenuItemActive(object $module, object $subModule = null): bool
    {
        $currentRoute = request()->path();
        $currentRouteParts = explode('/', $currentRoute);
        
        if ($subModule) {
            // Check sub-module URL against current route
            $subModuleUrlParts = explode('/', $subModule->url);
            $class = $subModuleUrlParts[0] ?? '';
            $action = $subModuleUrlParts[1] ?? 'index';
            $parameter = $subModuleUrlParts[2] ?? '';
            
            if (count($currentRouteParts) >= 2 && $currentRouteParts[1] === $class) {
                if (count($currentRouteParts) >= 3 && $currentRouteParts[2] === $action) {
                    if (count($currentRouteParts) >= 4 && $currentRouteParts[3] === $parameter) {
                        return true;
                    } elseif (empty($parameter) && count($currentRouteParts) === 3) {
                        return true;
                    }
                }
            }
        } else {
            // Check module URL against current route
            $moduleUrlParts = explode(',', $module->url);
            foreach ($moduleUrlParts as $url) {
                $urlParts = explode('/', trim($url));
                $class = $urlParts[0] ?? '';
                
                if (count($currentRouteParts) >= 2 && $currentRouteParts[1] === $class) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Get module URL array for checking active state (CI Project Structure)
     */
    public function getModuleUrlArray(object $module): array
    {
        // CI project uses comma-separated URLs in the url field
        $urls = explode(',', $module->url);
        return array_map('trim', $urls);
    }
}
