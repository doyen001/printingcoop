<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\Cart\CartService;
use App\Services\Pricing\PriceCalculator;

class ProductController extends Controller
{
    /**
     * Product listing with filters (replicate CI Products->index lines 19-172)
     */
    public function index(Request $request, $category_id = null, $sub_category_id = null)
    {
        $data = [];
        
        // Get store data from config
        $website_store_id = config('store.website_store_id', 1);
        $main_store_id = config('store.main_store_id', 1);
        $main_store_data = config('store.main_store_data', []);
        $language_name = config('store.language_name', 'English');
        
        // Initialize filter variables (lines 26-34)
        $data['category_id'] = '';
        $data['category_name'] = '';
        $data['category_data'] = '';
        $data['sub_category_id'] = '';
        $data['sub_category_name'] = '';
        $data['sub_category_data'] = '';
        $data['printer_brand'] = '';
        $data['printer_series'] = '';
        $data['printer_models'] = '';
        
        $title = $language_name == 'French' ? 'toutes catégories' : 'All Categories';
        
        // Decode category IDs (lines 37-38)
        $category_id = !empty($category_id) ? base64_decode($category_id) : 0;
        $sub_category_id = !empty($sub_category_id) ? base64_decode($sub_category_id) : 0;
        
        $url = url('Products/');
        
        // Get category from query string (lines 41-43)
        if ($request->has('category_id')) {
            $category_id = base64_decode($request->get('category_id'));
        }
        
        // Check ecoink condition (lines 44-49)
        if (isset($main_store_data['show_all_categories']) && $main_store_data['show_all_categories'] == 0 && $category_id != 13 && $website_store_id == 5) {
            $category_id = 13;
            $url .= '?category_id=' . base64_encode($category_id);
            return redirect($url);
        }
        
        // Get filters from query string (lines 51-54)
        $sub_category_id = $request->has('sub_category_id') ? base64_decode($request->get('sub_category_id')) : null;
        $printer_brand = $request->get('printer_brand');
        $printer_series = $request->get('printer_series');
        $printer_models = $request->get('printer_models');
        
        // Build category data (lines 56-67)
        if (!empty($category_id)) {
            $data['category_id'] = $category_id;
            $categoryData = $this->getCategoryDataById($category_id);
            $data['category_name'] = $categoryData['name'] ?? '';
            $data['category_data'] = $categoryData;
            $title = $language_name == 'French' ? ucfirst($categoryData['name_french'] ?? '') : ucfirst($categoryData['name'] ?? '');
            $pageData = $categoryData;
            $url .= '?category_id=' . base64_encode($category_id);
        } else {
            $url .= '?category_id=';
            $pageData = $this->getPageDataBySlug('products', $website_store_id);
        }
        
        // Build subcategory data (lines 69-81)
        if (!empty($sub_category_id)) {
            $data['sub_category_id'] = $sub_category_id;
            $subCategoryData = $this->getSubCategoryDataById($sub_category_id);
            $data['sub_category_name'] = $subCategoryData['name'] ?? '';
            $data['sub_category_data'] = $subCategoryData;
            $title .= $language_name == 'French' ? " /" . ucfirst($subCategoryData['name_french'] ?? '') : " /" . ucfirst($subCategoryData['name'] ?? '');
            $url .= '&sub_category_id=' . base64_encode($sub_category_id);
        } else {
            $url .= '&sub_category_id=';
        }
        
        // Build printer filters (lines 83-104)
        if (!empty($printer_brand)) {
            $data['printer_brand'] = $printer_brand;
            $title .= " / " . $printer_brand;
            $url .= '&printer_brand=' . $printer_brand;
        } else {
            $url .= '&printer_brand=';
        }
        
        if (!empty($printer_series)) {
            $data['printer_series'] = $printer_series;
            $title .= " / " . $printer_series;
            $url .= '&printer_series=' . $printer_series;
        } else {
            $url .= '&printer_series=';
        }
        
        if (!empty($printer_models)) {
            $data['printer_models'] = $printer_models;
            $title .= " / " . $printer_models;
            $url .= '&printer_models=' . $printer_models;
        } else {
            $url .= '&printer_models=';
        }
        
        // Set page title and meta tags (lines 106-117)
        $data['page_title'] = $title;
        if (!empty($pageData)) {
            $data['meta_page_title'] = $pageData['page_title'] ?? '';
            $data['meta_description_content'] = $pageData['meta_description_content'] ?? '';
            $data['meta_keywords_content'] = $pageData['meta_keywords_content'] ?? '';
            
            if ($language_name == 'French') {
                $data['meta_page_title'] = $pageData['page_title_french'] ?? '';
                $data['meta_description_content'] = $pageData['meta_description_content_french'] ?? '';
                $data['meta_keywords_content'] = $pageData['meta_keywords_content_french'] ?? '';
            }
        }
        
        // Get sorting (lines 118-129)
        $sortBy = $request->get('sort_by', 'name');
        $url .= "&sort_by=" . $sortBy;
        
        $sortByOptions = $this->getSortByDropdown();
        $sortByOption = $sortByOptions[$sortBy] ?? [];
        $order_by = $sortByOption['order_by'] ?? 'name';
        $type = $sortByOption['type'] ?? 'asc';
        $data['order'] = $sortBy;
        
        // Get total count (line 130)
        $total = $this->getTotalActiveProduct($category_id, $sub_category_id, $printer_brand, $printer_series, $printer_models);
        
        // Pagination (lines 132-151)
        $pageno = $request->get('pageno', 1);
        $no_of_records_per_page = 12;
        $offset = ($pageno - 1) * $no_of_records_per_page;
        $total_pages = ceil($total / $no_of_records_per_page);
        
        // Get product list (line 141)
        $lists = $this->getActiveProductList($category_id, $sub_category_id, $order_by, $type, $offset, $no_of_records_per_page, $printer_brand, $printer_series, $printer_models);
        
        $prevPage = $pageno - 1;
        $NextPage = $pageno + 1;
        
        if ($total_pages == $pageno) {
            $NextPage = '';
        }
        if ($pageno == 1) {
            $prevPage = '';
        }
        
        $data['url'] = $url;
        $data['total'] = $total;
        $data['NextPage'] = $NextPage;
        $data['prevPage'] = $prevPage;
        
        // Enrich product data with categories (lines 158-169)
        $data['lists'] = $lists;
        foreach ($lists as $key => $list) {
            $data['lists'][$key]['category'] = $this->getCategoryDataById($list['category_id']);
            
            $multipalCategory = $this->getProductMultipalCategoriesAndSubCategories($list['id']);
            $multipalCategoryData = [];
            foreach ($multipalCategory as $ckey => $cval) {
                $multipalCategoryData[$ckey] = $this->getCategoryDataById($ckey);
            }
            
            $data['lists'][$key]['multipalCategory'] = $multipalCategoryData;
        }
        
        return view('public.products.index', $data);
    }
    
