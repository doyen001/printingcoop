<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CheckProductQuantityMenuSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        echo "=== Product Quantity Menu Check ===\n";
        
        // Find the Product Quantity menu item
        $productQuantityMenu = DB::table('sub_modules')
            ->where('sub_module_name', 'Product Quantity')
            ->first();

        if ($productQuantityMenu) {
            echo "Found Product Quantity menu:\n";
            echo "- ID: " . $productQuantityMenu->id . "\n";
            echo "- Name: " . $productQuantityMenu->sub_module_name . "\n";
            echo "- URL: " . $productQuantityMenu->url . "\n";
            echo "- Module ID: " . $productQuantityMenu->module_id . "\n";
            echo "- Status: " . $productQuantityMenu->status . "\n";
            
            // Check the parent module
            $parentModule = DB::table('modules')
                ->where('id', $productQuantityMenu->module_id)
                ->first();
                
            if ($parentModule) {
                echo "- Parent Module: " . $parentModule->module_name . "\n";
                echo "- Parent URL: " . $parentModule->url . "\n";
            }
        } else {
            echo "Product Quantity menu not found\n";
        }
        
        echo "\n=== All Quantity Related Menus ===\n";
        $quantityMenus = DB::table('sub_modules')
            ->where('sub_module_name', 'LIKE', '%quantity%')
            ->orWhere('sub_module_name', 'LIKE', '%Quantity%')
            ->get();
            
        foreach ($quantityMenus as $menu) {
            echo "- ID: " . $menu->id . ", Name: " . $menu->sub_module_name . ", URL: " . $menu->url . "\n";
        }
        
        echo "\n=== Multiple Attributes Module ===\n";
        $multipleAttributesModule = DB::table('modules')
            ->where('module_name', 'Product Multiple Attributes')
            ->first();
            
        if ($multipleAttributesModule) {
            echo "Found Product Multiple Attributes module:\n";
            echo "- ID: " . $multipleAttributesModule->id . "\n";
            echo "- Name: " . $multipleAttributesModule->module_name . "\n";
            echo "- URL: " . $multipleAttributesModule->url . "\n";
            
            // Get all sub-modules under this module
            $subModules = DB::table('sub_modules')
                ->where('module_id', $multipleAttributesModule->id)
                ->get();
                
            echo "Sub-modules under Product Multiple Attributes:\n";
            foreach ($subModules as $sub) {
                echo "- ID: " . $sub->id . ", Name: " . $sub->sub_module_name . ", URL: " . $sub->url . "\n";
            }
        }
    }
}
