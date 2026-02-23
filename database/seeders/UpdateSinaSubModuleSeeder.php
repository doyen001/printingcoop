<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateSinaSubModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update the Sina sub-module URL to point to the main provider page
        DB::table('sub_modules')
            ->where('module_id', 1)
            ->where('sub_module_name', 'Sina')
            ->update([
                'url' => 'Products/Provider',
                'action' => 'provider',
            ]);
            
        echo "Sina sub-module URL updated to point to main provider page.\n";
    }
}