    /**
     * Product detail view (replicate CI Products->view lines 174-294)
     */
    public function view(Request $request, $id = null)
    {
        if (empty($id)) {
            return redirect('/');
        }
        
        $id = base64_decode($id);
        $data = [];
        
        // Get store data
        $website_store_id = config('store.website_store_id', 1);
        $main_store_id = config('store.main_store_id', 1);
        $main_store_data = config('store.main_store_data', []);
        
        $data['page_title'] = 'Product Details';
        
        // Get product data (lines 187-193)
        $Product = $this->getProductList($id);
        $ProductDescriptions = $this->getProductDescriptionById($id);
        $ProductTemplates = $this->getProductTemplatesById($id);
        
        if (!$Product) {
            return redirect('/');
        }
        
        // Get product images (lines 197-198)
        $ProductImages = $this->getProductImageDataByProductId($id);
        $data['ProductImages'] = $ProductImages;
        
        // Get multiple categories (lines 200-216)
        $multipalCategory = $this->getProductMultipalCategoriesAndSubCategories($Product['id']);
        $multipalCategoryData = [];
        foreach ($multipalCategory as $ckey => $cval) {
            $category = $this->getCategoryDataById($ckey);
            if ($category) {
                $multipalCategoryData[$ckey] = $category;
            }
        }
        $Product['multipalCategoryData'] = $multipalCategoryData;
        
        // Check ecoink condition (lines 210-216)
        if (isset($main_store_data['show_all_categories']) && $main_store_data['show_all_categories'] == 0 && !array_key_exists(13, $multipalCategoryData) && $main_store_data['id'] == 5) {
            $url = url('Products/');
            $category_id = 13;
            $url .= '?category_id=' . base64_encode($category_id);
            return redirect($url);
        }
        
        $data['Product'] = $Product;
        $data['ProductDescriptions'] = $ProductDescriptions;
        $data['ProductTemplates'] = $ProductTemplates;
        
        // Get product attributes (lines 222-232)
        $ProductAttributes = $this->getProductAttributesByItemIdFrontEnd($id);
        $data['ProductAttributes'] = $ProductAttributes;
        
        $ProductSizes = $this->ProductQuantityDropDwon($id);
        $data['ProductSizes'] = $ProductSizes;
        
        $ProductPages = $this->getProductPages();
        $ProductSheets = $this->getProductSheets();
        $pageQuantity = $this->getPageQuantity();
        $data['ProductPages'] = $ProductPages;
        $data['ProductSheets'] = $ProductSheets;
        $data['pageQuantity'] = $pageQuantity;
        
        // Get cart data (lines 234-249)
        $cart = new CartService();
        $total_items = $cart->total_items();
        $productRowid = '';
        $productQty = 0;
        
        if ($total_items > 0) {
            $carts = $cart->contents();
            foreach ($carts as $rowid => $cartItem) {
                if ($cartItem['id'] == $Product['id']) {
                    $productQty = $cartItem['qty'];
                    $productRowid = $rowid;
                    break;
                }
            }
        }
        $data['productRowid'] = $productRowid;
        $data['productQty'] = $productQty;
        
        // New attribute structure (lines 251-255)
        $this->getProductAttributes($id, null, 0, 0, $attributes, $attributes_count);
        $this->getProductAttributeItems($id, null, null, 0, 0, $attribute_items, $attribute_items_count);
        $data['attributes'] = $attributes;
        $data['attribute_items'] = $attribute_items;
        
        // Check Provider binding (lines 257-290)
        $provider = $this->getProvider('sina');
        if ($provider) {
            $providerProduct = $this->getProductByProductId($provider->id, $id);
            if ($providerProduct) {
                $optionGroups = $this->getProductOptionGroups($provider->id, $providerProduct->provider_product_id);
                $options = [];
                foreach ($optionGroups as $item) {
                    $options[$item->id] = $item;
                }
                
                $optionValues = $this->getProductOptionValues($provider->id, $providerProduct->provider_product_id);
                foreach ($optionValues as $item) {
                    if ($item->provider_option_value_id == null || $item->value == null) {
                        continue;
                    }
                    
                    $option = $options[$item->option_id];
                    if (!isset($option->values)) {
                        $option->values = [];
                    }
                    $option->values[] = $item;
                }
                
                $data['provider'] = (object) [
                    'id' => $provider->id,
                    'product_id' => $id,
                    'options' => $options,
                    'shipping_extra_days' => $providerProduct->shipping_extra_days ?? 0,
                    'price_rate' => $providerProduct->price_rate,
                ];
                $data['providerProduct'] = $providerProduct;
            } else {
                $data['provider'] = false;
            }
        } else {
            $data['provider'] = false;
        }
        
        return view('public.products.view', $data);
    }
    
