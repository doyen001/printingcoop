<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixEstimatesMenuUrlCorrectSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Check current estimates menu
        $currentMenu = DB::table('sub_modules')
            ->where('sub_module_name', 'Product Estimates')
            ->first();

        if ($currentMenu) {
            echo "Current estimates menu URL: " . $currentMenu->url . "\n";
            
            // Update to correct URL that matches the route
            $updated = DB::table('sub_modules')
                ->where('id', $currentMenu->id)
                ->update(['url' => 'Products/estimates']);

            echo "Updated estimates menu URL to: Products/estimates\n";
            echo "Rows affected: " . $updated . "\n";
        } else {
            echo "Product Estimates menu not found\n";
        }
    }
}
