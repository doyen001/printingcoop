<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstimatesMenuSeeder extends Seeder
{
    /**
     * Run database seeds.
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

        // Add the Estimates sub-module
        $existingEstimatesSubModule = DB::table('sub_modules')
            ->where('module_id', $productModuleId)
            ->where('url', 'estimates')
            ->first();

        if (!$existingEstimatesSubModule) {
            DB::table('sub_modules')->insert([
                'module_id' => $productModuleId,
                'sub_module_name' => 'Product Estimates',
                'order' => 10,
                'url' => 'estimates',
                'class' => 'fa fa-calculator',
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

            // Assign the estimates sub-module to all admin users
            $estimatesSubModule = DB::table('sub_modules')
                ->where('module_id', $productModuleId)
                ->where('url', 'estimates')
                ->first();

            if ($estimatesSubModule) {
                $existingSubAssignment = DB::table('admin_sub_modules')
                    ->where('admin_id', $admin->id)
                    ->where('module_id', $productModuleId)
                    ->where('sub_module_id', $estimatesSubModule->id)
                    ->first();

                if (!$existingSubAssignment) {
                    DB::table('admin_sub_modules')->insert([
                        'admin_id' => $admin->id,
                        'module_id' => $productModuleId,
                        'sub_module_id' => $estimatesSubModule->id,
                    ]);
                }
            }
        }
    }
}
