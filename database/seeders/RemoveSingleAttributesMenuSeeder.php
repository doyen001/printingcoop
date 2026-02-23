<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveSingleAttributesMenuSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Remove the Single Attributes menu item from the sidebar
        $deleted = DB::table('sub_modules')
            ->where('sub_module_name', 'Single Attributes')
            ->delete();

        echo "Removed Single Attributes menu from sidebar\n";
        echo "Rows affected: " . $deleted . "\n";

        // Also remove from admin_sub_modules
        $deletedAdmin = DB::table('admin_sub_modules')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sub_modules')
                    ->whereRaw('sub_modules.id = admin_sub_modules.sub_module_id')
                    ->where('sub_modules.sub_module_name', 'Single Attributes');
            })
            ->delete();

        echo "Removed Single Attributes from admin permissions\n";
        echo "Rows affected: " . $deletedAdmin . "\n";
    }
}