    /**
     * Search products (replicate CI Products->searchProduct lines 296-339)
     */
    public function searchProduct(Request $request)
    {
        $searchtext = $request->input('searchtext');
        $language_name = config('store.language_name', 'English');
        $main_store_id = config('store.main_store_id', 1);
        
        $search_result = '';
        
        if ($searchtext != '') {
            $searchtext = trim($searchtext);
            
            if ($language_name == 'French') {
                $lists = $this->getProductSearchFranchList($searchtext);
            } else {
                $lists = $this->getProductSearchList($searchtext);
            }
            
            if (!empty($lists)) {
                foreach ($lists as $list) {
                    if ($list['status'] == 1) {
                        $url = url('Products/view/' . base64_encode($list['id']));
                        $name = $language_name == 'French' ? $list['name_french'] : $list['name'];
                        $name = ucfirst($name);
                        $imageurl = $this->getProductImage($list['product_image'], 'medium');
                        
                        if ($main_store_id == 5) {
                            $category_id = 13;
                            $multipalCategory = $this->getProductMultipalCategoriesAndSubCategories($list['id']);
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
        
        return response($search_result);
    }
    
    /**
     * Add product rating (replicate CI Products->addRating lines 341-388)
     */
    public function addRating(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'rate' => 'required',
            'review' => 'required',
            'product_id' => 'required',
        ]);
        
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['errors'] = $validator->errors()->toArray();
        } else {
            $loginId = session('loginId');
            $name = $request->input('name');
            $rate = $request->input('rate');
            $review = $request->input('review');
            $product_id = $request->input('product_id');
            
            if (!$this->CheckRatingByUserIdAndProductId($loginId, $product_id)) {
                $postData = [
                    'name' => $name,
                    'rate' => $rate,
                    'review' => $review,
                    'product_id' => $product_id,
                    'user_id' => $loginId,
                ];
                
                if ($this->saveRating($postData)) {
                    $data = $this->getTotalSumAvgReting($product_id);
                    DB::table('products')->where('id', $product_id)->update([
                        'rating' => ceil($data['avg']),
                        'reviews' => $data['total'],
                    ]);
                    $response['msg'] = 'Your review posted successfully.';
                } else {
                    $response['status'] = 'error';
                    $response['msg'] = 'Your review posted unsuccessfully.';
                }
            } else {
                $response['status'] = 'error';
                $response['msg'] = 'You have already add review on this product.';
            }
        }
        
        return response()->json($response);
    }
    
    /**
     * Email subscribe (replicate CI Products->emailSubscribe lines 390-434)
     */
    public function emailSubscribe(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect('/');
        }
        
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|unique:subscribe_emails,email',
        ]);
        
