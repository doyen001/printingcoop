<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Common\ProviderProductInformationType;

/**
 * ProductsController
 * Replicate CI Products controller
 * CI: application/controllers/Products.php
 */
class ProductsController extends Controller
{
    /**
     * Display products listing page
     * CI: Products->index() lines 19-172
     */
    public function index(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        $main_store_data = DB::table('stores')->where('id', $website_store_id)->first();
        
        $category_id = '';
        $category_name = '';
        $category_data = '';
        $sub_category_id = '';
        $sub_category_name = '';
        $sub_category_data = '';
        $printer_brand = '';
        $printer_series = '';
        $printer_models = '';
        
        $title = $language_name == 'french' ? 'toutes catégories' : 'All Categories';
        
        // Get category_id from query string (CI lines 41-43)
        if ($request->has('category_id')) {
            $category_id = base64_decode($request->input('category_id'));
        }
        
        // Check condition for ecoink (CI lines 44-49)
        if ($main_store_data->show_all_categories == 0 && $category_id != 13 && $website_store_id == 5) {
            $category_id = 13;
            return redirect('Products?category_id=' . base64_encode($category_id));
        }
        
        // Get other filters (CI lines 51-54)
        $sub_category_id = $request->has('sub_category_id') ? base64_decode($request->input('sub_category_id')) : null;
        $printer_brand = $request->input('printer_brand', null);
        $printer_series = $request->input('printer_series', null);
        $printer_models = $request->input('printer_models', null);
        
        $url = site_url('Products');
        
        // Get category data (CI lines 56-67)
        if (!empty($category_id)) {
            $data = DB::table('categories')->where('id', $category_id)->first();
            if ($data) {
                $category_name = $data->name;
                $category_data = $data;
                $title = $language_name == 'french' ? ucfirst($data->name_french) : ucfirst($data->name);
                $pageData = $data;
                $url .= '?category_id=' . base64_encode($category_id);
            }
        } else {
            $url .= '?category_id=';
            $pageData = DB::table('pages')
                ->where('slug', 'products')
                ->where('main_store_id', $website_store_id)
                ->first();
        }
        
        // Get sub-category data (CI lines 69-81)
        if (!empty($sub_category_id)) {
            $data = DB::table('sub_categories')->where('id', $sub_category_id)->first();
            if ($data) {
                $sub_category_name = $data->name;
                $sub_category_data = $data;
                $title .= $language_name == 'french' ? " /" . ucfirst($data->name_french) : " /" . ucfirst($data->name);
                $url .= '&sub_category_id=' . base64_encode($sub_category_id);
            }
        } else {
            $url .= '&sub_category_id=';
        }
        
        // Add printer filters to URL (CI lines 83-104)
        if (!empty($printer_brand)) {
            $title .= " / " . $printer_brand;
            $url .= '&printer_brand=' . $printer_brand;
        } else {
            $url .= '&printer_brand=';
        }
        
        if (!empty($printer_series)) {
            $title .= " / " . $printer_series;
            $url .= '&printer_series=' . $printer_series;
        } else {
            $url .= '&printer_series=';
        }
        
        if (!empty($printer_models)) {
            $title .= " / " . $printer_models;
            $url .= '&printer_models=' . $printer_models;
        } else {
            $url .= '&printer_models=';
        }
        
        $page_title = $title;
        
        // Meta tags (CI lines 107-117)
        $meta_page_title = '';
        $meta_description_content = '';
        $meta_keywords_content = '';
        
        if (!empty($pageData)) {
            $meta_page_title = $pageData->page_title ?? '';
            $meta_description_content = $pageData->meta_description_content ?? '';
            $meta_keywords_content = $pageData->meta_keywords_content ?? '';
            
            if ($language_name == 'french') {
                $meta_page_title = $pageData->page_title_french ?? '';
                $meta_description_content = $pageData->meta_description_content_french ?? '';
                $meta_keywords_content = $pageData->meta_keywords_content_french ?? '';
            }
        }
        
        // Sorting (CI lines 118-129)
        $sortBy = $request->input('sort_by', 'name');
        $url .= "&sort_by=" . $sortBy;
        
        $sortByOptions = [
            'name' => ['order_by' => 'name', 'type' => 'asc'],
            'price_low' => ['order_by' => 'price', 'type' => 'asc'],
            'price_high' => ['order_by' => 'price', 'type' => 'desc'],
        ];
        
        $order_by = $sortByOptions[$sortBy]['order_by'] ?? 'name';
        $type = $sortByOptions[$sortBy]['type'] ?? 'asc';
        
        // Pagination (CI lines 130-152)
        $total = $this->getTotalActiveProduct($category_id, $sub_category_id, $printer_brand, $printer_series, $printer_models);
        
        $pageno = $request->input('pageno', 1);
        $no_of_records_per_page = 30;
        $offset = ($pageno - 1) * $no_of_records_per_page;
        $total_pages = ceil($total / $no_of_records_per_page);
        
        // Get products (CI line 141)
        $lists = $this->getActiveProductList($category_id, $sub_category_id, $order_by, $type, $offset, $no_of_records_per_page, $printer_brand, $printer_series, $printer_models);
        
        $prevPage = $pageno - 1;
        $NextPage = $pageno + 1;
        
        if ($total_pages == $pageno) {
            $NextPage = '';
        }
        if ($pageno == 1) {
            $prevPage = '';
        }
        
        // Add category data to products (CI lines 159-169)
        foreach ($lists as $key => $list) {
            $lists[$key]['category'] = DB::table('categories')->where('id', $list['category_id'])->first();
            
            // Get multiple categories
            $multipalCategory = $this->getProductMultipalCategoriesAndSubCategories($list['id']);
            $multipalCategoryData = [];
            foreach ($multipalCategory as $ckey => $cval) {
                $cat = DB::table('categories')->where('id', $ckey)->first();
                if ($cat) {
                    $multipalCategoryData[$ckey] = $cat;
                }
            }
            $lists[$key]['multipalCategory'] = $multipalCategoryData;
        }
        
        // Get categories for sidebar (from MY_Controller line 560-566)
        $categories = $this->getCategoriesAndSubCategoriesForMainMenu($website_store_id);
        
        // Prepare selected category/subcategory for sidebar highlighting
        $selected_category = !empty($category_id) ? base64_encode($category_id) : null;
        $selected_subcategory = !empty($sub_category_id) ? base64_encode($sub_category_id) : null;
        
        $data = [
            'page_title' => $page_title,
            'meta_page_title' => $meta_page_title,
            'meta_description_content' => $meta_description_content,
            'meta_keywords_content' => $meta_keywords_content,
            'category_id' => $category_id,
            'category_name' => $category_name,
            'category_data' => $category_data,
            'sub_category_id' => $sub_category_id,
            'sub_category_name' => $sub_category_name,
            'sub_category_data' => $sub_category_data,
            'printer_brand' => $printer_brand,
            'printer_series' => $printer_series,
            'printer_models' => $printer_models,
            'order' => $sortBy,
            'url' => $url,
            'total' => $total,
            'pageno' => $pageno,
            'total_pages' => $total_pages,
            'NextPage' => $NextPage,
            'prevPage' => $prevPage,
            'lists' => $lists,
            'categories' => $categories,
            'language_name' => $language_name,
            'MainStoreData' => $main_store_data,
            'selected_category' => $selected_category,
            'selected_subcategory' => $selected_subcategory,
            'product_price_currency_symbol' => config('store.product_price_currency_symbol', '$'),
            'product_price_currency' => config('store.product_price_currency', 'price'),
        ];
        
        return view('products.index', $data);
    }
    
    /**
     * Get total active products count
     * Helper method for pagination
     */
    private function getTotalActiveProduct($category_id, $sub_category_id, $printer_brand, $printer_series, $printer_models)
    {
        // Use product_subcategory junction table (CI Product_Model lines 311-326)
        if (!empty($sub_category_id)) {
            $productIds = $this->getProductIdsBySubCategory($category_id, $sub_category_id);
            if (empty($productIds)) {
                return 0;
            }
            $query = DB::table('products')
                ->where('status', 1)
                ->whereIn('id', $productIds);
        } else if (!empty($category_id)) {
            $productIds = $this->getProductIdsByCategory($category_id);
            if (empty($productIds)) {
                return 0;
            }
            $query = DB::table('products')
                ->where('status', 1)
                ->whereIn('id', $productIds);
        } else {
            $query = DB::table('products')->where('status', 1);
        }
        
        if (!empty($printer_brand)) {
            $query->where('printer_brand', $printer_brand);
        }
        
        if (!empty($printer_series)) {
            $query->where('printer_series', $printer_series);
        }
        
        if (!empty($printer_models)) {
            $query->where('printer_models', $printer_models);
        }
        
        return $query->count();
    }
    
