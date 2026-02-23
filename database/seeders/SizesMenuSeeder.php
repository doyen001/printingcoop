<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SizesMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find or create the Product Management module
        $productModule = DB::table('modules')->where('module_name', 'Product Management')->first();
        
        if (!$productModule) {
            $productModuleId = DB::table('modules')->insertGetId([
                'module_name' => 'Product Management',
                'order' => 3,
                'url' => 'Products',
                'status' => 1,
                'class' => 'fa fa-product-hunt',
            ]);
        } else {
            $productModuleId = $productModule->id;
        }

        // Add the Sizes sub-module
        $existingSizeSubModule = DB::table('sub_modules')
            ->where('module_id', $productModuleId)
            ->where('url', 'Sizes')
            ->first();

        if (!$existingSizeSubModule) {
            DB::table('sub_modules')->insert([
                'module_id' => $productModuleId,
                'sub_module_name' => 'Sizes',
                'order' => 5,
                'url' => 'Sizes',
                'class' => 'fa fa-ruler',
                'action' => 'index',
                'show_menu' => 1,
                'status' => 1,
                'sub_module_class' => 'fa fa-circle',
            ]);
        }

        // Assign the module to all admin users (optional)
        $adminUsers = DB::table('users')->where('role', 'admin')->get();
        
        foreach ($adminUsers as $admin) {
            // Check if admin already has this module assigned
            $existingAssignment = DB::table('admin_modules')
                ->where('admin_id', $admin->id)
                ->where('module_id', $productModuleId)
                ->first();

            if (!$existingAssignment) {
                DB::table('admin_modules')->insert([
                    'admin_id' => $admin->id,
                    'module_id' => $productModuleId,
                ]);
            }

            // Assign the sizes sub-module to all admin users
            $sizeSubModule = DB::table('sub_modules')
                ->where('module_id', $productModuleId)
                ->where('url', 'Sizes')
                ->first();

            if ($sizeSubModule) {
                $existingSubAssignment = DB::table('admin_sub_modules')
                    ->where('admin_id', $admin->id)
                    ->where('module_id', $productModuleId)
                    ->where('sub_module_id', $sizeSubModule->id)
                    ->first();

                if (!$existingSubAssignment) {
                    DB::table('admin_sub_modules')->insert([
                        'admin_id' => $admin->id,
                        'module_id' => $productModuleId,
                        'sub_module_id' => $sizeSubModule->id,
                    ]);
                }
            }
        }
    }
}
