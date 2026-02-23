<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixSingleAttributesMenuUrlSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Update the single attributes menu URL to match the correct route
        $updated = DB::table('sub_modules')
            ->where('sub_module_name', 'Single Attributes')
            ->where('url', 'singleAttributes')
            ->update(['url' => 'Products/singleAttributes']);

        echo "Updated single attributes menu URL from 'singleAttributes' to 'Products/singleAttributes'\n";
        echo "Rows affected: " . $updated . "\n";
    }
}
