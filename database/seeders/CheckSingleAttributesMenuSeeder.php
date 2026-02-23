<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CheckSingleAttributesMenuSeeder extends Seeder
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
            echo "Current single attributes menu:\n";
            echo "- ID: " . $currentMenu->id . "\n";
            echo "- Name: " . $currentMenu->sub_module_name . "\n";
            echo "- URL: " . $currentMenu->url . "\n";
            
            // Force update to correct URL
            $updated = DB::table('sub_modules')
                ->where('id', $currentMenu->id)
                ->update(['url' => 'Products/singleAttributes']);

            echo "\nForce updated URL to: Products/singleAttributes\n";
            echo "Rows affected: " . $updated . "\n";
        } else {
            echo "Single Attributes menu not found\n";
        }

        // Clear cache
        echo "\nClearing Laravel cache...\n";
        $this->call('cache:clear');
    }
}
