<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixProductQualityMenuUrlSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Find the Product Quality menu item
        $productQualityMenu = DB::table('sub_modules')
            ->where('sub_module_name', 'Product Quality')
            ->first();

        if ($productQualityMenu) {
            echo "Current Product Quality menu URL: " . $productQualityMenu->url . "\n";
            
            // Update to CI-style URL
            $updated = DB::table('sub_modules')
                ->where('id', $productQualityMenu->id)
                ->update(['url' => 'MultipleAttributes/quantity']);

            echo "Updated Product Quality menu URL to: MultipleAttributes/quantity\n";
            echo "Rows affected: " . $updated . "\n";
        } else {
            echo "Product Quality menu not found\n";
            
            // Check if it exists with different name
            $allMenus = DB::table('sub_modules')
                ->where('sub_module_name', 'LIKE', '%quality%')
                ->orWhere('sub_module_name', 'LIKE', '%Quantity%')
                ->get();
                
            echo "Found menus with quality/quantity:\n";
            foreach ($allMenus as $menu) {
                echo "- ID: " . $menu->id . ", Name: " . $menu->sub_module_name . ", URL: " . $menu->url . "\n";
            }
        }
    }
}
