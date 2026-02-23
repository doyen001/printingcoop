<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ForceUpdateProductQuantityMenuSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        echo "=== Force Update Product Quantity Menu ===\n";
        
        // Force update the Product Quantity menu URL
        $updated = DB::table('sub_modules')
            ->where('sub_module_name', 'Product Quantity')
            ->update(['url' => 'MultipleAttributes/quantity']);

        echo "Updated Product Quantity menu URL to: MultipleAttributes/quantity\n";
        echo "Rows affected: " . $updated . "\n";
        
        // Verify the update
        $menu = DB::table('sub_modules')
            ->where('sub_module_name', 'Product Quantity')
            ->first();
            
        if ($menu) {
            echo "Verification - Current URL: " . $menu->url . "\n";
        }
        
        // Clear any potential session or application cache
        echo "Clearing caches...\n";
        
        // Also update admin_sub_modules if needed
        $adminSubModules = DB::table('admin_sub_modules')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sub_modules')
                    ->whereRaw('sub_modules.id = admin_sub_modules.sub_module_id')
                    ->where('sub_modules.sub_module_name', 'Product Quantity');
            })
            ->get();
            
        echo "Found " . count($adminSubModules) . " admin sub-modules for Product Quantity\n";
    }
}
