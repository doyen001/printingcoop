<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert modules
        $modules = [
            ['id' => 1, 'module_name' => 'Product Management', 'order' => 10, 'url' => 'Products', 'status' => 1, 'class' => 'fa fab fa-product-hunt'],
            ['id' => 2, 'module_name' => 'Customer Management', 'order' => 40, 'url' => 'Users', 'status' => 1, 'class' => 'fa fas fa-users'],
            ['id' => 3, 'module_name' => 'Orders Management', 'order' => 40, 'url' => 'Orders', 'status' => 1, 'class' => 'fa fa-refresh'],
            ['id' => 4, 'module_name' => 'Category Management', 'order' => 50, 'url' => 'Categories', 'status' => 1, 'class' => 'fa fas fa-th-large'],
            ['id' => 5, 'module_name' => 'Content Management', 'order' => 60, 'url' => 'Pages,Banners,Services,Sections', 'status' => 1, 'class' => 'fa fab fa-product-hunt'],
            ['id' => 6, 'module_name' => 'Discount & Promotion Management', 'order' => 70, 'url' => 'Discounts', 'status' => 1, 'class' => 'fa fab fa-product-hunt'],
            ['id' => 7, 'module_name' => 'Settings', 'order' => 90, 'url' => 'Accounts,Configrations', 'status' => 1, 'class' => 'fa fas fa-cog'],
            ['id' => 8, 'module_name' => 'Manage Store', 'order' => 100, 'url' => 'Stores', 'status' => 1, 'class' => 'fa fas fa-cog'],
            ['id' => 9, 'module_name' => 'Manage Sub Admin', 'order' => 110, 'url' => 'Accounts', 'status' => 1, 'class' => 'fa fas fa-cog'],
            ['id' => 10, 'module_name' => 'Blog Management', 'order' => 80, 'url' => 'Blogs', 'status' => 1, 'class' => 'fa fab fa-product-hunt'],
            ['id' => 11, 'module_name' => 'Supports', 'order' => 120, 'url' => 'Supports', 'status' => 1, 'class' => 'fa fab fa-product-hunt'],
            ['id' => 12, 'module_name' => 'Product Single Attributes', 'order' => 20, 'url' => 'SingleAttributes', 'status' => 1, 'class' => 'fa fab fa-product-hunt'],
            ['id' => 13, 'module_name' => 'Product Multiple Attributes', 'order' => 30, 'url' => 'MultipleAttributes', 'status' => 1, 'class' => 'fa fab fa-product-hunt'],
            ['id' => 14, 'module_name' => 'Printers Management', 'order' => 130, 'url' => 'Printers', 'status' => 1, 'class' => 'fa fab fa-product-hunt'],
            ['id' => 15, 'module_name' => 'Neighbor', 'order' => 31, 'url' => 'Neighbor', 'status' => 1, 'class' => 'fa fab fa fa-link'],
        ];

        foreach ($modules as $module) {
            DB::table('modules')->insert([
                'id' => $module['id'],
                'module_name' => $module['module_name'],
                'order' => $module['order'],
                'url' => $module['url'],
                'status' => $module['status'],
                'class' => $module['class'],
            ]);
        }

        // Insert sub-modules
        $subModules = [
            // Product Management sub-modules
            ['module_id' => 1, 'sub_module_name' => 'Products List', 'order' => 1, 'url' => 'Products/index', 'class' => 'Products', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 1, 'sub_module_name' => 'Add/Edit Product', 'order' => 4, 'url' => 'Products/addEdit', 'class' => 'Products', 'action' => 'addEdit', 'show_menu' => 0, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 1, 'sub_module_name' => 'Add/Edit Attributes', 'order' => 7, 'url' => 'Products/addEditAttribute', 'class' => 'Products', 'action' => 'addEditAttribute', 'show_menu' => 0, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 1, 'sub_module_name' => 'Product Estimates', 'order' => 8, 'url' => 'Products/estimates', 'class' => 'Products', 'action' => 'estimates', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 1, 'sub_module_name' => 'Sina', 'order' => 2, 'url' => 'Products/Provider/sina', 'class' => 'Products', 'action' => 'provider', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            
            // Customer Management sub-modules
            ['module_id' => 2, 'sub_module_name' => 'All Customers', 'order' => 1, 'url' => 'Users/index', 'class' => 'Users', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 2, 'sub_module_name' => 'Active Customers', 'order' => 2, 'url' => 'Users/index/active', 'class' => 'Users', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 2, 'sub_module_name' => 'Inactive Customers', 'order' => 3, 'url' => 'Users/index/inactive', 'class' => 'Users', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 2, 'sub_module_name' => 'Preferred Customer', 'order' => 4, 'url' => 'Users/preferredCustomer', 'class' => 'Users', 'action' => 'preferredCustomer', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 2, 'sub_module_name' => 'Subscribe Email', 'order' => 5, 'url' => 'Users/subscribeEmail', 'class' => 'Users', 'action' => 'subscribeEmail', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            
            // Orders Management sub-modules
            ['module_id' => 3, 'sub_module_name' => 'Create Orders', 'order' => 1, 'url' => 'Orders/createOrder', 'class' => 'Orders', 'action' => 'createOrder', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 3, 'sub_module_name' => 'All Orders', 'order' => 2, 'url' => 'Orders/index/all', 'class' => 'Orders', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 3, 'sub_module_name' => 'New Orders', 'order' => 3, 'url' => 'Orders/index/New', 'class' => 'Orders', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 3, 'sub_module_name' => 'Processing Orders', 'order' => 4, 'url' => 'Orders/index/Processing', 'class' => 'Orders', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 3, 'sub_module_name' => 'Shipped Orders', 'order' => 5, 'url' => 'Orders/index/Shipped', 'class' => 'Orders', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 3, 'sub_module_name' => 'Delivered Orders', 'order' => 6, 'url' => 'Orders/index/Delivered', 'class' => 'Orders', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 3, 'sub_module_name' => 'Cancelled Orders', 'order' => 7, 'url' => 'Orders/index/Cancelled', 'class' => 'Orders', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            
            // Category Management sub-modules
            ['module_id' => 4, 'sub_module_name' => 'Categories', 'order' => 1, 'url' => 'Categories', 'class' => 'Categories', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 4, 'sub_module_name' => 'Add/Edit Categories', 'order' => 2, 'url' => 'Categories/addEdit', 'class' => 'Categories', 'action' => 'addEdit', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 4, 'sub_module_name' => 'Sub Categories', 'order' => 3, 'url' => 'Categories/SubCategories', 'class' => 'Categories', 'action' => 'SubCategories', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            ['module_id' => 4, 'sub_module_name' => 'Add/Edit SubCategory', 'order' => 4, 'url' => 'Categories/addEditSubCategory', 'class' => 'Categories', 'action' => 'addEditSubCategory', 'show_menu' => 0, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
            
            // Single Attributes sub-modules
            ['module_id' => 12, 'sub_module_name' => 'Single Attributes', 'order' => 3, 'url' => 'SingleAttributes/index', 'class' => 'SingleAttributes', 'action' => 'index', 'show_menu' => 1, 'status' => 1, 'sub_module_class' => 'fa fas fa-circle'],
        ];

        foreach ($subModules as $subModule) {
            DB::table('sub_modules')->insert([
                'module_id' => $subModule['module_id'],
                'sub_module_name' => $subModule['sub_module_name'],
                'order' => $subModule['order'],
                'url' => $subModule['url'],
                'class' => $subModule['class'],
                'action' => $subModule['action'],
                'show_menu' => $subModule['show_menu'],
                'status' => $subModule['status'],
                'sub_module_class' => $subModule['sub_module_class'],
            ]);
        }
    }
}
