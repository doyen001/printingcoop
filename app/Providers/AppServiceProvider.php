<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share global data with public layout views only (not all views)
        // Optimized with caching to prevent N+1 queries on every page load
        view()->composer(['elements.app', 'elements.*'], function ($view) {
            $website_store_id = config('store.website_store_id', 1);
            $main_store_id = config('store.main_store_id', 1);
            $language_name = config('store.language_name', 'english');
            
            // Cache pages for 1 hour
            $pages = Cache::remember("pages_{$website_store_id}", 3600, function () use ($website_store_id) {
                return \DB::table('pages')
                    ->where('status', 1)
                    ->where('display_on_top_menu', 1)
                    ->where('main_store_id', $website_store_id)
                    ->orderBy('shortOrder', 'asc')
                    ->get()
                    ->map(function($page) {
                        return (array) $page;
                    })
                    ->toArray();
            });
            
            // Cache categories data for 1 hour
            $categoriesData = Cache::remember("categories_menu_{$main_store_id}", 3600, function () use ($main_store_id) {
                return $this->getCategoriesAndSubCategoriesForMainMenu($main_store_id);
            });
            
            // Cache footer categories for 1 hour
            $footerCategory = Cache::remember("footer_categories_{$main_store_id}", 3600, function () use ($main_store_id) {
                $query = \DB::table('categories')
                    ->where('status', 1)
                    ->where('show_footer_menu', 1);
                
                if (in_array($main_store_id, [5, 6])) {
                    $query->whereRaw("FIND_IN_SET(?, store_id)", [$main_store_id]);
                }
                
                return $query->orderBy('category_order', 'asc')->get()->toArray();
            });
            
            // Cache configurations for 1 hour
            $configurations = Cache::remember("configurations_{$website_store_id}", 3600, function () use ($website_store_id) {
                return \DB::table('configurations')
                    ->where('main_store_id', $website_store_id)
                    ->first();
            });
            
            // Get session data for logged in user
            $loginId = session('loginId');
            $loginName = session('loginName');
            $loginPic = session('loginPic');
            
            // MainStoreData: Use configurations data as main store data (main_stores table doesn't exist)
            $configurationsArray = $configurations ? (array) $configurations : [];
            $MainStoreData = $configurationsArray;
            $MainStoreData['language_name'] = ucfirst($language_name);
            $MainStoreData['show_language_translation'] = $configurationsArray['show_language_translation'] ?? 1;
            $MainStoreData['name'] = config('app.name', 'Printing Imprimeur');
            
            // StoreListData: Get language stores for language selector
            // Cache for 1 hour
            $StoreListData = Cache::remember("store_list_{$main_store_id}", 3600, function () use ($main_store_id) {
                return \DB::table('stores')
                    ->join('language', 'language.id', '=', 'stores.langue_id')
                    ->where('stores.status', 1)
                    ->where('stores.main_store_id', $main_store_id)
                    ->select('stores.*', 'language.name as language_name', 'language.id as language_id')
                    ->get()
                    ->map(function($store) {
                        return (array) $store;
                    })
                    ->toArray();
            });
            
            // Share all global variables with views
            $view->with([
                'pages' => $pages,
                'language_name' => $language_name,
                'website_store_id' => $website_store_id,
                'main_store_id' => $main_store_id,
                'categories' => $categoriesData, // Pass full array with 'categories' and 'all_categories_products' keys
                'all_categories_products' => $categoriesData['all_categories_products'] ?? 0,
                'footerCategory' => $footerCategory,
                'configurations' => $configurations ? (array) $configurations : [],
                'loginId' => $loginId,
                'loginName' => $loginName,
                'loginPic' => $loginPic,
                'MainStoreData' => $MainStoreData,
                'StoreListData' => $StoreListData,
            ]);
        });
    }
    
    /**
     * Get categories and subcategories for main menu
     * CI: Category_Model->getCategoriesAndSubCategoriesForMainMenu() lines 173-209
     */
    private function getCategoriesAndSubCategoriesForMainMenu($store_id = null)
    {
        $query = \DB::table('categories')
            ->where('status', 1)
            ->where('show_main_menu', 1);
        
        if (!empty($store_id) && in_array($store_id, [5, 6])) {
            $query->whereRaw("FIND_IN_SET(?, store_id)", [$store_id]);
        }
        
        $categories = $query->orderBy('category_order', 'asc')->get();
        
        if ($categories->isEmpty()) {
            return ['categories' => [], 'all_categories_products' => 0];
        }
        
        $categoryIds = $categories->pluck('id')->toArray();
        
        // Optimize: Get all subcategories in one query
        $subCategories = \DB::table('sub_categories')
            ->where('status', 1)
            ->whereIn('category_id', $categoryIds)
            ->where('show_main_menu', 1)
            ->orderBy('sub_category_order', 'asc')
            ->get()
            ->groupBy('category_id');
        
        $subCategoryIds = $subCategories->flatten()->pluck('id')->toArray();
        
        // Optimize: Get all product counts in one query for subcategories
        $subCategoryProductCounts = [];
        if (!empty($subCategoryIds)) {
            $subCategoryProductCounts = \DB::table('product_subcategory')
                ->join('products', 'product_subcategory.product_id', '=', 'products.id')
                ->whereIn('product_subcategory.sub_category_id', $subCategoryIds)
                ->where('products.status', 1)
                ->select('product_subcategory.sub_category_id', \DB::raw('COUNT(*) as count'))
                ->groupBy('product_subcategory.sub_category_id')
                ->pluck('count', 'sub_category_id')
                ->toArray();
        }
        
        // Optimize: Get all product counts in one query for categories
        $categoryProductCounts = \DB::table('product_category')
            ->join('products', 'product_category.product_id', '=', 'products.id')
            ->whereIn('product_category.category_id', $categoryIds)
            ->where('products.status', 1)
            ->select('product_category.category_id', \DB::raw('COUNT(*) as count'))
            ->groupBy('product_category.category_id')
            ->pluck('count', 'category_id')
            ->toArray();
        
        $data = ['categories' => [], 'all_categories_products' => 0];
        $allCategoryProducts = 0;
        
        foreach ($categories as $category) {
            $categoryArray = (array) $category;
            
            // Get subcategories for this category from grouped data
            $categorySubs = $subCategories->get($category->id, collect([]));
            
            $subCategoriesArray = [];
            foreach ($categorySubs as $subcategory) {
                $subcategoryArray = (array) $subcategory;
                $productsCount = $subCategoryProductCounts[$subcategory->id] ?? 0;
                
                $subcategoryArray['products'] = $productsCount;
                $subcategoryArray['sub_category_total_products'] = $productsCount;
                $subCategoriesArray[] = $subcategoryArray;
            }
            
            $categoryArray['sub_categories'] = $subCategoriesArray;
            
            // Get product count from pre-fetched data
            $categoryProductsCount = $categoryProductCounts[$category->id] ?? 0;
            $categoryArray['total_products'] = $categoryProductsCount;
            $allCategoryProducts += $categoryProductsCount;
            
            $data['categories'][] = $categoryArray;
        }
        
        $data['all_categories_products'] = $allCategoryProducts;
        return $data;
    }
}
