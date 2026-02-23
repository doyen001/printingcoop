<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveSizesMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Remove the Sizes sub-module from the database
        DB::table('sub_modules')
            ->where('sub_module_name', 'Sizes')
            ->where('url', 'Sizes')
            ->delete();

        // Remove admin assignments for sizes sub-module
        $sizeSubModules = DB::table('sub_modules')
            ->where('sub_module_name', 'Sizes')
            ->pluck('id');

        if ($sizeSubModules->isNotEmpty()) {
            DB::table('admin_sub_modules')
                ->whereIn('sub_module_id', $sizeSubModules)
                ->delete();
        }
    }
}
