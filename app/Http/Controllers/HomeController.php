<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

/**
 * HomeController
 * Replicate CI Homes controller exactly
 * CI: application/controllers/Homes.php
 */
class HomeController extends Controller
{
    /**
     * Display homepage
     * CI: Homes->index() lines 13-93
     */
    public function index()
    {
        $website_store_id = config('store.website_store_id', 1);
        $main_store_id = config('store.main_store_id', 1);
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => 'Home',
            'website_store_id' => $website_store_id,
        ];
        
        // Get banners (CI: Banner_Model->getHomePageBanners line 26)
        // Query: WHERE status=1 AND menu_id IS NULL AND product_id IS NULL AND main_store_id=$website_store_id
        $data['Branrers'] = DB::table('banners')
            ->where('status', 1)
            ->whereNull('menu_id')
            ->whereNull('product_id')
            ->where('main_store_id', $website_store_id)
            ->get();
        
        // Get services (CI: Service_Model->getActiveServices line 29)
        // Query: WHERE status=1 AND main_store_id=$website_store_id
        $data['allServices'] = DB::table('services')
            ->where('status', 1)
            ->where('main_store_id', $website_store_id)
            ->get();
        
        // Store-specific sections (CI lines 31-90)
        if ($website_store_id == 1) {
            // Printing Coop sections
            $data['section_1'] = DB::table('sections')->find(1);
            $data['section_2'] = DB::table('sections')->find(2);
            $data['section_3'] = DB::table('sections')->find(3);
            $data['section_4'] = DB::table('sections')->find(4);
            $data['section_5'] = DB::table('sections')->find(5);
            $data['section_6'] = DB::table('sections')->find(6);
            $data['section_7'] = DB::table('sections')->find(7);
            
            // Get tags (CI: Category_Model->getTasgList(1, 1) line 40)
            // Query: WHERE status=1 AND proudly_display_your_brand=1
            $data['proudly_display_your_brand_tags'] = DB::table('tags')
                ->where('status', 1)
                ->where('proudly_display_your_brand', 1)
                ->orderBy('tag_order', 'asc')
                ->get();
            
            // Get tags (CI: Category_Model->getTasgList(1, '', 1) line 41)
            // Query: WHERE status=1 AND montreal_book_printing=1
            $data['montreal_book_printing_tags'] = DB::table('tags')
                ->where('status', 1)
                ->where('montreal_book_printing', 1)
                ->orderBy('tag_order', 'asc')
                ->get();
            
            // Get categories (CI: Category_Model->ourPrintedProductsCategory() line 44)
            // Query: WHERE status=1 AND show_our_printed_product=1
            $categories = DB::table('categories')
                ->where('status', 1)
                ->where('show_our_printed_product', 1)
                ->orderBy('category_order', 'asc')
                ->get();
            
            // Convert to array and add categoryImages like CI project
            $data['our_printed_products_category'] = [];
            foreach ($categories as $category) {
                $categoryArray = (array) $category;
                
                // Add categoryImages like CI project
                $categoryImages = $this->getCategoriesImagesDataBy($category->id);
                $categoryArray['categoryImages'] = $categoryImages;
                
                $data['our_printed_products_category'][] = $categoryArray;
            }
            
            // Get page meta data (CI: Page_Model->getPageDataBySlug line 46)
            $pageData = DB::table('pages')
                ->where('slug', 'home')
                ->where('main_store_id', $website_store_id)
                ->first();
            
            if ($pageData) {
                $data['page_title'] = $pageData->title;
                $data['meta_page_title'] = $pageData->page_title;
                $data['meta_description_content'] = $pageData->meta_description_content;
                $data['meta_keywords_content'] = $pageData->meta_keywords_content;
                
                // French language support (CI lines 52-57)
                if ($language_name == 'french') {
                    $data['page_title'] = $pageData->title_french;
                    $data['meta_page_title'] = $pageData->page_title_french;
                    $data['meta_description_content'] = $pageData->meta_description_content_french;
                    $data['meta_keywords_content'] = $pageData->meta_keywords_content_french;
                }
                
                $data['slug'] = $pageData->slug;
                $data['pageData'] = $pageData;
            }
        } else if ($website_store_id == 3) {
            // ClickImprimerie sections
            $data['section_1'] = DB::table('sections')->find(8);
            $data['section_2'] = DB::table('sections')->find(10);
            $data['section_3'] = DB::table('sections')->find(12);
            $data['section_4'] = DB::table('sections')->find(14);
            $data['section_5'] = DB::table('sections')->find(16);
            $data['section_6'] = DB::table('sections')->find(18);
            $data['section_7'] = DB::table('sections')->find(20);
            
            // Get tags (CI lines 71-72)
            $data['proudly_display_your_brand_tags'] = DB::table('tags')
                ->where('status', 1)
                ->where('proudly_display_your_brand', 1)
                ->orderBy('tag_order', 'asc')
                ->get();
            
            $data['montreal_book_printing_tags'] = DB::table('tags')
                ->where('status', 1)
                ->where('montreal_book_printing', 1)
                ->orderBy('tag_order', 'asc')
                ->get();
            
            // Get categories (CI line 75)
            $categories = DB::table('categories')
                ->where('status', 1)
                ->where('show_our_printed_product', 1)
                ->orderBy('category_order', 'asc')
                ->get();
            
            // Convert to array and add categoryImages like CI project
            $data['our_printed_products_category'] = [];
            foreach ($categories as $category) {
                $categoryArray = (array) $category;
                
                // Add categoryImages like CI project
                $categoryImages = $this->getCategoriesImagesDataBy($category->id);
                $categoryArray['categoryImages'] = $categoryImages;
                
                $data['our_printed_products_category'][] = $categoryArray;
            }
        } else if ($website_store_id == 5) {
            // EcoInk sections
            $data['section_1'] = DB::table('sections')->find(9);
            $data['section_2'] = DB::table('sections')->find(11);
            $data['section_3'] = DB::table('sections')->find(13);
            $data['section_4'] = DB::table('sections')->find(15);
            $data['section_5'] = DB::table('sections')->find(17);
            $data['section_6'] = DB::table('sections')->find(19);
            $data['section_7'] = DB::table('sections')->find(21);
            
            // Get tags (CI line 84)
            // Query: WHERE status=1 AND proudly_display_your_brand=1 AND FIND_IN_SET($main_store_id, store_id)
            $data['proudly_display_your_brand_tags'] = DB::table('tags')
                ->where('status', 1)
                ->where('proudly_display_your_brand', 1)
                ->whereRaw("FIND_IN_SET(?, store_id)", [$main_store_id])
                ->orderBy('tag_order', 'asc')
                ->get();
            
            // Get products by tag (CI: Product_Model->getProductByTagId(11, 30) line 86)
            $data['our_ink_printed_products'] = DB::table('products')
                ->join('product_tags', 'products.id', '=', 'product_tags.product_id')
                ->where('product_tags.tag_id', 11)
                ->where('products.status', 1)
                ->select('products.*')
                ->limit(30)
                ->get();
            
            // Get printer brands (CI: Printer_Model->getActicePrinterBrandsList() line 87)
            $data['PrinterBrandsLists'] = DB::table('printer_brands')
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
        }
        
        // Render view (CI line 92)
        return view('homes.index', $data);
    }
    
    /**
     * Close COVID message
     * CI: Homes->COVIDMSGClose() lines 95-105
     */
    public function COVIDMSGClose()
    {
        Cookie::queue('COVID19MSG', 1, 3600 * 24);
        return response()->json(['success' => true]);
    }
    
    /**
     * Get category images data by category ID
     * Based on CI project's getCategoriesImagesDataBy method
     */
    private function getCategoriesImagesDataBy($categoryId)
    {
        $images = DB::table('categories_images')
            ->where('category_id', $categoryId)
            ->get();
        
        $categoryImages = [];
        foreach ($images as $image) {
            $categoryImages[$image->main_store_id] = [
                'id' => $image->id,
                'image' => $image->image,
                'image_french' => $image->image_french ?? '',
            ];
        }
        
        return $categoryImages;
    }
}