    /**
     * Get active product list
     * Helper method for product listing
     */
    private function getActiveProductList($category_id, $sub_category_id, $order_by, $type, $offset, $limit, $printer_brand, $printer_series, $printer_models)
    {
        // Use product_subcategory junction table (CI Product_Model lines 192-228)
        if (!empty($sub_category_id)) {
            $productIds = $this->getProductIdsBySubCategory($category_id, $sub_category_id);
            if (empty($productIds)) {
                return [];
            }
            $query = DB::table('products')
                ->where('status', 1)
                ->whereIn('id', $productIds);
        } else if (!empty($category_id)) {
            $productIds = $this->getProductIdsByCategory($category_id);
            if (empty($productIds)) {
                return [];
            }
            $query = DB::table('products')
                ->where('status', 1)
                ->whereIn('id', $productIds);
        } else {
            $query = DB::table('products')->where('status', 1);
        }
        
        if (!empty($printer_brand)) {
            $query->where('printer_brand', $printer_brand);
        }
        
        if (!empty($printer_series)) {
            $query->where('printer_series', $printer_series);
        }
        
        if (!empty($printer_models)) {
            $query->where('printer_models', $printer_models);
        }
        
        $query->orderBy($order_by, $type)
              ->offset($offset)
              ->limit($limit);
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }
    
    /**
     * Get product multiple categories and subcategories
     * CI: Product_Model->getProductMultipalCategoriesAndSubCategories() lines 2392-2413
     */
    private function getProductMultipalCategoriesAndSubCategories($product_id)
    {
        $categoryQueryData = [];
        
        // Get all categories for this product from product_category table
        $ProductCategories = DB::table('product_category')
            ->where('product_id', $product_id)
            ->get();
        
        foreach ($ProductCategories as $category) {
            $category_id = $category->category_id;
            
            // Get all subcategories for this category and product
            $subCategoryQuery = DB::table('product_subcategory')
                ->where('category_id', $category_id)
                ->where('product_id', $product_id)
                ->get();
            
            $subCategoryQueryData = [];
            foreach ($subCategoryQuery as $val) {
                $subCategoryQueryData[] = $val->sub_category_id;
            }
            
            $categoryQueryData[$category_id] = $subCategoryQueryData;
        }
        
        return $categoryQueryData;
    }
    
    /**
     * Get product IDs by category
     * CI: Product_Model->getProductIdsByCategory() lines 295-309
     */
    private function getProductIdsByCategory($category_id)
    {
        $productIds = DB::table('product_category')
            ->where('category_id', $category_id)
            ->pluck('product_id')
            ->toArray();
        
        return $productIds;
    }
    
    /**
     * Get product IDs by subcategory
     * CI: Product_Model->getProductIdsBySubCategory() lines 311-326
     */
    private function getProductIdsBySubCategory($category_id, $sub_category_id)
    {
        $productIds = DB::table('product_subcategory')
            ->where('category_id', $category_id)
            ->where('sub_category_id', $sub_category_id)
            ->pluck('product_id')
            ->toArray();
        
        return $productIds;
    }
    
    /**
     * Get categories and subcategories for main menu
     * Helper method (from MY_Controller)
     */
    private function getCategoriesAndSubCategoriesForMainMenu($website_store_id)
    {
        $categories = DB::table('categories')
            ->where('status', 1)
            ->whereRaw("FIND_IN_SET(?, store_id)", [$website_store_id])
            ->orderBy('name', 'asc')
            ->get();
        
        $result = [
            'categories' => [],
            'all_categories_products' => 0,
        ];
        
        foreach ($categories as $category) {
            $catData = (array) $category;
            
            // Get subcategories
            $subCategories = DB::table('sub_categories')
                ->where('category_id', $category->id)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
            
            $catData['sub_categories'] = [];
            foreach ($subCategories as $subCat) {
                $subCatData = (array) $subCat;
                // Count products using product_subcategory junction table
                $productIds = DB::table('product_subcategory')
                    ->where('category_id', $category->id)
                    ->where('sub_category_id', $subCat->id)
                    ->pluck('product_id')
                    ->toArray();
                $subCatData['sub_category_total_products'] = !empty($productIds) 
                    ? DB::table('products')->where('status', 1)->whereIn('id', $productIds)->count()
                    : 0;
                $catData['sub_categories'][] = $subCatData;
            }
            
            // Get total products for this category using product_category junction table
            $productIds = DB::table('product_category')
                ->where('category_id', $category->id)
                ->pluck('product_id')
                ->toArray();
            
            $catData['total_products'] = !empty($productIds)
                ? DB::table('products')->where('status', 1)->whereIn('id', $productIds)->count()
                : 0;
            
            $result['all_categories_products'] += $catData['total_products'];
            $result['categories'][] = $catData;
        }
        
        return $result;
    }
    
