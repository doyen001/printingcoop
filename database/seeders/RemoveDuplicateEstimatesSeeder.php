<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveDuplicateEstimatesSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        // Check for duplicate estimates menu items
        $estimateItems = DB::table('sub_modules')
            ->where('sub_module_name', 'like', '%Estimate%')
            ->orWhere('url', 'like', '%estimate%')
            ->get();

        echo "Found " . $estimateItems->count() . " estimate-related menu items:\n";
        
        foreach ($estimateItems as $item) {
            echo "- ID: {$item->id}, Name: {$item->sub_module_name}, URL: {$item->url}\n";
        }

        // If there are duplicates, remove the oldest one (keep the one with highest ID)
        if ($estimateItems->count() > 1) {
            $toKeep = $estimateItems->sortByDesc('id')->first();
            $toRemove = $estimateItems->where('id', '!=', $toKeep->id);
            
            echo "\nRemoving duplicates, keeping ID: {$toKeep->id}\n";
            
            foreach ($toRemove as $item) {
                echo "- Removing ID: {$item->id} ({$item->sub_module_name})\n";
                
                // Remove from admin_sub_modules first
                DB::table('admin_sub_modules')
                    ->where('sub_module_id', $item->id)
                    ->delete();
                
                // Then remove from sub_modules
                DB::table('sub_modules')
                    ->where('id', $item->id)
                    ->delete();
            }
        } else if ($estimateItems->count() == 1) {
            echo "\nOnly one estimates menu item found, no duplicates to remove.\n";
        } else {
            echo "\nNo estimates menu items found.\n";
        }
    }
}
