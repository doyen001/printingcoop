<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VerifyProductQuantityMenuSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        echo "=== Product Quantity Menu Verification ===\n";
        
        // Get the Product Quantity menu
        $menu = DB::table('sub_modules')
            ->where('sub_module_name', 'Product Quantity')
            ->first();
            
        if ($menu) {
            echo "✅ Product Quantity Menu Found:\n";
            echo "   - ID: " . $menu->id . "\n";
            echo "   - Name: " . $menu->sub_module_name . "\n";
            echo "   - URL: " . $menu->url . "\n";
            echo "   - Status: " . ($menu->status ? 'Active' : 'Inactive') . "\n";
            
            // Check parent module
            $parent = DB::table('modules')
                ->where('id', $menu->module_id)
                ->first();
                
            if ($parent) {
                echo "   - Parent Module: " . $parent->module_name . "\n";
                echo "   - Parent URL: " . $parent->url . "\n";
            }
            
            // Expected final URL
            $expectedUrl = '/admin/' . $menu->url;
            echo "\n✅ Expected URL when clicked: " . $expectedUrl . "\n";
            
            // Route verification
            echo "\n✅ Route should be handled by: ProductsController@productQuantity\n";
            echo "✅ View should be: admin.products.product_quantity\n";
            
        } else {
            echo "❌ Product Quantity menu not found!\n";
        }
        
        echo "\n=== Troubleshooting Steps ===\n";
        echo "1. Clear browser cache (Ctrl+F5 or Cmd+Shift+R)\n";
        echo "2. Clear Laravel cache (already done)\n";
        echo "3. Check browser developer tools Network tab\n";
        echo "4. Verify menu is not cached in session\n";
    }
}