    /**
     * View single product details
     * CI: Products->view() lines 174-294
     * CI: Product_Model->getProductList() lines 112-150
     */
    public function view($id = null)
    {
        if (empty($id)) {
            return redirect('/');
        }
        
        $id = base64_decode($id);
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        $main_store_id = config('store.main_store_id', 1);
        
        // Get product data with joins (CI: Product_Model->getProductList() lines 114-149)
        $Product = DB::table('products as Product')
            ->leftJoin('categories as Category', 'Category.id', '=', 'Product.category_id')
            ->leftJoin('sub_categories as SubCategory', 'SubCategory.id', '=', 'Product.sub_category_id')
            ->leftJoin('provider_products', 'provider_products.product_id', '=', 'Product.id')
            ->where('Product.id', $id)
            ->select('Product.*', 
                     'Category.name as category_name', 'Category.name_french as category_name_french',
                     'SubCategory.name as sub_category_name', 'SubCategory.name_french as sub_category_name_french',
                     'provider_products.provider_product_id')
            ->orderBy('Product.updated', 'desc')
            ->first();
        
        if (!$Product) {
            return redirect('/');
        }
        
        $Product = (array) $Product;
        
        // Get product descriptions (CI: Product_Model->getProductDescriptionById() lines 1523-1531)
        $ProductDescriptions = DB::table('product_descriptions')
            ->where('product_id', $id)
            ->get()
            ->map(function($item) {
                return (array) $item;
            })
            ->toArray();
        
        // Get product templates (CI: Product_Model->getProductTemplatesById() lines 1533-1541)
        $ProductTemplates = DB::table('product_templates')
            ->where('product_id', $id)
            ->get()
            ->map(function($item) {
                return (array) $item;
            })
            ->toArray();
        
        // Get product images (CI: ProductImage_Model->getProductImageDataByProductId() lines 7-15)
        $ProductImages = DB::table('product_images')
            ->where('product_id', $id)
            ->get()
            ->map(function($item) {
                return (array) $item;
            })
            ->toArray();
        
        // Get multiple categories (CI lines 200-208)
        $multipalCategory = $this->getProductMultipalCategoriesAndSubCategories($id);
        $multipalCategoryData = [];
        foreach ($multipalCategory as $ckey => $cval) {
            $category = DB::table('categories')->where('id', $ckey)->first();
            if ($category) {
                $multipalCategoryData[$ckey] = (array) $category;
            }
        }
        $Product['multipalCategoryData'] = $multipalCategoryData;
        
        // Check condition for ecoink (CI lines 211-216)
        $main_store_data = DB::table('stores')->where('id', $main_store_id)->first();
        if ($main_store_data && $main_store_data->show_all_categories == 0 
            && !array_key_exists(13, $multipalCategoryData) 
            && $main_store_id == 5) {
            $category_id = 13;
            return redirect('Products?category_id=' . base64_encode($category_id));
        }
        
        // Get product attributes for frontend display (CI project style)
        $ProductAttributes = [];
        $attributeData = DB::table('product_attribute_datas')
            ->join('product_attributes', 'product_attributes.id', '=', 'product_attribute_datas.attribute_id')
            ->where('product_attribute_datas.product_id', $id)
            ->where('product_attributes.status', 1)
            ->select('product_attribute_datas.*', 'product_attributes.name as attribute_name', 'product_attributes.name_french as attribute_name_french')
            ->orderBy('product_attribute_datas.show_order', 'asc')
            ->get();
        
        foreach ($attributeData as $attr) {
            $attribute_id = $attr->attribute_id;
            
            // Get attribute items
            $items = DB::table('product_attribute_item_datas')
                ->join('product_attribute_items', 'product_attribute_items.id', '=', 'product_attribute_item_datas.attribute_item_id')
                ->where('product_attribute_item_datas.product_id', $id)
                ->where('product_attribute_item_datas.attribute_id', $attribute_id)
                ->select('product_attribute_item_datas.*', 'product_attribute_items.item_name', 'product_attribute_items.item_name_french')
                ->orderBy('product_attribute_item_datas.show_order', 'asc')
                ->get();
            
            $attribute_items = [];
            foreach ($items as $item) {
                $attribute_items[$item->attribute_item_id] = (array) $item;
            }
            
            $ProductAttributes[$attribute_id] = [
                'data' => (array) $attr,
                'items' => $attribute_items
            ];
        }
        
        // Get product sizes/quantities (CI: Product_Model->ProductQuantityDropDwon() lines 1649-1666)
        $ProductSizes = DB::table('product_quantity')
            ->join('quantity', 'product_quantity.qty', '=', 'quantity.id')
            ->where('product_quantity.product_id', $id)
            ->where('quantity.status', 1)
            ->select('product_quantity.price', 'product_quantity.qty', 'quantity.name as qty_name', 'quantity.name_french as qty_name_french')
            ->groupBy('product_quantity.qty', 'product_quantity.price', 'quantity.name', 'quantity.name_french')
            ->orderBy('quantity.name', 'asc')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->qty => (array) $item];
            })
            ->toArray();
        
        // Get product pages and sheets (CI: Product_Model lines 2066-2095)
        $ProductPages = DB::table('page_size')
            ->where('status', 1)
            ->orderBy('total_page', 'asc')
            ->get()
            ->map(function($item) {
                return (array) $item;
            })
            ->toArray();
        
        $ProductSheets = DB::table('sheets')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($item) {
                return (array) $item;
            })
            ->toArray();
        
        $pageQuantity = DB::table('page_quantity')
            ->where('status', 1)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function($item) {
                return (array) $item;
            })
            ->toArray();
        
        // Check cart for this product (CI lines 234-249)
        $productRowid = '';
        $productQty = 0;
        
        // Get attributes and attribute items - new structure (CI: Product_Model lines 3287-3400)
        // Get attributes for product detail form (CI project style using product_attribute_map)
        $attributeMapData = DB::table('product_attribute_map')
            ->join('attributes', 'attributes.id', '=', 'product_attribute_map.attribute_id')
            ->where('product_attribute_map.product_id', $id)
            ->select('product_attribute_map.attribute_id', 'product_attribute_map.product_id', 'product_attribute_map.show_order', 
                     'product_attribute_map.use_items', 'product_attribute_map.use_percentage', 'product_attribute_map.value_min', 
                     'product_attribute_map.value_max', 'product_attribute_map.additional_fee', 'product_attribute_map.fee_apply_size',
                     'product_attribute_map.fee_apply_width', 'product_attribute_map.fee_apply_length', 'product_attribute_map.fee_apply_diameter',
                     'product_attribute_map.fee_apply_pages',
                     'attributes.name', 'attributes.label', 'attributes.label_fr', 'attributes.type')
            ->orderBy('product_attribute_map.show_order', 'asc')
            ->get();
        
        // Add item_count for each attribute
        $attributes = $attributeMapData->map(function($item) use ($id) {
            $itemArray = (array) $item;
            $itemArray['item_count'] = DB::table('product_attribute_item_map')
                ->where('product_id', $id)
                ->where('attribute_id', $item->attribute_id)
                ->distinct('attribute_item_id')
                ->count();
            return $itemArray;
        })->toArray();
        $attributes_count = count($attributes);
        
        // Get attribute items (CI project style using product_attribute_item_datas)
        $attribute_items = [];
        $attribute_items_count = 0;
        
        $attributeItemMapData = DB::table('product_attribute_item_map')
            ->join('attributes', 'attributes.id', '=', 'product_attribute_item_map.attribute_id')
            ->join('attribute_items', 'attribute_items.id', '=', 'product_attribute_item_map.attribute_item_id')
            ->join('product_attribute_map', function($join) use ($id) {
                $join->on('product_attribute_map.product_id', '=', 'product_attribute_item_map.product_id')
                     ->on('product_attribute_map.attribute_id', '=', 'product_attribute_item_map.attribute_id');
            })
            ->where('product_attribute_item_map.product_id', $id)
            ->select('product_attribute_item_map.id',
                     'attributes.name AS attribute_name', 'attributes.label AS label', 'attributes.label_fr AS label_fr', 'attributes.type',
                     'attribute_items.name AS attribute_item_name', 'attribute_items.name_fr AS attribute_item_name_fr',
                     'product_attribute_item_map.product_id', 'product_attribute_item_map.attribute_id', 'product_attribute_item_map.attribute_item_id', 
                     'product_attribute_item_map.additional_fee', 'product_attribute_item_map.show_order')
            ->orderByRaw('product_attribute_map.show_order, attributes.type, attributes.name, product_attribute_item_map.show_order, attribute_items.name')
            ->get();
        
        $attribute_items = $attributeItemMapData->map(function($item) {
            $itemArray = (array) $item;
            // Convert all values to strings
            return array_map(function($value) {
                return is_null($value) ? '' : (string) $value;
            }, $itemArray);
        })->toArray();
        $attribute_items_count = count($attribute_items);
        
        // Check provider binding (CI lines 258-290)
        $provider = DB::table('providers')->where('name', 'sina')->first();
        $providerProduct = null;
        $providerData = false;
        
        if ($provider) {
            $providerProduct = DB::table('provider_products')
                ->where('provider_id', $provider->id)
                ->where('product_id', $id)
                ->first();
            
            if ($providerProduct) {
                // Get option groups using provider_product_options joined with provider_options
                // CI: Provider_Model->getProductOptionGroups() lines 620-631
                $optionGroups = DB::table('provider_product_options')
                    ->select('provider_options.*', 'product_attributes.name as attribute_name', 'product_attributes.name_french as attribute_name_french', DB::raw('MIN(provider_product_options.id) as min_option_id'))
                    ->join('provider_options', 'provider_options.id', '=', 'provider_product_options.option_id')
                    ->leftJoin('product_attributes', 'product_attributes.id', '=', 'provider_options.attribute_id')
                    ->where('provider_product_options.provider_id', $provider->id)
                    ->where('provider_product_options.provider_product_id', $providerProduct->provider_product_id)
                    ->groupBy('provider_options.id', 'provider_options.provider_id', 'provider_options.provider_option_id', 'provider_options.name', 'provider_options.label', 'provider_options.type', 'provider_options.attribute_id', 'provider_options.html_type', 'provider_options.sort_order', 'product_attributes.name', 'product_attributes.name_french')
                    ->orderBy('provider_options.type')
                    ->orderBy('min_option_id')
                    ->get();
                
                $options = [];
                foreach ($optionGroups as $item) {
                    $itemArray = (array) $item;
                    // Add option_id field (same as provider_option_id) for compatibility with CI view
                    $itemArray['option_id'] = $item->provider_option_id;
                    $options[$item->id] = $itemArray;
                }
                
                // Get option values using provider_product_options
                // CI: Provider_Model->getProductOptionValues() lines 625-634
                $optionValues = DB::table('provider_product_options')
                    ->select('provider_option_values.*', 'provider_options.type as option_type', 'provider_product_options.price_rate as price_rate', 'provider_product_options.provider_option_value_id', 'provider_product_options.value')
                    ->join('provider_options', 'provider_options.id', '=', 'provider_product_options.option_id')
                    ->leftJoin('provider_option_values', function($join) {
                        $join->on('provider_option_values.option_id', '=', 'provider_product_options.option_id')
                             ->on('provider_option_values.provider_option_value_id', '=', 'provider_product_options.provider_option_value_id')
                             ->on('provider_option_values.value', '=', 'provider_product_options.value');
                    })
                    ->where('provider_product_options.provider_id', $provider->id)
                    ->where('provider_product_options.provider_product_id', $providerProduct->provider_product_id)
                    ->orderBy('provider_options.sort_order')
                    ->orderBy('provider_options.type')
                    ->orderBy('provider_product_options.id')
                    ->orderBy('provider_product_options.provider_option_value_id')
                    ->get();
                
                foreach ($optionValues as $item) {
                    // Skip items where provider_option_value_id or value is null (CI: Products.php lines 194-195, 591-592)
                    if ($item->provider_option_value_id == null || $item->value == null) {
                        continue;
                    }
                    
                    $option = &$options[$item->option_id];
                    if (!isset($option['values'])) {
                        $option['values'] = [];
                    }
                    $option['values'][] = (array) $item;
                }
                
                // Create provider array to match view's array syntax
                $providerData = [
                    'id' => $provider->id,
                    'product_id' => $id,
                    'options' => $options,
                    'shipping_extra_days' => $providerProduct->shipping_extra_days ?? 0,
                    'price_rate' => $providerProduct->price_rate ?? 1,
                ];
            }
        }
        
        $data = [
            'page_title' => 'Product Details',
            'Product' => $Product,
            'ProductDescriptions' => $ProductDescriptions,
            'ProductTemplates' => $ProductTemplates,
            'ProductImages' => $ProductImages,
            'ProductAttributes' => $ProductAttributes,
            'ProductSizes' => $ProductSizes,
            'ProductPages' => $ProductPages,
            'ProductSheets' => $ProductSheets,
            'pageQuantity' => $pageQuantity,
            'productRowid' => $productRowid,
            'productQty' => $productQty,
            'attributes' => $attributes,
            'attribute_items' => $attribute_items,
            'provider' => $providerData,
            'providerProduct' => $providerProduct,
        ];
        
        return view('products.view', $data);
    }
    
    /**
     * Email subscribe (CI: Products->emailSubscribe lines 390-434)
     */
    public function emailSubscribe(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect('/');
        }
        
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribe_emails,email',
        ]);
        
        $language_name = config('store.language_name', 'english');
        $main_store_id = config('store.main_store_id', 1);
        
        $response = [
            'status' => 'error',
            'msg' => '',
            'errors' => [],
        ];
        
        if ($validator->fails()) {
            $response['errors'] = $validator->errors()->toArray();
        } else {
            $postData = [
                'email' => $request->input('email'),
                'store_id' => $main_store_id,
            ];
            
            if (DB::table('subscribe_emails')->insert($postData)) {
                $response['status'] = 'success';
                $response['msg'] = $language_name == 'french'
                    ? "Votre adresse e-mail s'est abonnée avec succès."
                    : 'Your email id subscribe successfully.';
            } else {
                $response['msg'] = $language_name == 'french'
                    ? "Votre adresse e-mail s'est abonnée sans succès"
                    : 'Your review posted unsuccessfully.';
            }
        }
        
        return response()->json($response);
    }
    
    /**
     * Add product to cart (CI: ShoppingCarts->addToCart() lines 38-741)
     * Follows CI project pattern from ShoppingCartsController
     */
    public function addToCart(Request $request)
    {
        // Handle both regular and provider form data structures
        $params = [];
        
        if ($request->has('params')) {
            // Parse serialized form data
            parse_str($request->input('params'), $params);
        } else {
            // Handle direct POST data (fallback)
            $params = $request->all();
        }
        
        $json = ['status' => 0, 'msg' => ''];
        $language_name = config('store.language_name', 'english');
        $cart = new \App\Services\CartService();
        
        $product_id = $params['product_id'] ?? null;
        $quantity = $params['quantity'] ?? 1;
        $price = $params['price'] ?? 0;
        
        $product_quantity_id = $params['product_quantity_id'] ?? null;
        $product_size_id = $params['product_size_id'] ?? null;
        $add_length_width = $params['add_length_width'] ?? null;
        $depth_add_length_width = $params['depth_add_length_width'] ?? null;
        $page_add_length_width = $params['page_add_length_width'] ?? null;
        $recto_verso = $params['recto_verso'] ?? null;
        $recto_verso_price = $params['recto_verso_price'] ?? 0;
        $votre_text = $params['votre_text'] ?? '';
        $file_comments = $params['file_comments'] ?? [];
        
        $productData = DB::table('products')->where('id', $product_id)->first();
        if (!$productData) {
            $json['msg'] = $language_name == 'french' ? "Le produit n'existe pas" : 'Product does not exist';
            echo json_encode($json);
            return;
        }
        
        $productData = (array) $productData;
        
        // Provider handling - CI: lines 67-93
        $provider_id = $params['provider_id'] ?? null;
        if ($provider_id) {
            $productOptions = $params['productOptions'] ?? [];
            $providerProduct = DB::table('provider_products')
                ->where('provider_id', $provider_id)
                ->where('product_id', $product_id)
                ->first();
                
            if ($providerProduct) {
                // Get detailed provider options from database
                $providerOptions = [];
                foreach ($productOptions as $optionName => $optionValue) {
                    // Find the option details from provider_options table
                    $optionDetails = DB::table('provider_options')
                        ->where('provider_id', $provider_id)
                        ->where('name', $optionName)
                        ->first();
                        
                    if ($optionDetails) {
                        // Find the option value details
                        $optionValueDetails = DB::table('provider_option_values')
                            ->where('option_id', $optionDetails->id)
                            ->where('provider_option_value_id', $optionValue)
                            ->first();
                            
                        $providerOptions[] = (object) [
                            'id' => $optionDetails->id,
                            'provider_id' => $provider_id,
                            'provider_option_id' => $optionDetails->provider_option_id ?? null,
                            'name' => $optionName,
                            'label' => $optionDetails->label ?? $optionName,
                            'type' => $optionDetails->type ?? 0,
                            'attribute_id' => $optionDetails->attribute_id ?? null,
                            'html_type' => $optionDetails->html_type ?? null,
                            'sort_order' => $optionDetails->sort_order ?? 0,
                            'provider_option_value_id' => $optionValue,
                            'value' => $optionValueDetails->value ?? $optionValue,
                            'attribute_name' => $optionDetails->attribute_name ?? null,
                            'attribute_name_french' => $optionDetails->attribute_name_french ?? null,
                        ];
                    }
                }
                
                $productOptions = (object) [
                    'provider_id' => $provider_id,
                    'provider_product_id' => $providerProduct->provider_product_id,
                    'provider_options' => $productOptions,
                    'information_type' => $providerProduct->information_type ?? 0,
                    'options' => $providerOptions,
                ];
            }
        } else {
            // Regular attribute handling - CI: lines 95-103
            $productOptions = $this->attributeDataFromIds($product_id, $params['attributes'] ?? []);
            
            if (isset($productOptions['error'])) {
                $json['msg'] = $productOptions['error'];
                echo json_encode($json);
                return;
            }
        }
        
        // Product size and quantity handling - CI: lines 105-123
        $product_size = [];
        if (!empty($product_quantity_id)) {
            $quantityData = DB::table('product_quantity')
                ->where('id', $product_quantity_id)
                ->first();
            if ($quantityData) {
                $qty_ext_price = $quantityData->price ?? 0;
                $price = $price + $qty_ext_price;
                $product_size['product_quantity'] = $quantityData->qty_name ?? '';
                $product_size['product_quantity_french'] = $quantityData->qty_name_french ?? '';
            }
        }
        
        if (!empty($product_size_id)) {
            $sizeData = DB::table('product_size')
                ->where('id', $product_size_id)
                ->first();
            if ($sizeData) {
                $extra_price = $sizeData->extra_price ?? 0;
                $price = $price + $extra_price;
                $product_size['product_size'] = $sizeData->size_name ?? '';
                $product_size['product_size_french'] = $sizeData->size_name_french ?? '';
            }
        }
        
        // Add attribute array to match CI project structure
        $product_size['attribute'] = [];
        
        // Width/Length calculations - CI: lines 141-152
        $product_width_length = [];
        if (!empty($add_length_width)) {
            $product_length = $params['product_length'] ?? 0;
            $product_width = $params['product_width'] ?? 0;
            
            if (empty($product_length)) {
                $json['msg'] = $language_name == 'french' ? 'Veuillez saisir la longueur' : 'Please enter length';
                echo json_encode($json);
                return;
            }
            
            if (empty($product_width)) {
                $json['msg'] = $language_name == 'french' ? 'Veuillez saisir la largeur' : 'Please enter width';
                echo json_encode($json);
                return;
            }
            
            $rq_area = $product_length * $product_width;
            $extra_price = ($productData['min_length_min_width_price'] ?? 0) * $rq_area;
            
            if (!empty($params['length_width_color'])) {
                if ($params['length_width_color'] == 'black') {
                    $extra_price = ($productData['length_width_unit_price_black'] ?? 0) * $rq_area;
                } else if ($params['length_width_color'] == 'color') {
                    $extra_price = ($productData['length_width_price_color'] ?? 0) * $rq_area;
                }
            }
            
            if (!empty($params['product_total_page'])) {
                $extra_price *= $params['product_total_page'];
            }
            
            $price += $extra_price;
            $product_width_length = [
                'product_width' => $product_width,
                'product_length' => $product_length,
                'product_total_page' => $params['product_total_page'] ?? '',
                'length_width_color' => $params['length_width_color'] ?? '',
            ];
        }
        
        // Recto/Verso price calculation - CI: lines 180-188
        if (!empty($recto_verso) && $recto_verso == "Yes" && !empty($recto_verso_price)) {
            $price = $price + (($price * $recto_verso_price) / 100);
        }
        
        $recto_verso_french = '';
        if (!empty($recto_verso)) {
            $recto_verso_french = $recto_verso == 'Yes' ? 'Oui' : 'Non';
        }
        
        // Prepare cart data - CI: lines 190-227
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(['(', ')', "'", ','], '', $productData['name']));
        $name_french = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(['(', ')', "'", ','], '', $productData['name_french'] ?? $productData['name']));
        
        // Get cart images from session
        $cart_images = session("product_id.$product_id") ?? [];

        // Merge any inline comments from the add-to-cart form
        if (!empty($cart_images) && !empty($file_comments)) {
            foreach ($cart_images as $key => &$image) {
                if (isset($file_comments[$key])) {
                    $image['cumment'] = $file_comments[$key];

                    // Keep session in sync with latest comment
                    $sessionKey = "product_id.$product_id.$key";
                    session([$sessionKey => $image]);
                }
            }
            unset($image);
        }
        
        // Check if file upload is required (website_store_id != 5)
        $website_store_id = config('store.website_store_id', 1);
        if ($website_store_id != 5 && empty($cart_images)) {
            $json['msg'] = $language_name == 'french' 
                ? 'Veuillez télécharger le fichier produit' 
                : 'Please upload product file';
            echo json_encode($json);
            return;
        }
        
        $data = [
            'id' => $productData['id'],
            'qty' => $quantity,
            'price' => $price,
            'name' => $name,
            'name_french' => $name_french,
            'options' => [
                'product_id' => $productData['id'],
                'product_image' => $productData['product_image'] ?? '',
                'cart_images' => $cart_images,
                'provider_product_id' => $provider_id ? ($providerProduct->provider_product_id ?? null) : null,
                'attribute_ids' => $productOptions,
                'product_size' => $product_size,
                'product_width_length' => $product_width_length,
                'product_depth_length_width' => [],
                'page_product_width_length' => [],
                'recto_verso' => $recto_verso,
                'recto_verso_french' => $recto_verso_french,
                'votre_text' => $votre_text,
            ],
        ];
        
        // Insert to cart and get response - CI: lines 229-256
        if ($cart->insert($data)) {
            $items = $cart->contents();
            $row_id = '';
            $tquantity = '';
            foreach ($items as $key => $item) {
                if ($item['id'] == $product_id) {
                    $row_id = $key;
                    $tquantity = $item['qty'];
                    break;
                }
            }
            
            $json['status'] = 1;
            $json['total_item'] = $cart->totalItems();
            $json['sub_total'] = config('app.currency_symbol', '$') . number_format($cart->total(), 2);
            $json['row_id'] = $row_id;
            $json['quantity'] = $tquantity;
            $json['msg'] = $language_name == 'french'
                ? ucfirst(strtolower(($productData['name_french'] ?? $productData['name']) . ' est ajouté à votre panier.'))
                : ucfirst(strtolower($productData['name'] . ' is added to your shopping cart.'));
        } else {
            $json['msg'] = $language_name == 'french'
                ? ucfirst(strtolower(($productData['name_french'] ?? $productData['name']) . ' ajouter à votre panier a été champ.'))
                : ucfirst(strtolower($productData['name'] . ' add to your shopping cart has been field'));
        }
        
        echo json_encode($json);
    }
    
    /**
     * Search products via AJAX
     * CI: Products->searchProduct() lines 296-339
     */
    public function searchProduct(Request $request)
    {
        $searchtext = $request->input('searchtext', '');
        $language_name = config('store.language_name', 'english');
        $main_store_id = config('store.website_store_id', 1);
        
        $search_result = '';
        
        if ($searchtext != '') {
            $searchtext = trim($searchtext);
            
            $query = DB::table('products')->where('status', 1);
            
            if ($language_name == 'french') {
                $query->where('name_french', 'like', '%' . $searchtext . '%');
            } else {
                $query->where('name', 'like', '%' . $searchtext . '%');
            }
            
            $lists = $query->get();
            
            if ($lists->count() > 0) {
                foreach ($lists as $list) {
                    if ($list->status == 1) {
                        $url = url('Products/view/' . base64_encode($list->id));
                        $name = $language_name == 'french' ? $list->name_french : $list->name;
                        $name = ucfirst($name);
                        $imageurl = url('uploads/products/' . ($list->product_image ?? 'default.jpg'));
                        
                        // Check for ecoink store (CI lines 317-323)
                        if ($main_store_id == 5) {
                            $category_id = 13;
                            $multipalCategory = $this->getProductMultipalCategoriesAndSubCategories($list->id);
                            if (array_key_exists($category_id, $multipalCategory)) {
                                $search_result .= '<li><img src="' . $imageurl . '" width="50"><a href="' . $url . '">' . $name . '</a></li>';
                            }
                        } else {
                            $search_result .= '<li><img src="' . $imageurl . '" width="50"><a href="' . $url . '">' . $name . '</a></li>';
                        }
                    }
                }
            } else {
                $search_result = '<li><i class="fas fa-search"></i><a href="javascript:void(0)">product not found</a></li>';
            }
        } else {
            $search_result = '<li><i class="fas fa-search"></i><a href="javascript:void(0)">product not found</a></li>';
        }
        
        if (empty($search_result)) {
            $search_result = '<li><i class="fas fa-search"></i><a href="javascript:void(0)">product not found</a></li>';
        }
        
        echo $search_result;
    }
    
    /**
     * Upload image/file for product customization
     * CI: lines 1488-1546
     */
    public function uploadImage(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        $product_id = $request->input('product_id');
        
        $return_arr = ['name' => '', 'size' => '', 'src' => '', 'skey' => '', 'product_id' => $product_id, 'location' => '', 'cumment' => '', 'error' => '', 'error_msg' => '', 'file_base_url' => '', 'html' => ''];
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $filesize = $file->getSize();
            $filetype = $file->getMimeType();
            
            $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg'];
            
            if (!in_array($filetype, $allowed_types)) {
                $return_arr['error'] = 1;
                $return_arr['error_msg'] = $language_name == 'french' 
                    ? 'Type de fichier autorisé uniquement pdf, jpg, jpeg' 
                    : 'Allowed file type only pdf, jpg, jpeg';
            } else if ($filesize > 262144000) { // 250MB
                $return_arr['error'] = 1;
                $return_arr['error_msg'] = $language_name == 'french' 
                    ? 'Taille de fichier maximale autorisée pour le téléchargement 250 Mo' 
                    : 'Maximum file size allowed for upload 250 MB';
            } else {
                $time = time();
                $ext = $file->getClientOriginalExtension();
                $newfileName = "cart-image/" . $time . '.' . $ext;
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('uploads/cart-image');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                $location = $uploadPath . '/' . $time . '.' . $ext;
                
                if ($file->move($uploadPath, $time . '.' . $ext)) {
                    $src = asset('defaults/pdf-icon.png');
                    $file_base_url = asset('uploads/' . $newfileName);
                    
                    $range = range(1, 5000);
                    $key = array_rand($range);
                    
                    $return_arr = [
                        'name' => $filename,
                        'size' => $filesize,
                        'src' => $src,
                        'skey' => $key,
                        'product_id' => $product_id,
                        'location' => $location,
                        'cumment' => '',
                        'error' => '',
                        'error_msg' => '',
                        'file_base_url' => $file_base_url
                    ];
                    
                    // Store in session
                    $sessionKey = 'product_id.' . $product_id . '.' . $key;
                    session([$sessionKey => $return_arr]);
                    
                    // Debug: Log file storage
                    Log::info('Stored file in session: ' . $sessionKey);
                    Log::info('Session after upload: ' . print_r(session()->all(), true));
                    
                    // Generate HTML
                    $html = view('ajax.file_data', ['return_arr' => $return_arr])->render();
                    $return_arr['html'] = $html;
                } else {
                    $return_arr['error'] = 1;
                    $return_arr['error_msg'] = $language_name == 'french' 
                        ? 'Le téléchargement du fichier a échoué' 
                        : 'File upload failed';
                }
            }
        }
        
        echo json_encode($return_arr);
    }
    
    /**
     * Update comment for uploaded file
     * CI: lines 1548-1558
     */
    public function updateCumment(Request $request)
    {
        $product_id = $request->input('product_id');
        $skey = $request->input('skey');
        $cumment = $request->input('cumment');
        
        $sessionKey = 'product_id.' . $product_id . '.' . $skey;
        $fileData = session($sessionKey);
        
        if ($fileData) {
            $fileData['cumment'] = $cumment;
            session([$sessionKey => $fileData]);
        }
        
        exit(0);
    }
    
    /**
     * Delete uploaded image
     * CI: lines 1561-1575
     */
    public function deleteImage(Request $request)
    {
        $product_id = $request->input('product_id');
        $skey = $request->input('skey');

        if (empty($product_id) || empty($skey)) {
            return response()->json(['success' => false]);
        }

        // Read the specific file entry from session (same structure as uploadImage/updateCumment)
        $sessionKey = "product_id.$product_id.$skey";
        $fileData = session($sessionKey);

        if ($fileData) {
            // Remove file from filesystem if it still exists
            $location = $fileData['location'] ?? null;
            if (!empty($location) && file_exists($location) && is_file($location)) {
                @unlink($location);
            }

            // Remove only this file entry from session
            session()->forget($sessionKey);
        }

        return response()->json(['success' => true]);
    }
    
    /**
     * Refresh captcha via AJAX
     * CI: Pages->load_refresh_capcha() lines 182-212
     */
    public function refreshCaptcha()
    {
        // Delete old captcha file
        $old_filename = session('captcha_filename');
        if ($old_filename) {
            $old_filepath = public_path('assets/captcha/' . $old_filename);
            if (file_exists($old_filepath) && is_file($old_filepath)) {
                unlink($old_filepath);
            }
        }
        
        // Create new captcha
        $cap = $this->create_capcha();
        session(['captcha_filename' => $cap['filename']]);
        
        // Store in database
        $user_ip = request()->ip();
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        DB::table('captcha')->insert([
            'captcha_time' => $cap['time'],
            'ip_address' => $user_ip,
            'word' => $cap['word']
        ]);
        
        return response()->json([
            'captcha' => $cap['image']
        ]);
    }
    
    /**
     * Create captcha
     * CI: Pages->create_capcha() lines 154-181
     */
    private function create_capcha()
    {
        $word = substr(str_shuffle("0123456789"), 0, 4);
        $img_path = public_path('assets/captcha/');
        $img_url = asset('assets/captcha/');
        
        // Create captcha directory if it doesn't exist
        if (!file_exists($img_path)) {
            mkdir($img_path, 0777, true);
        }
        
        // Generate captcha image
        $img_width = 150;
        $img_height = 50;
        $font_size = 20;
        
        $image = imagecreate($img_width, $img_height);
        
        // Colors
        $background = imagecolorallocate($image, 255, 255, 255);
        $border = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $grid_color = imagecolorallocate($image, 255, 40, 40);
        
        // Fill background
        imagefilledrectangle($image, 0, 0, $img_width, $img_height, $background);
        
        // Add border
        imagerectangle($image, 0, 0, $img_width - 1, $img_height - 1, $border);
        
        // Add grid lines
        for ($i = 0; $i < $img_width; $i += 10) {
            imageline($image, $i, 0, $i, $img_height, $grid_color);
        }
        for ($i = 0; $i < $img_height; $i += 10) {
            imageline($image, 0, $i, $img_width, $i, $grid_color);
        }
        
        // Add text with better positioning
        $x = 20;
        $y = 15;
        for ($i = 0; $i < strlen($word); $i++) {
            // Use font 5 which is the largest built-in font (9x15 pixels)
            imagestring($image, 5, $x + ($i * 30), $y + rand(-3, 3), $word[$i], $text_color);
        }
        
        // Save image
        $filename = time() . '.jpg';
        $filepath = $img_path . $filename;
        imagejpeg($image, $filepath);
        imagedestroy($image);
        
        $time = time();
        $img_tag = '<img src="' . $img_url . '/' . $filename . '" width="' . $img_width . '" height="' . $img_height . '" style="border:0;" alt=" " />';
        
        return [
            'word' => $word,
            'time' => $time,
            'image' => $img_tag,
            'filename' => $filename,
        ];
    }
    
    public function providerPrice(Request $request)
    {
        $params = [];
        parse_str($request->input('params'), $params);
        
        $provider_id = $params['provider_id'];
        $product_id = $params['product_id'];
        $productOptions = array_filter($params['productOptions']);
        // var_dump($product_id);

        $providerProduct = DB::table('provider_products')
            ->where('provider_id', $provider_id)
            ->where('product_id', $product_id)
            ->first();
        
        if ($providerProduct) {
            if ($providerProduct->information_type == 0) {
                unset($productOptions['turnaround']);
                $options = array_values((array) $productOptions);
            } else if ($providerProduct->information_type == 1) {
                // For information_type 1, the form sends numeric provider_option_value_id
                // but the Sina API expects actual text values. Map all IDs to text values.
                $providerOpts = DB::table('provider_options')
                    ->where('provider_id', $provider_id)
                    ->get()
                    ->keyBy('name');

                $options = [];
                foreach ($productOptions as $key => $value) {
                    // Normalize key: replace spaces with underscores for API
                    $apiKey = str_replace(' ', '_', $key);

                    // If value is numeric and the option exists in provider_options, map it
                    if (is_numeric($value) && isset($providerOpts[$key])) {
                        $optVal = DB::table('provider_option_values')
                            ->where('option_id', $providerOpts[$key]->id)
                            ->where('provider_option_value_id', $value)
                            ->first();

                        $options[$apiKey] = $optVal ? $optVal->value : $value;
                    } else {
                        $options[$apiKey] = $value;
                    }
                }
            }
            
            // Handle diameter: only circle needs diameter instead of width/length
            if (isset($options['shape']) && strtolower($options['shape']) === 'circle') {
                if (!isset($options['diameter']) || empty($options['diameter'])) {
                    // Derive diameter from width/length if available
                    if (isset($options['width']) && !empty($options['width'])) {
                        $options['diameter'] = $options['width'];
                    } elseif (isset($options['length']) && !empty($options['length'])) {
                        $options['diameter'] = $options['length'];
                    }
                }
                // Remove width/length for circle since API expects diameter
                unset($options['width'], $options['length']);
            } else {
                // Remove empty diameter for non-circle shapes (oval, square, rectangle use width/length)
                if (isset($options['diameter']) && empty($options['diameter'])) {
                    unset($options['diameter']);
                }
            }

            $price = sina_price($providerProduct->provider_product_id, $options);
            $result = ['success' => true, 'price' => $price];
        } else {
            $result = ['success' => false, 'message' => "Can't find product info"];
        }
        
        return response()->json($result);
        // echo json_encode($result);
    }

    public function calculatePrice(Request $request)
    {
        $response = [];

        // Basic POST data
        $product_id = $request->input('product_id');
        $price = $request->input('price', 0);
        $quantity = $request->input('quantity', 1);
        $quantity_id = $request->input('product_quantity_id');
        $size_id = $request->input('product_size_id');

        $add_length_width = $request->input('add_length_width');
        $page_add_length_width = $request->input('page_add_length_width');
        $depth_add_length_width = $request->input('depth_add_length_width');
        $recto_verso = $request->input('recto_verso');
        $recto_verso_price = $request->input('recto_verso_price');

        // Process multiple attributes
        $multiple_attributes = collect($request->all())
            ->filter(fn($val, $key) => preg_match('/^multiple_attribute_([0-9]+)$/i', $key) && $val !== '')
            ->map(function($val, $key) {
                preg_match('/^multiple_attribute_([0-9]+)$/i', $key, $m);
                return [(int)$m[1], $val];
            })
            ->sortBy(fn($attr) => $attr[0])
            ->values()
            ->toArray();

        $s_multiple_attributes = array_map(fn($attr) => "{$attr[0]} - {$attr[1]}", $multiple_attributes);

        // Check full price list (external source)
        $price_newprint = app('App\Models\Product')->getFullPrice($product_id, $quantity_id, $size_id, implode(',', $s_multiple_attributes));

        if ($price_newprint > 0) {
            $price = $price_newprint;
        } else {
            // Original price logic
            $attributes = collect($request->all())
                ->filter(fn($val, $key) => preg_match('/^attribute_id_([0-9]+)$/i', $key) && $val !== '')
                ->map(function($val, $key) {
                    preg_match('/^attribute_id_([0-9]+)$/i', $key, $m);
                    return [(int)$m[1], $val];
                })
                ->values()
                ->toArray();

            $price += DB::table('product')->getSumExtraPriceOfSingleAttributes($product_id, $attributes);
            $price += DB::table('product')->getSumExtraPriceOfQuantity($product_id, $quantity_id);
            $price += DB::table('product')->getSumExtraPriceOfQuantitySize($product_id, $quantity_id, $size_id);
            $price += DB::table('product')->getSumExtraPriceOfMultipleAttributes($product_id, $quantity_id, $size_id, $multiple_attributes);

            $Product = DB::table('product')->getProductList($product_id);

            // Add length/width price
            if ($add_length_width) {
                $price += DB::table('product')->calculateLengthWidthPrice($request, $Product);
            }

            // Add depth price
            if ($depth_add_length_width) {
                $price += DB::table('product')->calculateDepthPrice($request, $Product);
            }

            // Add page price
            if ($page_add_length_width) {
                $price += DB::table('product')->calculatePagePrice($request, $Product);
            }

            // Recto-verso price
            if ($recto_verso === "Yes" && $recto_verso_price) {
                $price += ($price * $recto_verso_price) / 100;
            }
        }

        $response['success'] = true;
        $response['price'] = number_format($price * $quantity, 2);

        // return response()->json($response);
        echo json_encode($response);
        exit(0);
    }

    public function saveEstimate(Request $request)
    {
        $language_name = session('language', 'english');
        
        // Then see if a captcha exists: (CI lines 1579-1590)
        $expiration = time() - 7200; // Two hour limit
        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND captcha_time > ?';
        $binds = [$request->input('captcha'), $expiration];
        $result = DB::select($sql, $binds);
        $row = $result[0] ?? null;
        
        // First, delete old captchas (CI lines 1593-1596)
        DB::table('captcha')
            ->where('captcha_time', '<', $expiration)
            ->where('word', $request->input('captcha'))
            ->delete();
        
        // Check captcha validity (CI lines 1600-1622)
        if ($row->count == 0) {
            if (session('filename')) {
                $filePath = public_path('assets/captcha/' . session('filename'));
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
            }
            
            $msg = '<font color=red>You must submit the word that appears in the image.</font><br />';
            if ($language_name == 'French') {
                $msg = '<font color=red>Vous devez soumettre le mot qui apparaît dans l\'image.</font><br />';
            }
            
            $response = [
                'status' => 'error',
                'msg' => $msg,
                'errors' => []
            ];
            
            echo json_encode($response);
            exit();
        } else {
            if (session('filename')) {
                $filePath = public_path('assets/captcha/' . session('filename'));
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
            }
        }
        
        // Load validation library and model (CI lines 1625-1626)
        // Laravel uses built-in validation
        
        // Get validation rules from Estimate_Model (CI lines 1628-1631)
        $rules = [
            'contact_name' => 'required',
            'company_name' => 'required',
            'street' => 'required',
            'city' => 'required',
            'country' => 'required',
            'flat_size' => 'required',
            'finish_size' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|min:6|max:15',
            'postal_code' => 'required|min:6|max:10',
        ];
        
        // Set validation rules (CI line 1633)
        $validator = validator($request->all(), $rules);
        
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];
        
        // CI has validation commented out, so we proceed directly (CI line 1644)
        // Prepare post data (CI lines 1645-1669)
        $postData = [
            'contact_name' => $request->input('contact_name'),
            'company_name' => $request->input('company_name'),
            'email' => $request->input('email'),
            'phone_number' => $request->input('phone_number'),
            'street' => $request->input('street'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'country' => $request->input('country'),
            'postal_code' => $request->input('postal_code'),
            'product_type' => $request->input('product_type'),
            'product_name' => $request->input('product_name'),
            'same_quote_request' => $request->input('same_quote_request'),
            'qty_1' => $request->input('qty_1'),
            'qty_2' => $request->input('qty_2'),
            'qty_3' => $request->input('qty_3'),
            'more_qty' => $request->input('more_qty'),
            'flat_size' => $request->input('flat_size'),
            'finish_size' => $request->input('finish_size'),
            'paper_stock' => $request->input('paper_stock'),
            'no_of_sides' => $request->input('no_of_sides'),
            'folding' => $request->input('folding'),
            'total_versions' => $request->input('total_versions'),
            'shipping_methods' => $request->input('shipping_methods'),
            'notes' => $request->input('notes'),
            'store_id' => config('store.main_store_id', 1), // matching CI main_store_id
        ];
        
        // Get country and province names (CI lines 1676-1677)
        $country = DB::table('countries')->where('id', $postData['country'])->value('name') ?? '';
        $province = DB::table('states')->where('id', $postData['province'])->value('name') ?? '';
        
        // Save estimate data (CI line 1679)
        $postData['created'] = date('Y-m-d H:i:s');
        $postData['updated'] = date('Y-m-d H:i:s');
        
        $estimateSaved = DB::table('estimates')->insert($postData);
        
        if ($estimateSaved) {
            // Prepare email (CI lines 1680-1707)
            $subject = 'Estimate Quote Request';
            $postData['same_quote_request'] = $postData['same_quote_request'] == 0 ? "Nope" : "Yes";
            $postData['no_of_sides'] = $postData['no_of_sides'] == 1 ? "1 side (inches)" : "Flat Format (2 Sides)";
            
            $body = '<div style="text-align:left;">' .
                $this->addEmailItem('Name Of The Contact', $postData['contact_name']) .
                $this->addEmailItem('Company Name', $postData['company_name']) .
                $this->addEmailItem('Email Address', $postData['email']) .
                $this->addEmailItem('Street', $postData['street']) .
                $this->addEmailItem('City', $postData['city']) .
                $this->addEmailItem('Country', $country) .
                $this->addEmailItem('State', $province) .
                $this->addEmailItem('Postal Code', $postData['postal_code']) .
                $this->addEmailItem('Product Type (Postcards, Booklets)', $postData['product_type']) .
                $this->addEmailItem('Product Name', $postData['product_name']) .
                $this->addEmailItem('Ever Requested The Same Quote?', $postData['same_quote_request']) .
                $this->addEmailItem('Qty1', $postData['qty_1']) .
                $this->addEmailItem('Qty2', $postData['qty_2']) .
                $this->addEmailItem('Qty3', $postData['qty_3']) .
                $this->addEmailItem('More quantity', $postData['more_qty']) .
                $this->addEmailItem('Flat Size (inches)', $postData['flat_size']) .
                $this->addEmailItem('Finished Size (inches)', $postData['finish_size']) .
                $this->addEmailItem('Paper / Stock', $postData['paper_stock']) .
                $this->addEmailItem('Number Of Sides', $postData['no_of_sides']) .
                $this->addEmailItem('Folding', $postData['folding']) .
                $this->addEmailItem('Number of Versions', $postData['total_versions']) . // Fixed from CI error
                $this->addEmailItem('Shipping Method', $postData['shipping_methods']) .
                $this->addEmailItem('Notes', $postData['notes']) .
                '</div>';
            
            // Get logo and prepare email template (CI lines 1710-1711)
            $configurations = config('site.configurations', session('configurations', []));
            $logoImage = $language_name == 'French' 
                ? ($configurations['logo_image_french'] ?? '')
                : ($configurations['logo_image'] ?? '');
            
            $logo = $this->getLogoImages($logoImage, true);
            $emailBody = $this->emailTemplate($subject, $body, false, $logo);
            
            // Send email to admin (CI line 1712)
            $adminEmail = env('ADMIN_EMAIL', 'info@printing.coop');
            $fromEmail = env('FROM_EMAIL', 'info@printing.coop');
            $this->sendEmail($adminEmail, $subject, $emailBody, $fromEmail, 'ADMIN');
            
            // Extra email to user (CI lines 1714-1715)
            $userSubject = 'We have received your estimate request';
            $this->sendEmail($postData['email'], $userSubject, $emailBody, $fromEmail, 'ADMIN');
            
            // Success message (CI lines 1717-1720)
            $response['status'] = 'success';
            $response['msg'] = 'Thank you for contacting printing coop we have received your estimation query our representative will get back to you within 24 hours';
            if ($language_name == 'French') {
                $response['msg'] = "Merci d'avoir contacté Imprimeur.coop nous avons reçu votre demande d'estimation notre représentant vous répondra dans les 24 heures";
            }
            
            echo json_encode($response);
            exit();
        }
        
        // Error case (CI lines 1726-1738)
        $response['status'] = 'error';
        $response['msg'] = 'Your Estimate Not Save Please Try Again.';
        if ($language_name == 'French') {
            $response['msg'] = "Votre estimation n'est pas enregistrée. Veuillez réessayer.";
        }
        
        echo json_encode($response);
        exit();
    }
    
    /**
     * Send email helper function
     * CI: constants.php line 959-980
     */
    private function sendEmail($toEmail, $subject, $body, $from = null, $fromName = null, $files = [])
    {
        try {
            $from = $from ?? config('mail.from.address');
            $fromName = $fromName ?? config('mail.from.name');
            
            Mail::raw($body, function ($message) use ($toEmail, $subject, $from, $fromName) {
                $message->to($toEmail)
                    ->subject($subject)
                    ->from($from, $fromName);
            });
            
            return true;
        } catch (\Exception $e) {
            Log::error('Email send error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get logo images helper function
     * CI: constants.php line 1038-1048
     */
    private function getLogoImages($imageName = null, $mail = false)
    {
        $imageurl = '';
        $logoPath = public_path('uploads/logo/' . $imageName);
        
        if ($imageName && file_exists($logoPath)) {
            if ($mail) {
                $imageurl = url('uploads/logo/' . $imageName);
            } else {
                $imageurl = asset('uploads/logo/' . $imageName);
            }
        }
        
        return $imageurl;
    }
    
    /**
     * Add email item helper function
     * CI: constants.php line 1924-1927
     */
    private function addEmailItem($title, $data)
    {
        return '<b>' . $title . ' </b> : ' . ucfirst($data) . '<br><br>';
    }
    
    /**
     * Email template helper function
     * CI: constants.php line 764-796
     */
    private function emailTemplate($subject, $body, $empty = false, $logo = false)
    {
        $logo = $logo ?: 'https://laravel.imprimeriecoop.com/assets/images/printing.coopLogo.png';
        $websiteName = config('app.name', 'Printing Coop');
        
        $html = '<div class="top-section" style="width:100%;text-align:center; font-family: Raleway, sans-serif !important;display: flex;justify-content: center;align-items: center;">
            <div class="top-mid-section" style="width:100%; max-width:600px; height:auto; text-align:center; padding:0px 0px 0px 0px; box-shadow: 0px 0px 10px -3px rgba(0,0,0,0.5);background-image: url(https://laravel.imprimeriecoop.com/assets/images/bg-vector-img.jpg);">
                <div style="background: rgba(255,255,255,0.9)">
                <div class="top-inner-section" style="background: #fa762b; padding: 3px 0px 1px 0px; box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.5);">
                </div>
                <div style="padding: 20px 0px 10px 0px; text-align: center;"><img src="' . $logo . '" width="60%"></div>
                <div class="tem-mid-section" style="text-align: center;">
                    <div class="tem-visibility" style="z-index: 99; padding: 20px;">
                        <div class="top-title" style="font-size: 22px; text-align: center;">
                            <span><strong>' . $subject . '</strong></span>
                        </div>

                        <div class="email-body">
                            ' . $body . '
                        </div>
                        <div style="background-color: #0086ac;margin-top: 20px;">
                            <div style="padding: 20px;">
                                <span style="color: #fff;line-height: 25px;">We are always here to help. You can also contact us directly on<br>514-544-8043,1-877-384-8043 or email us at info@printing.coop<br>FOLLOW US <br>printing.coop<br>imprimeur.coop<br><br>© Copyright 2019 ' . $websiteName . '</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tem-bottom" style="font-size: 18px; letter-spacing: 0.5px; line-height: 30px; background: #22a641;; color: #fff; padding: 3px 0px; text-align: center;">
                </div>
            </div>
        </div>
        </div>';
        
        return $html;
    }
    
    /**
     * Convert attribute IDs to full attribute data
     * CI: Product_Model->attributeDataFromIds() lines 3586-3625
     */
    protected function attributeDataFromIds($product_id, $attributes)
    {
        // CI project style: use product_attribute_map table which has the required columns
        $query = DB::table('product_attribute_map')
            ->join('attributes', 'attributes.id', '=', 'product_attribute_map.attribute_id')
            ->leftJoin('product_attribute_item_map', function($join) {
                $join->on('product_attribute_item_map.product_id', '=', 'product_attribute_map.product_id')
                     ->on('product_attribute_item_map.attribute_id', '=', 'product_attribute_map.attribute_id')
                     ->where('product_attribute_map.use_items', '=', 1);
            })
            ->leftJoin('attribute_items', 'attribute_items.id', '=', 'product_attribute_item_map.attribute_item_id')
            ->where('product_attribute_map.product_id', $product_id)
            ->select(
                'product_attribute_map.value_min',
                'product_attribute_map.value_max', 
                'product_attribute_map.use_items',
                'product_attribute_map.attribute_id',
                'product_attribute_item_map.attribute_item_id',
                'attributes.name as attribute_name_real',
                'attributes.label as attribute_name',
                'attributes.label_fr as attribute_name_french',
                'attribute_items.name as item_name',
                'attribute_items.name_fr as item_name_french'
            );
        
        // Build WHERE clause for matching attributes (only if attributes are not empty)
        if (!empty($attributes)) {
            $query->where(function($q) use ($attributes) {
                $first = true;
                foreach ($attributes as $attribute_id => $attribute_item_id) {
                    $condition = "(product_attribute_map.attribute_id = $attribute_id AND (product_attribute_item_map.attribute_item_id IS NULL OR product_attribute_item_map.attribute_item_id = $attribute_item_id))";
                    if ($first) {
                        $q->whereRaw($condition);
                        $first = false;
                    } else {
                        $q->orWhereRaw($condition);
                    }
                }
            });
        }
        
        $query->groupBy(
            'product_attribute_map.attribute_id', 
            'product_attribute_item_map.attribute_item_id',
            'product_attribute_map.value_min',
            'product_attribute_map.value_max',
            'product_attribute_map.use_items',
            'attributes.name',
            'attributes.label',
            'attributes.label_fr',
            'attribute_items.name',
            'attribute_items.name_fr'
        );
        $data = $query->get()->toArray();
        
        // Convert to array and update item names for non-item attributes (CI style)
        if (!empty($attributes)) {
            foreach ($attributes as $attribute_id => $attribute_item_id) {
                foreach ($data as &$item) {
                    $itemArray = (array) $item;
                    if ($itemArray['attribute_id'] == $attribute_id) {
                        if (!$itemArray['use_items']) {
                            $itemArray['item_name'] = $attribute_item_id;
                            $itemArray['item_name_french'] = $attribute_item_id;
                            $item = (object) $itemArray;
                        }
                        break;
                    }
                }
            }
        }
        
        // Add custom array to match CI format
        $result = $data;
        $result['custom'] = [];
        
        return $result;
    }
}