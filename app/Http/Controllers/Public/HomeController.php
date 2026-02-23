<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{
    /**
     * Display home page (replicate CI Homes->index lines 13-93)
     */
    public function index()
    {
        $data = [];
        
        // Get store IDs from config (lines 26, 31, 62, 76)
        $website_store_id = config('store.website_store_id', 1);
        $main_store_id = config('store.main_store_id', 1);
        $language_name = config('store.language_name', 'English');
        
        $data['page_title'] = 'Home';
        
        // Load banners (line 26)
        $data['Branrers'] = $this->getHomePageBanners($website_store_id);
        
        // Load services (line 29)
        $data['allServices'] = $this->getActiveServices($website_store_id);
        
        // Store-specific sections (lines 31-90)
        if ($website_store_id == 1) {
            // Store ID 1 sections (lines 32-61)
            $data['section_1'] = $this->getSectionById(1);
            $data['section_2'] = $this->getSectionById(2);
            $data['section_3'] = $this->getSectionById(3);
            $data['section_4'] = $this->getSectionById(4);
            $data['section_5'] = $this->getSectionById(5);
            $data['section_6'] = $this->getSectionById(6);
            $data['section_7'] = $this->getSectionById(7);
            
            // Category tags (lines 40-44)
            $data['proudly_display_your_brand_tags'] = $this->getTasgList(1, 1);
            $data['montreal_book_printing_tags'] = $this->getTasgList(1, '', 1);
            $data['our_printed_products_category'] = $this->ourPrintedProductsCategory();
            
            // SEO meta tags (lines 46-61)
            $pageData = $this->getPageDataBySlug('home', $website_store_id);
            if (!empty($pageData)) {
                $data['page_title'] = $pageData['title'];
                $data['meta_page_title'] = $pageData['page_title'];
                $data['meta_description_content'] = $pageData['meta_description_content'];
                $data['meta_keywords_content'] = $pageData['meta_keywords_content'];
                
                // French translations (lines 52-57)
                if ($language_name == 'French') {
                    $data['page_title'] = $pageData['title_french'];
                    $data['meta_page_title'] = $pageData['page_title_french'];
                    $data['meta_description_content'] = $pageData['meta_description_content_french'];
                    $data['meta_keywords_content'] = $pageData['meta_keywords_content_french'];
                }
                
                $data['slug'] = $pageData['slug'];
                $data['pageData'] = $pageData;
            }
        } else if ($website_store_id == 3) {
            // Store ID 3 sections (lines 63-75)
            $data['section_1'] = $this->getSectionById(8);
            $data['section_2'] = $this->getSectionById(10);
            $data['section_3'] = $this->getSectionById(12);
            $data['section_4'] = $this->getSectionById(14);
            $data['section_5'] = $this->getSectionById(16);
            $data['section_6'] = $this->getSectionById(18);
            $data['section_7'] = $this->getSectionById(20);
            
            // Category tags (lines 71-75)
            $data['proudly_display_your_brand_tags'] = $this->getTasgList(1, 1);
            $data['montreal_book_printing_tags'] = $this->getTasgList(1, '', 1);
            $data['our_printed_products_category'] = $this->ourPrintedProductsCategory();
        } else if ($website_store_id == 5) {
            // Store ID 5 sections (lines 77-90)
            $data['section_1'] = $this->getSectionById(9);
            $data['section_2'] = $this->getSectionById(11);
            $data['section_3'] = $this->getSectionById(13);
            $data['section_4'] = $this->getSectionById(15);
            $data['section_5'] = $this->getSectionById(17);
            $data['section_6'] = $this->getSectionById(19);
            $data['section_7'] = $this->getSectionById(21);
            
            // Store 5 specific data (lines 84-87)
            $data['proudly_display_your_brand_tags'] = $this->getTasgList(1, 1, 0, 0, $main_store_id);
            $data['our_ink_printed_products'] = $this->getProductByTagId(11, 30);
            $data['PrinterBrandsLists'] = $this->getActicePrinterBrandsList();
        }
        
        // Add store ID to view data
        $data['website_store_id'] = $website_store_id;
        
        return view('public.home.index', $data);
    }
    
    /**
     * Close COVID message (replicate CI Homes->COVIDMSGClose lines 95-105)
     */
    public function covidMsgClose()
    {
        Cookie::queue('COVID19MSG', 1, 3600 * 24);
        return response()->json(['status' => 'success']);
    }
    
    /**
     * Get home page banners (replicate Banner_Model->getHomePageBanners)
     */
    private function getHomePageBanners($website_store_id)
    {
        return DB::table('banners')
            ->where('store_id', $website_store_id)
            ->where('status', 1)
            ->orderBy('order', 'asc')
            ->get()
            ->toArray();
    }
    
    /**
     * Get active services (replicate Service_Model->getActiveServices)
     */
    private function getActiveServices($website_store_id)
    {
        return DB::table('services')
            ->where('store_id', $website_store_id)
            ->where('status', 1)
            ->orderBy('order', 'asc')
            ->get()
            ->toArray();
    }
    
    /**
     * Get section by ID (replicate Section_Model->getSectionById)
     */
    private function getSectionById($id)
    {
        $section = DB::table('sections')
            ->where('id', $id)
            ->first();
        
        return $section ? (array) $section : [];
    }
    
    /**
     * Get category tags list (replicate Category_Model->getTasgList)
     */
    private function getTasgList($status = 1, $is_featured = null, $is_tag = null, $limit = null, $store_id = null)
    {
        $query = DB::table('categories')
            ->where('status', $status);
        
        if ($is_featured !== null && $is_featured !== '') {
            $query->where('is_featured', $is_featured);
        }
        
        if ($is_tag !== null && $is_tag !== '') {
            $query->where('is_tag', $is_tag);
        }
        
        if ($store_id !== null) {
            $query->where('store_id', $store_id);
        }
        
        if ($limit !== null) {
            $query->limit($limit);
        }
        
        return $query->get()->toArray();
    }
    
    /**
     * Get our printed products category (replicate Category_Model->ourPrintedProductsCategory)
     */
    private function ourPrintedProductsCategory()
    {
        return DB::table('categories')
            ->where('status', 1)
            ->where('our_printed_products', 1)
            ->orderBy('order', 'asc')
            ->get()
            ->toArray();
    }
    
    /**
     * Get page data by slug (replicate Page_Model->getPageDataBySlug)
     */
    private function getPageDataBySlug($slug, $store_id)
    {
        $page = DB::table('pages')
            ->where('slug', $slug)
            ->where('store_id', $store_id)
            ->where('status', 1)
            ->first();
        
        return $page ? (array) $page : [];
    }
    
    /**
     * Get products by tag ID (replicate Product_Model->getProductByTagId)
     */
    private function getProductByTagId($tag_id, $limit = null)
    {
        $query = DB::table('products')
            ->where('status', 1)
            ->whereRaw("FIND_IN_SET(?, tag_ids)", [$tag_id]);
        
        if ($limit !== null) {
            $query->limit($limit);
        }
        
        return $query->get()->toArray();
    }
    
    /**
     * Get active printer brands list (replicate Printer_Model->getActicePrinterBrandsList)
     */
    private function getActicePrinterBrandsList()
    {
        return DB::table('printer_brands')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->toArray();
    }
}
