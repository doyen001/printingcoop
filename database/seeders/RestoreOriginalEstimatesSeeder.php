<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestoreOriginalEstimatesSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Check current estimates menu items
        $estimateItems = DB::table('sub_modules')
            ->where('sub_module_name', 'like', '%Estimate%')
            ->orWhere('url', 'like', '%estimate%')
            ->get();

        echo "Found " . $estimateItems->count() . " estimate-related menu items:\n";
        
        foreach ($estimateItems as $item) {
            echo "- ID: {$item->id}, Name: {$item->sub_module_name}, URL: {$item->url}\n";
        }

        // Remove the newer estimates menu item (ID: 63) and restore the original (ID: 6)
        $newerItem = $estimateItems->where('id', 63)->first();
        $originalItem = $estimateItems->where('id', 6)->first();

        if ($newerItem) {
            echo "\nRemoving newer estimates menu item (ID: 63)\n";
            
            // Remove from admin_sub_modules first
            DB::table('admin_sub_modules')
                ->where('sub_module_id', $newerItem->id)
                ->delete();
            
            // Then remove from sub_modules
            DB::table('sub_modules')
                ->where('id', $newerItem->id)
                ->delete();
        }

        if ($originalItem) {
            echo "Keeping original estimates menu item (ID: 6)\n";
        } else {
            echo "\nOriginal estimates menu item not found, recreating it...\n";
            
            // Find Product Management module
            $productModule = DB::table('modules')->where('module_name', 'Product Management')->first();
            
            if ($productModule) {
                // Recreate original estimates menu item
                DB::table('sub_modules')->insert([
                    'id' => 6,
                    'module_id' => $productModule->id,
                    'sub_module_name' => 'Product Estimates',
                    'order' => 10,
                    'url' => 'Products/estimates',
                    'class' => 'fa fa-calculator',
                    'action' => 'index',
                    'show_menu' => 1,
                    'status' => 1,
                    'sub_module_class' => 'fa fa-circle',
                ]);
                
                echo "Recreated original estimates menu item with ID: 6\n";
            }
        }
    }
}