        $language_name = config('store.language_name', 'English');
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
                $response['msg'] = $language_name == 'French'
                    ? "Votre adresse e-mail s'est abonnée avec succès."
                    : 'Your email id subscribe successfully.';
            } else {
                $response['msg'] = $language_name == 'French'
                    ? "Votre adresse e-mail s'est abonnée sans succès"
                    : 'Your review posted unsuccessfully.';
            }
        }
        
        return response()->json($response);
    }
    
    /**
     * Calculate price (use PriceCalculator service)
     */
    public function calculatePrice(Request $request)
    {
        $calculator = new PriceCalculator();
        $language = config('store.language_name', 'English');
        
        $result = $calculator->calculatePrice($request->all(), $language);
        
        return response()->json($result);
    }
    
    // ========== Private Helper Methods (replicate Product_Model methods) ==========
    
    private function getCategoryDataById($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        return $category ? (array) $category : [];
    }
    
    private function getSubCategoryDataById($id)
    {
        $subCategory = DB::table('sub_categories')->where('id', $id)->first();
        return $subCategory ? (array) $subCategory : [];
    }
    
    private function getPageDataBySlug($slug, $store_id)
    {
        $page = DB::table('pages')->where('slug', $slug)->where('store_id', $store_id)->first();
        return $page ? (array) $page : [];
    }
    
    private function getSortByDropdown()
    {
        return [
            'name' => ['order_by' => 'name', 'type' => 'asc'],
            'price_low' => ['order_by' => 'price', 'type' => 'asc'],
            'price_high' => ['order_by' => 'price', 'type' => 'desc'],
            'newest' => ['order_by' => 'id', 'type' => 'desc'],
        ];
    }
    
    private function getTotalActiveProduct($category_id, $sub_category_id, $printer_brand, $printer_series, $printer_models)
    {
        $query = DB::table('products')->where('status', 1);
        
        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }
        
        if (!empty($sub_category_id)) {
            $query->where('sub_category_id', $sub_category_id);
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
    
    private function getActiveProductList($category_id, $sub_category_id, $order_by, $type, $offset, $limit, $printer_brand, $printer_series, $printer_models)
    {
        $query = DB::table('products')->where('status', 1);
        
        if (!empty($category_id)) {
            $query->where('category_id', $category_id);
        }
        
        if (!empty($sub_category_id)) {
            $query->where('sub_category_id', $sub_category_id);
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
        
        $query->orderBy($order_by, $type)->offset($offset)->limit($limit);
        
        $results = $query->get();
        return array_map(function($item) {
            return (array) $item;
        }, $results->toArray());
    }
    
    private function getProductMultipalCategoriesAndSubCategories($product_id)
    {
        // This would query a junction table for multiple categories
        // Simplified version - adjust based on actual schema
        return [];
    }
    
    private function getProductList($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        return $product ? (array) $product : null;
    }
    
    private function getProductDescriptionById($id)
    {
        return DB::table('product_descriptions')->where('product_id', $id)->get()->toArray();
    }
    
    private function getProductTemplatesById($id)
    {
        return DB::table('product_templates')->where('product_id', $id)->get()->toArray();
    }
    
    private function getProductImageDataByProductId($id)
    {
        return DB::table('product_images')->where('product_id', $id)->get()->toArray();
    }
    
    private function getProductAttributesByItemIdFrontEnd($id)
    {
        return DB::table('product_attributes')->where('product_id', $id)->get()->toArray();
    }
    
    private function ProductQuantityDropDwon($id)
    {
        return DB::table('product_quantity')->where('product_id', $id)->get()->toArray();
    }
    
    private function getProductPages()
    {
        return []; // Implement based on schema
    }
    
    private function getProductSheets()
    {
        return []; // Implement based on schema
    }
    
    private function getPageQuantity()
    {
        return []; // Implement based on schema
    }
    
    private function getProductAttributes($product_id, $q, $take, $skip, &$attributes, &$attributes_count)
    {
        $attributes = DB::table('product_attributes')->where('product_id', $product_id)->get()->toArray();
        $attributes_count = count($attributes);
    }
    
    private function getProductAttributeItems($product_id, $attribute_id, $q, $take, $skip, &$attribute_items, &$attribute_items_count)
    {
        $attribute_items = DB::table('product_attribute_items')->where('product_id', $product_id)->get()->toArray();
        $attribute_items_count = count($attribute_items);
    }
    
    private function getProvider($name)
    {
        return DB::table('providers')->where('name', $name)->first();
    }
    
    private function getProductByProductId($provider_id, $product_id)
    {
        return DB::table('provider_products')
            ->where('provider_id', $provider_id)
            ->where('product_id', $product_id)
            ->first();
    }
    
    private function getProductOptionGroups($provider_id, $provider_product_id)
    {
        return DB::table('provider_options')
            ->where('provider_id', $provider_id)
            ->where('provider_product_id', $provider_product_id)
            ->get();
    }
    
    private function getProductOptionValues($provider_id, $provider_product_id)
    {
        return DB::table('provider_option_values')
            ->where('provider_id', $provider_id)
            ->where('provider_product_id', $provider_product_id)
            ->get();
    }
    
    private function getProductSearchList($searchtext)
    {
        $results = DB::table('products')
            ->where('status', 1)
            ->where('name', 'LIKE', '%' . $searchtext . '%')
            ->get();
        
        return array_map(function($item) {
            return (array) $item;
        }, $results->toArray());
    }
    
    private function getProductSearchFranchList($searchtext)
    {
        $results = DB::table('products')
            ->where('status', 1)
            ->where('name_french', 'LIKE', '%' . $searchtext . '%')
            ->get();
        
        return array_map(function($item) {
            return (array) $item;
        }, $results->toArray());
    }
    
    private function getProductImage($image, $size)
    {
        // Implement image URL generation logic
        return url('uploads/products/' . $size . '/' . $image);
    }
    
    private function CheckRatingByUserIdAndProductId($user_id, $product_id)
    {
        return DB::table('product_ratings')
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->exists();
    }
    
    private function saveRating($data)
    {
        return DB::table('product_ratings')->insert($data);
    }
    
    private function getTotalSumAvgReting($product_id)
    {
        $result = DB::table('product_ratings')
            ->where('product_id', $product_id)
            ->selectRaw('AVG(rate) as avg, COUNT(*) as total')
            ->first();
        
        return [
            'avg' => $result->avg ?? 0,
            'total' => $result->total ?? 0,
        ];
    }
}
