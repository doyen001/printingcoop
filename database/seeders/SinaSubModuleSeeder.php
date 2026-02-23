<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SinaSubModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the Sina sub-module already exists
        $existing = DB::table('sub_modules')
            ->where('module_id', 1)
            ->where('sub_module_name', 'Sina')
            ->first();
        
        if (!$existing) {
            // Insert the Sina sub-module
            DB::table('sub_modules')->insert([
                'module_id' => 1,
                'sub_module_name' => 'Sina',
                'order' => 2,
                'url' => 'Products/Provider/sina',
                'class' => 'Products',
                'action' => 'provider',
                'show_menu' => 1,
                'status' => 1,
                'sub_module_class' => 'fa fas fa-circle',
            ]);
            
            echo "Sina sub-module added successfully.\n";
        } else {
            echo "Sina sub-module already exists.\n";
        }
    }
}
