<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Check Provider Command (replicate CI CronJob->CheckProvider)
 * 
 * This command checks and updates provider product information
 * Specifically handles Sina provider integration
 */
class CheckProviderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:check-provider {provider?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update provider product information';

    /**
     * Execute the console command (replicate CI CronJob->CheckProvider lines 9-21)
     */
    public function handle()
    {
        $provider_name = $this->argument('provider');
        
        $provider = $this->getProvider($provider_name);
        
        if (!$provider) {
            $this->error("Provider not found: $provider_name");
            return 1;
        }
        
        if ($provider['name'] == 'sina') {
            $this->checkSina($provider);
        } else {
            $this->error("Unknown provider {$provider['name']}");
            return 1;
        }
        
        $this->info("{$provider['name']} product list was checked successful.");
        return 0;
    }
    
    /**
     * Check Sina provider (replicate CI CronJob->check_sina lines 23-43)
     * 
     * @param array $provider Provider data
     */
    private function checkSina($provider)
    {
        // Get Sina products list
        if (!Session::has('sina_products') || !Session::get('sina_products')) {
            $sina_products = $this->sinaProducts();
            Session::put('sina_products', $sina_products);
        }
        
        $this->updateProvider($provider['id'], Session::get('sina_products'));
        
        // Get products that need updating
        $products = $this->getUpdatingProducts($provider['id']);
        
        $this->info("Updating " . count($products) . " products...");
        
        foreach ($products as $product) {
            $productInfo = $this->sinaProductInfo($product['provider_product_id']);
            $this->updateProductInfo($product, $productInfo);
            
            $this->line("{$product['provider_product_id']}: {$product['name']}");
        }
    }
    
    // ========== Helper Methods ==========
    
    private function getProvider($provider_name)
    {
        $provider = DB::table('providers')->where('name', $provider_name)->first();
        return $provider ? (array) $provider : null;
    }
    
    private function updateProvider($provider_id, $sina_products)
    {
        // Update provider with latest product list
        DB::table('providers')
            ->where('id', $provider_id)
            ->update([
                'product_list' => json_encode($sina_products),
                'updated' => date('Y-m-d H:i:s'),
            ]);
    }
    
    private function getUpdatingProducts($provider_id)
    {
        $products = DB::table('provider_products')
            ->where('provider_id', $provider_id)
            ->where('status', 1)
            ->get();
        
        return array_map(function($item) {
            return (array) $item;
        }, $products->toArray());
    }
    
    private function updateProductInfo($product, $productInfo)
    {
        DB::table('provider_products')
            ->where('id', $product['id'])
            ->update([
                'product_info' => json_encode($productInfo),
                'updated' => date('Y-m-d H:i:s'),
            ]);
    }
    
    /**
     * Get Sina products list
     * This would call the Sina API - placeholder for actual implementation
     */
    private function sinaProducts()
    {
        // This should call sina_products() helper function
        // Placeholder implementation
        if (function_exists('sina_products')) {
            return sina_products();
        }
        
        return [];
    }
    
    /**
     * Get Sina product info
     * This would call the Sina API - placeholder for actual implementation
     */
    private function sinaProductInfo($product_id)
    {
        // This should call sina_product_info() helper function
        // Placeholder implementation
        if (function_exists('sina_product_info')) {
            return sina_product_info($product_id);
        }
        
        return [];
    }
}
