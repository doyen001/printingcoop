<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixSingleAttributesMenuToCISeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Check current single attributes menu
        $currentMenu = DB::table('sub_modules')
            ->where('sub_module_name', 'Single Attributes')
            ->first();

        if ($currentMenu) {
            echo "Current single attributes menu URL: " . $currentMenu->url . "\n";
            
            // Update to CI-style URL
            $updated = DB::table('sub_modules')
                ->where('id', $currentMenu->id)
                ->update(['url' => 'SingleAttributes/index']);

            echo "Updated single attributes menu URL to: SingleAttributes/index\n";
            echo "Rows affected: " . $updated . "\n";
        } else {
            echo "Single Attributes menu not found\n";
        }
    }
}
