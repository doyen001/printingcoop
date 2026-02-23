<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddSingleAttributesModuleSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Create the "Product Single Attributes" module (CI equivalent)
        $moduleExists = DB::table('modules')->where('module_name', 'Product Single Attributes')->first();
        
        if (!$moduleExists) {
            $moduleId = DB::table('modules')->insertGetId([
                'module_name' => 'Product Single Attributes',
                'order' => 20,
                'url' => 'SingleAttributes',
                'status' => 1,
                'class' => 'fa fab fa-product-hunt',
            ]);
            
            echo "Created Product Single Attributes module with ID: " . $moduleId . "\n";
        } else {
            $moduleId = $moduleExists->id;
            echo "Product Single Attributes module already exists with ID: " . $moduleId . "\n";
        }

        // Add the "Single Attributes" sub-module
        $subModuleExists = DB::table('sub_modules')
            ->where('module_id', $moduleId)
            ->where('sub_module_name', 'Single Attributes')
            ->first();

        if (!$subModuleExists) {
            DB::table('sub_modules')->insert([
                'module_id' => $moduleId,
                'sub_module_name' => 'Single Attributes',
                'order' => 3,
                'url' => 'SingleAttributes/index',
                'class' => 'fa fa-tags',
                'action' => 'index',
                'show_menu' => 1,
                'status' => 1,
                'sub_module_class' => 'fa fa-circle',
            ]);
            
            echo "Created Single Attributes sub-module\n";
        } else {
            echo "Single Attributes sub-module already exists\n";
        }

        // Assign the module to all admin users
        $adminUsers = DB::table('users')->where('role', 'admin')->get();
        
        foreach ($adminUsers as $admin) {
            // Check if admin already has this module assigned
            $existingAssignment = DB::table('admin_modules')
                ->where('admin_id', $admin->id)
                ->where('module_id', $moduleId)
                ->first();

            if (!$existingAssignment) {
                DB::table('admin_modules')->insert([
                    'admin_id' => $admin->id,
                    'module_id' => $moduleId,
                ]);
            }

            // Assign the sub-module to all admin users
            $subModule = DB::table('sub_modules')
                ->where('module_id', $moduleId)
                ->where('sub_module_name', 'Single Attributes')
                ->first();

            if ($subModule) {
                $existingSubAssignment = DB::table('admin_sub_modules')
                    ->where('admin_id', $admin->id)
                    ->where('module_id', $moduleId)
                    ->where('sub_module_id', $subModule->id)
                    ->first();

                if (!$existingSubAssignment) {
                    DB::table('admin_sub_modules')->insert([
                        'admin_id' => $admin->id,
                        'module_id' => $moduleId,
                        'sub_module_id' => $subModule->id,
                    ]);
                }
            }
        }

        echo "Assigned module and permissions to admin users\n";
    }
}
