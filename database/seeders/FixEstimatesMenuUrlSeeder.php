<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixEstimatesMenuUrlSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Update the estimates menu URL to match the correct route
        $updated = DB::table('sub_modules')
            ->where('sub_module_name', 'Product Estimates')
            ->where('url', 'Products/estimates')
            ->update(['url' => 'estimates']);

        echo "Updated estimates menu URL from 'Products/estimates' to 'estimates'\n";
        echo "Rows affected: " . $updated . "\n";
    }
}
