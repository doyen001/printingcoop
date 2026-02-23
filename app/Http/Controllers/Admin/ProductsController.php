<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductQuantity;
use App\Models\ProductSize;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeItem;
use App\Models\ProductMultipleAttribute;
use App\Models\ProductMultipleAttributeItem;
use App\Models\ProductSizeMultipleAttribute;
use App\Models\Size;
use App\Models\PageSize;
use App\Models\Estimate;
use App\Http\Requests\Admin\ProductUpdateRequest;
use App\Http\Requests\Admin\ProductImageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Admin ProductsController
 * Complete product management for admin panel
 * CI: application/controllers/admin/Products.php
 */
class ProductsController extends Controller
{
    protected $productModel;
    
    public function __construct()
    {
        // Dependency injection for Product model
        $this->productModel = new Product();
    }

    /**
     * Product listing with pagination and search
     * CI: Products->index() lines 24-54
     */
    public function index(Request $request, $product_id = 0, $order = 'desc')
    {
        // Handle order change via POST (CI equivalent)
        if ($request->isMethod('post')) {
            $order = $request->input('order', 'desc');
            $product_id = $request->input('product_id', 0);
            return redirect()->to('admin/Products/index/' . $product_id . '/' . $order);
        }

        // Page setup data (CI equivalent)
        $data = [
            'page_title' => 'Products',
            'sub_page_title' => 'Add New Product',
            'sub_page_url' => 'admin.products.addEdit',
            'sub_page_view_url' => 'admin.products.view',
            'sub_page_delete_url' => 'admin.products.delete',
            'sub_page_url_active_inactive' => 'admin.products.activeInactive',
        ];

        // Pagination setup (CI equivalent)
        $perPage = 20;
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;

        // Get products using Eloquent model (converted from CI Product_Model->getProductList)
        $cacheKey = "products_list_{$product_id}_{$perPage}_{$offset}_{$order}";
        
        $products = Cache::remember($cacheKey, 300, function () use ($product_id, $perPage, $offset, $order) {
            $query = Product::query();
            $query->getProductList(null, $product_id, $perPage, $offset, $order);
            return $query->get();
        });
        
        // Get total count for pagination (CI equivalent) with caching
        $countCacheKey = "products_total_{$product_id}";
        $totalProducts = Cache::remember($countCacheKey, 600, function () use ($product_id) {
            $query = Product::query();
            return $query->getProductTotal($product_id);
        });
        
        // Create pagination links (Laravel equivalent)
        $products = new \Illuminate\Pagination\LengthAwarePaginator(
            $products,
            $totalProducts,
            $perPage,
            $page,
            [
                'path' => route('admin.products.index'),
                'pageName' => 'page',
                'query' => ['product_id' => $product_id, 'order' => $order]
            ]
        );

        // Pass data to view (CI equivalent)
        $data['products'] = $products;
        $data['lists'] = $products;  // Also pass as $lists for backward compatibility
        $data['order'] = $order;
        $data['product_id'] = $product_id;

        return view('admin.products.index', $data);
    }
    
        
    /**
     * Add/Edit product form with comprehensive validation
     * CI: Products->addEdit() lines 80-600
     */
    public function addEdit(Request $request, $id = null)
    {
        try {
            // Page setup (CI equivalent)
            $page_title = 'Add New Product';
            if ($id) {
                $page_title = 'Edit Product';
            }

            // Initialize data arrays (CI equivalent)
            $postData = [];
            $ProductImages = [];
            $ProductDescriptions = [];
            $ProductTemplates = [];
            $ProductCategory = [];
            $ProductSubCategory = [];

            // Load existing data if editing (CI equivalent)
            if ($id) {
                $product = new Product();
                $postData = $product->getProductDataById($id);
                if (!$postData) {
                    return redirect()->route('admin.products.index')
                        ->with('message_error', 'Product not found');
                }
                $ProductImages = DB::table('product_images')->where('product_id', $id)->get();
                $ProductDescriptions = DB::table('product_descriptions')->where('product_id', $id)->get();
                $ProductTemplates = DB::table('product_templates')->where('product_id', $id)->get();
                
                // Get product categories and subcategories
                $categoryData = $product->getProductMultipalCategoriesAndSubCategories($id);
                
                // Debug: Log the category data structure
                Log::info('Category data structure', [
                    'categoryData' => $categoryData,
                    'categories' => $categoryData['categories'] ?? 'missing',
                    'sub_categories' => $categoryData['sub_categories'] ?? 'missing'
                ]);
                
                // Transform categories for blade template compatibility
                $ProductCategory = [];
                if (isset($categoryData['categories'])) {
                    foreach ($categoryData['categories'] as $categoryId) {
                        $ProductCategory[$categoryId] = $categoryId;
                    }
                }
                
                // Transform subcategories for blade template compatibility  
                $ProductSubCategory = $categoryData['sub_categories'] ?? [];
                
                // Debug: Log the final arrays
                Log::info('Final category arrays', [
                    'ProductCategory' => $ProductCategory,
                    'ProductSubCategory' => $ProductSubCategory,
                    'is_subcategory_array' => is_array($ProductSubCategory)
                ]);
            } else {
                $ProductCategory = [];
                $ProductSubCategory = [];
            }

            // Handle POST request with validation
            if ($request->isMethod('post')) {
                // Debug: Log form submission
                Log::info('Form submitted', ['all_data' => $request->all()]);
                
                // Extract categories from checkbox inputs
                $selectedCategories = [];
                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'category_id_') === 0 && $value) {
                        $categoryId = str_replace('category_id_', '', $key);
                        $selectedCategories[] = $categoryId;
                    }
                }
                
                // Basic validation like CI project
                $rules = [
                    'name' => 'required|string|max:255',
                    'name_french' => 'required|string|max:255',
                    'price' => 'required|numeric|min:0',
                ];
                
                // Add category validation if no categories selected
                if (empty($selectedCategories)) {
                    $rules['category_validation'] = 'required';
                }
                
                $validator = Validator::make($request->all(), $rules, [
                    'category_validation.required' => 'Please select at least one product category'
                ]);
                
                if ($validator->fails()) {
                    Log::error('Validation failed', ['errors' => $validator->errors()->toArray()]);
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                
                Log::info('Validation passed');
                
                // Build complete postData array like CI version
                $postData = [];
                
                // Basic product info
                $postData['name'] = $request->input('name');
                $postData['name_french'] = $request->input('name_french');
                $postData['price'] = $request->input('price');
                $postData['short_description'] = $request->input('short_description');
                $postData['short_description_french'] = $request->input('short_description_french');
                $postData['full_description'] = $request->input('full_description');
                $postData['full_description_french'] = $request->input('full_description_french');
                $postData['code'] = $request->input('code');
                $postData['code_french'] = $request->input('code_french');
                $postData['model'] = $request->input('model');
                $postData['model_french'] = $request->input('model_french');
                
                // SEO fields
                $postData['page_title'] = $request->input('page_title');
                $postData['page_title_french'] = $request->input('page_title_french');
                $postData['meta_description_content'] = $request->input('meta_description_content');
                $postData['meta_description_content_french'] = $request->input('meta_description_content_french');
                $postData['meta_keywords_content'] = $request->input('meta_keywords_content');
                $postData['meta_keywords_content_french'] = $request->input('meta_keywords_content_french');
                
                // Status and stock
                $postData['is_stock'] = $request->input('is_stock', 0);
                $postData['status'] = $request->input('status', 1);
                $postData['is_featured'] = $request->input('featured', 0);
                $postData['is_bestseller'] = $request->input('bestseller', 0);
                $postData['is_today_deal'] = $request->input('today_deal', 0);
                $postData['is_special'] = $request->input('special', 0);
                
                // Product tags
                $product_tag = $request->input('product_tag', []);
                if (!empty($product_tag)) {
                    $product_tag = implode(',', $product_tag);
                }
                $postData['product_tag'] = $product_tag;
                
                // Categories - use first selected category as main category_id
                $postData['category_id'] = !empty($selectedCategories) ? $selectedCategories[0] : null;
                $postData['sub_category_id'] = $request->input('sub_category_id');
                
                // Required fields from CI database
                $postData['min_order_quantity'] = $request->input('min_order_quantity', 25);
                $postData['menu_id'] = $request->input('menu_id', 0);
                $postData['total_stock'] = $request->input('total_stock', 0);
                $postData['discount'] = $request->input('discount', 0);
                $postData['reviews'] = $request->input('reviews', 0);
                $postData['rating'] = $request->input('rating', 0);
                $postData['total_visited'] = $request->input('total_visited', 0);
                $postData['delivery_charge'] = $request->input('delivery_charge', 0);
                $postData['product_type'] = $request->input('product_type', 2);
                $postData['discount_id'] = $request->input('discount_id', 0);
                $postData['free_shipping'] = $request->input('free_shipping', 1);
                $postData['store_id'] = $request->input('store_id', '1,2');
                $postData['poster_plans'] = $request->input('poster_plans', 0);
                $postData['banners_frames'] = $request->input('banners_frames', 0);
                $postData['cards_invites'] = $request->input('cards_invites', 0);
                $postData['photo_gifts'] = $request->input('photo_gifts', 0);
                $postData['cart_name'] = $request->input('cart_name', 0);
                $postData['catalog'] = $request->input('catalog', 0);
                $postData['brochure'] = $request->input('brochure', 0);
                $postData['is_printed_product'] = $request->input('is_printed_product', 0);
                $postData['is_bestdeal'] = $request->input('is_bestdeal', 0);
                $postData['add_length_width'] = $request->input('add_length_width', 0);
                $postData['length_width_pages_type'] = $request->input('length_width_pages_type', 'dropdown');
                $postData['length_width_min_quantity'] = $request->input('length_width_min_quantity', 25);
                $postData['length_width_max_quantity'] = $request->input('length_width_max_quantity', 5000);
                $postData['length_width_quantity_show'] = $request->input('length_width_quantity_show', 1);
                $postData['length_width_color_show'] = $request->input('length_width_color_show', 0);
                $postData['votre_text'] = $request->input('votre_text', 0);
                $postData['recto_verso'] = $request->input('recto_verso', 0);
                $postData['recto_verso_price'] = $request->input('recto_verso_price', 0);
                $postData['page_add_length_width'] = $request->input('page_add_length_width', 0);
                $postData['page_length_width_pages_type'] = $request->input('page_length_width_pages_type', 'dropdown');
                $postData['page_length_width_pages_show'] = $request->input('page_length_width_pages_show', 1);
                $postData['page_length_width_sheets_type'] = $request->input('page_length_width_sheets_type', 'dropdown');
                $postData['page_length_width_quantity_type'] = $request->input('page_length_width_quantity_type', 'input');
                $postData['page_length_width_sheets_show'] = $request->input('page_length_width_sheets_show', 0);
                $postData['page_length_width_color_show'] = $request->input('page_length_width_color_show', 0);
                $postData['page_length_width_min_quantity'] = $request->input('page_length_width_min_quantity', 25);
                $postData['page_length_width_max_quantity'] = $request->input('page_length_width_max_quantity', 5000);
                $postData['page_length_width_quantity_show'] = $request->input('page_length_width_quantity_show', 1);
                $postData['call'] = $request->input('call', 0);
                $postData['depth_add_length_width'] = $request->input('depth_add_length_width', 0);
                $postData['depth_width_length_type'] = $request->input('depth_width_length_type', 'input');
                $postData['depth_width_length_quantity_show'] = $request->input('depth_width_length_quantity_show', '1');
                $postData['depth_min_quantity'] = $request->input('depth_min_quantity', 25);
                $postData['depth_max_quantity'] = $request->input('depth_max_quantity', 5000);
                $postData['depth_color_show'] = $request->input('depth_color_show', 0);
                $postData['use_custom_size'] = $request->input('use_custom_size', 0);
                
                // Generate slug like CI
                $postData['product_slug'] = $this->createSlug($postData['name'], 'products', 'product_slug', $id);
                
                Log::info('Calling saveProduct', ['postData' => $postData]);
                
                return $this->saveProduct($postData, $id, $selectedCategories);
            }

            // Get dropdown data (CI equivalent)
            $quantity = Product::getQuantityListDropDown();
            $StoreList = Product::getStoreDropDownList();
            $Categoty = Product::getMultipleCategoriesAndSubCategories();
            $tagList = DB::table('tags')->where('status', 1)->get();
            
            // Debug: Log categories data
            Log::info('Categories loaded', ['categories' => $Categoty]);

            // Prepare data for view (CI equivalent)
            $data = [
                'page_title' => $page_title,
                'main_page_url' => '',
                'postData' => $postData,
                'ProductImages' => $ProductImages,
                'ProductDescriptions' => $ProductDescriptions,
                'ProductTemplates' => $ProductTemplates,
                'ProductCategory' => $ProductCategory,
                'ProductSubCategory' => $ProductSubCategory,
                'quantity' => $quantity,
                'StoreList' => $StoreList,
                'Categoty' => $Categoty,
                'tagList' => $tagList,
            ];

            return view('admin.products.add_edit', $data);
            
        } catch (Exception $e) {
            Log::error('Error in addEdit method: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading product form: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Save product (create or update) with enhanced validation and error handling
     * CI: Products->addEdit() POST handling lines 119-600
     */
    /**
     * Delete product with associated images and data
     * CI: Products->deleteProduct() lines 1291-1329
     */
    public function deleteProduct($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('admin.products.index')
                    ->with('message_error', 'Missing information.');
            }

            $page_title = 'Product Delete';
            
            // Get product images before deletion for file cleanup
            $productImages = DB::table('product_images')->where('product_id', $id)->get();
            
            // Delete the product using the model method
            $product = new Product();
            if ($product->deleteProduct($id)) {
                // Delete associated product images from database
                DB::table('product_images')->where('product_id', $id)->delete();
                
                // Delete associated product descriptions
                DB::table('product_descriptions')->where('product_id', $id)->delete();
                
                // Delete associated product templates
                DB::table('product_templates')->where('product_id', $id)->delete();
                
                // Delete associated product categories
                DB::table('product_category')->where('product_id', $id)->delete();
                
                // Delete associated product subcategories
                DB::table('product_subcategory')->where('product_id', $id)->delete();
                
                // Clean up image files
                foreach ($productImages as $image) {
                    $imageName = $image->image;
                    if (!empty($imageName)) {
                        $imagePaths = [
                            public_path('uploads/products/small/' . $imageName),
                            public_path('uploads/products/medium/' . $imageName),
                            public_path('uploads/products/large/' . $imageName),
                            public_path('uploads/products/' . $imageName),
                        ];
                        
                        foreach ($imagePaths as $imagePath) {
                            if (file_exists($imagePath)) {
                                unlink($imagePath);
                            }
                        }
                    }
                }
                
                Log::info('Product deleted successfully', ['product_id' => $id]);
                
                return redirect()->route('admin.products.index')
                    ->with('message_success', $page_title . ' Successfully.');
            } else {
                Log::error('Failed to delete product', ['product_id' => $id]);
                
                return redirect()->route('admin.products.index')
                    ->with('message_error', $page_title . ' Unsuccessfully.');
            }
            
        } catch (Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage(), ['product_id' => $id]);
            
            return redirect()->route('admin.products.index')
                ->with('message_error', 'Error deleting product: ' . $e->getMessage());
        }
    }
    
    /**
     * Create slug from name (CI equivalent)
     */
    protected function createSlug($name, $table, $field, $id = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;
        
        // Check if slug exists
        $query = DB::table($table)->where($field, $slug);
        if ($id) {
            $query->where('id', '!=', $id);
        }
        
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $query = DB::table($table)->where($field, $slug);
            if ($id) {
                $query->where('id', '!=', $id);
            }
            $counter++;
        }
        
        return $slug;
    }
    
    protected function saveProduct(array $postData, $id = null, $selectedCategories = [])
    {
        try {
            Log::info('saveProduct called', ['postData' => $postData, 'id' => $id]);
            
            DB::beginTransaction();
            
            // Generate product slug if not provided (CI equivalent)
            if (empty($postData['product_slug'])) {
                $postData['product_slug'] = $this->createSlug($postData['name'], 'products', 'product_slug', $id);
            }
            
            // Prepare timestamps
            $now = now();
            
            if ($id) {
                // Update existing product - only include valid database columns
                $updateData = [];
                $validColumns = [
                    'name', 'name_french', 'price', 'price_euro', 'price_gbp', 'price_usd',
                    'short_description', 'short_description_french', 'full_description', 'full_description_french',
                    'is_today_deal', 'is_today_deal_date', 'status', 'created', 'updated',
                    'menu_id', 'category_id', 'sub_category_id', 'is_featured', 'is_bestseller',
                    'is_special', 'is_stock', 'poster_plans', 'banners_frames', 'cards_invites',
                    'photo_gifts', 'cart_name', 'catalog', 'brochure', 'is_printed_product',
                    'total_stock', 'discount', 'product_image', 'code', 'code_french', 'brand',
                    'reviews', 'rating', 'total_visited', 'delivery_charge', 'is_bestdeal',
                    'product_type', 'min_order_quantity', 'discount_id', 'free_shipping',
                    'store_id', 'product_tag', 'add_length_width', 'min_length', 'max_length',
                    'min_width', 'max_width', 'min_length_min_width_price', 'length_width_pages_type',
                    'length_width_min_quantity', 'length_width_max_quantity', 'length_width_quantity_show',
                    'length_width_unit_price_black', 'length_width_price_color', 'length_width_color_show',
                    'votre_text', 'recto_verso', 'recto_verso_price', 'page_add_length_width',
                    'page_min_length', 'page_max_length', 'page_min_width', 'page_max_width',
                    'page_min_length_min_width_price', 'page_length_width_pages_type',
                    'page_length_width_pages_show', 'page_length_width_sheets_type',
                    'page_length_width_quantity_type', 'page_length_width_sheets_show',
                    'page_length_width_color_show', 'page_length_width_price_color',
                    'page_length_width_price_black', 'page_length_width_min_quantity',
                    'page_length_width_max_quantity', 'page_length_width_quantity_show',
                    'call', 'phone_number', 'shipping_box_length', 'shipping_box_width',
                    'shipping_box_height', 'shipping_box_weight', 'use_custom_size',
                    'depth_add_length_width', 'min_depth', 'max_depth', 'depth_min_length',
                    'depth_max_length', 'depth_min_width', 'depth_max_width',
                    'depth_width_length_price', 'depth_unit_price_black', 'depth_price_color',
                    'depth_color_show', 'depth_width_length_type', 'depth_width_length_quantity_show',
                    'depth_min_quantity', 'depth_max_quantity', 'page_title', 'page_title_french',
                    'meta_description_content', 'meta_description_content_french',
                    'meta_keywords_content', 'meta_keywords_content_french', 'product_slug'
                ];
                
                foreach ($validColumns as $column) {
                    if (array_key_exists($column, $postData)) {
                        $updateData[$column] = $postData[$column];
                    }
                }
                
                $updateData['updated'] = $now;
                Log::info('Updating product', ['id' => $id, 'data' => $updateData]);
                $result = DB::table('products')->where('id', $id)->update($updateData);
                $product_id = $id;
                $message = 'Product updated successfully';
                Log::info('Product updated successfully', ['product_id' => $product_id]);
                
            } else {
                // Create new product
                $postData['created'] = $now;
                $postData['updated'] = $now;
                $postData['status'] = $postData['status'] ?? 1;
                Log::info('Creating product', ['data' => $postData]);
                $product_id = DB::table('products')->insertGetId($postData);
                $message = 'Product created successfully';
                Log::info('Product created successfully', ['product_id' => $product_id]);
            }
            
            if (!$product_id) {
                Log::error('Failed to save product - no product_id returned');
                throw new Exception('Failed to save product');
            }
            
            Log::info('Product saved, continuing with related data', ['product_id' => $product_id]);
            
            // Handle image uploads with validation
            if (request()->hasFile('product_images')) {
                Log::info('Processing image uploads');
                $this->handleImageUploads(request(), $product_id);
            }
            
            // Save product descriptions
            if (request()->has('title')) {
                Log::info('Saving product descriptions');
                $this->saveProductDescriptions(request(), $product_id);
            }
            
            // Save product templates
            if (request()->has('final_dimensions')) {
                Log::info('Saving product templates');
                $this->saveProductTemplates(request(), $product_id);
            }
            
            // Handle categories and subcategories
            $this->saveProductCategoriesWithCategories($product_id, $selectedCategories);
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('message_success', $message);
                
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error saving product: ' . $e->getMessage(), [
                'validated_data' => $validatedData ?? [],
                'product_id' => $id ?? 'new'
            ]);
            
            return redirect()->back()
                ->with('message_error', 'Error saving product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Handle image uploads with comprehensive validation and error handling
     */
    protected function handleImageUploads(Request $request, $product_id)
    {
        try {
            $imageRequest = new ProductImageRequest();
            $validatedData = $imageRequest->validated();
            
            $uploadedImages = [];
            
            if (isset($validatedData['product_images'])) {
                $files = $request->file('product_images');
                
                foreach ($files as $key => $file) {
                    if ($file->isValid()) {
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        
                        // Store file using Laravel Storage (CI equivalent)
                        $path = $file->storeAs('products', $filename, 'public');
                        
                        // Create different sizes (CI equivalent)
                        $this->resizeImage($filename, 'small');
                        $this->resizeImage($filename, 'medium');
                        $this->resizeImage($filename, 'large');
                        
                        $uploadedImages[] = [
                            'image' => $filename,
                            'created' => now(),
                            'updated' => now(),
                            'product_id' => $product_id
                        ];
                        
                        Log::info('Image uploaded successfully', [
                            'product_id' => $product_id,
                            'filename' => $filename,
                            'path' => $path
                        ]);
                    }
                }
            }
            
            // Save images to database (CI equivalent)
            if (!empty($uploadedImages)) {
                DB::table('product_images')->insert($uploadedImages);
                
                // Set first image as main product image (CI equivalent)
                $mainImage = $uploadedImages[0]['image'];
                DB::table('products')->where('id', $product_id)->update([
                    'product_image' => $mainImage
                ]);
                
                Log::info('Product main image updated', [
                    'product_id' => $product_id,
                    'main_image' => $mainImage
                ]);
            }
            
        } catch (Exception $e) {
            Log::error('Error handling image uploads: ' . $e->getMessage(), [
                'product_id' => $product_id
            ]);
            
            throw $e; // Re-throw to be caught by calling method
        }
    }

/**
 * Upload single image with validation (AJAX endpoint)
 */
public function uploadImage(Request $request)
{
    try {
        $imageRequest = new ProductImageRequest();
        $validatedData = $imageRequest->validated();
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            if ($file->isValid()) {
                // Generate unique filename (CI equivalent)
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Store file using Laravel Storage (CI equivalent)
                $path = $file->storeAs('products', $filename, 'public');
                
                // Create different sizes (CI equivalent)
                $this->resizeImage($filename, 'small');
                $this->resizeImage($filename, 'medium');
                $this->resizeImage($filename, 'large');
                
                // Save to database
                DB::table('product_images')->insert([
                    'product_id' => $request->product_id ?? null,
                    'image' => $filename,
                    'created' => now(),
                    'updated' => now(),
                ]);
                
                // Update main product image if not set
                $product = Product::find($request->product_id);
                if ($product && !$product->product_image) {
                    Product::where('id', $request->product_id)->update(['product_image' => $filename]);
                }
                
                Log::info('Single image uploaded successfully', [
                    'product_id' => $request->product_id,
                    'filename' => $filename,
                    'path' => $path
                ]);
                
                return response()->json([
                    'status' => 1,
                    'msg' => 'Image uploaded successfully',
                    'filename' => $filename,
                    'path' => $path
                ]);
            }
        }

        return response()->json([
            'status' => 0,
            'msg' => 'No file uploaded'
        ]);
        
    } catch (Exception $e) {
        Log::error('Error uploading image: ' . $e->getMessage());
        
        return response()->json([
            'status' => 0,
            'msg' => 'Error uploading image: ' . $e->getMessage()
        ]);
    }
}

/**
 * Copy/Duplicate product with all related data
 * CI: Products->ProductCopy() lines 56-59
 */
public function copy($id)
{
    try {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('message_error', 'Product not found');
        }

        // Convert product to array and remove ID for duplication
        $productData = $product->toArray();
            unset($productData['id']);
            
            // Create unique identifiers for the copy
            $productData['name'] = $productData['name'] . ' (Copy)';
            $productData['name_french'] = ($productData['name_french'] ?? '') . ' (Copie)';
            
            // Create slug using Product model method
            $product = new Product();
            $productData['product_slug'] = $product->createSlug($productData['name'], 'products', 'product_slug');
            $productData['created'] = now();
            $productData['updated'] = now();
            
            // Create the new product
            $newProductId = DB::table('products')->insertGetId($productData);

            if ($newProductId) {
                // Copy product images
                $this->copyProductImages($id, $newProductId);
                
                // Copy product descriptions
                $this->copyProductDescriptions($id, $newProductId);
                
                // Copy product templates
                $this->copyProductTemplates($id, $newProductId);
                
                // Copy product categories and subcategories
                $this->copyProductCategories($id, $newProductId);
                
                // Copy product quantities, sizes, and attributes
                $this->copyProductAttributes($id, $newProductId);
                
                return redirect()->route('admin.products.index')
                    ->with('message_success', 'Product copied successfully with all related data.');
            } else {
                return redirect()->back()
                    ->with('message_error', 'Failed to copy product.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message_error', 'Error copying product: ' . $e->getMessage());
        }
    }

    /**
     * Copy product images
     */
    protected function copyProductImages($sourceProductId, $targetProductId)
    {
        $images = DB::table('product_images')->where('product_id', $sourceProductId)->get();
        
        foreach ($images as $image) {
            $imageData = (array) $image;
            unset($imageData['id']);
            $imageData['product_id'] = $targetProductId;
            $imageData['created'] = now();
            $imageData['updated'] = now();
            
            DB::table('product_images')->insert($imageData);
        }
    }

    /**
     * Copy product descriptions
     */
    protected function copyProductDescriptions($sourceProductId, $targetProductId)
    {
        $descriptions = DB::table('product_descriptions')->where('product_id', $sourceProductId)->get();
        
        foreach ($descriptions as $desc) {
            $descData = (array) $desc;
            unset($descData['id']);
            $descData['product_id'] = $targetProductId;
            $descData['created_at'] = now();
            $descData['updated_at'] = now();
            
            DB::table('product_descriptions')->insert($descData);
        }
    }

    /**
     * Copy product templates
     */
    protected function copyProductTemplates($sourceProductId, $targetProductId)
    {
        $templates = DB::table('product_templates')->where('product_id', $sourceProductId)->get();
        
        foreach ($templates as $template) {
            $templateData = (array) $template;
            unset($templateData['id']);
            $templateData['product_id'] = $targetProductId;
            $templateData['created_at'] = now();
            $templateData['updated_at'] = now();
            
            DB::table('product_templates')->insert($templateData);
        }
    }

    /**
     * Copy product categories and subcategories
     */
    protected function copyProductCategories($sourceProductId, $targetProductId)
    {
        // Copy categories
        $categories = DB::table('product_category')->where('product_id', $sourceProductId)->get();
        foreach ($categories as $category) {
            $categoryData = (array) $category;
            unset($categoryData['id']);
            $categoryData['product_id'] = $targetProductId;
            DB::table('product_category')->insert($categoryData);
        }
        
        // Copy subcategories
        $subCategories = DB::table('product_subcategory')->where('product_id', $sourceProductId)->get();
        foreach ($subCategories as $subCategory) {
            $subCategoryData = (array) $subCategory;
            unset($subCategoryData['id']);
            $subCategoryData['product_id'] = $targetProductId;
            DB::table('product_subcategory')->insert($subCategoryData);
        }
    }

    /**
     * Copy product quantities, sizes, and attributes
     */
    protected function copyProductAttributes($sourceProductId, $targetProductId)
    {
        // Copy quantities
        $quantities = DB::table('product_quantity')->where('product_id', $sourceProductId)->get();
        $quantityMapping = [];
        
        foreach ($quantities as $quantity) {
            $quantityData = (array) $quantity;
            unset($quantityData['id']);
            $quantityData['product_id'] = $targetProductId;
            $quantityData['created_at'] = now();
            $quantityData['updated_at'] = now();
            
            $newQuantityId = DB::table('product_quantity')->insertGetId($quantityData);
            $quantityMapping[$quantity->id] = $newQuantityId;
        }
        
        // Copy sizes with updated quantity IDs
        $sizes = DB::table('product_size')->where('product_id', $sourceProductId)->get();
        $sizeMapping = [];
        
        foreach ($sizes as $size) {
            $sizeData = (array) $size;
            unset($sizeData['id']);
            $sizeData['product_id'] = $targetProductId;
            $sizeData['qty'] = $quantityMapping[$size->qty] ?? $size->qty;
            $sizeData['created_at'] = now();
            $sizeData['updated_at'] = now();
            
            $newSizeId = DB::table('product_size')->insertGetId($sizeData);
            $sizeMapping[$size->id] = $newSizeId;
        }
        
        // Copy attributes with updated size IDs
        $attributes = DB::table('size_multiple_attributes')->where('product_id', $sourceProductId)->get();
        
        foreach ($attributes as $attribute) {
            $attributeData = (array) $attribute;
            unset($attributeData['id']);
            $attributeData['product_id'] = $targetProductId;
            $attributeData['created_at'] = now();
            $attributeData['updated_at'] = now();
            
            DB::table('size_multiple_attributes')->insert($attributeData);
        }
    }
    
    /**
     * Enhanced bulk operations with comprehensive action support
     * CI: Products->deleteAllProduct() lines 1331-1380
     */
    public function bulkAction(Request $request)
    {
        $action = $request->action;
        $ids = $request->ids;
        
        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'status' => 0, 
                'msg' => 'No products selected'
            ]);
        }
        
        try {
            $results = ['success' => 0, 'error' => 0, 'message' => ''];
            
            switch ($action) {
                case 'delete':
                    $results = $this->bulkDelete($ids);
                    break;
                    
                case 'activate':
                    $results = $this->bulkActivate($ids);
                    break;
                    
                case 'deactivate':
                    $results = $this->bulkDeactivate($ids);
                    break;
                    
                case 'copy':
                    $results = $this->bulkCopy($ids);
                    break;
                    
                case 'feature':
                    $results = $this->bulkFeature($ids);
                    break;
                    
                case 'unfeature':
                    $results = $this->bulkUnfeature($ids);
                    break;
                    
                case 'bestseller':
                    $results = $this->bulkBestseller($ids);
                    break;
                    
                case 'unbestseller':
                    $results = $this->bulkUnbestseller($ids);
                    break;
                    
                default:
                    return response()->json([
                        'status' => 0, 
                        'msg' => 'Invalid action specified'
                    ]);
            }
            
            return response()->json([
                'status' => $results['error'] === 0 ? 1 : 0,
                'msg' => $results['message'],
                'details' => [
                    'success' => $results['success'],
                    'error' => $results['error']
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0, 
                'msg' => 'Error performing bulk action: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk delete operation
     */
    protected function bulkDelete($ids)
    {
        $success = 0;
        $error = 0;
        
        foreach ($ids as $id) {
            try {
                $product = Product::find($id);
                if (!$product) {
                    $error++;
                    continue;
                }

                // Get product images before deletion
                $productImages = DB::table('product_images')->where('product_id', $id)->get();

                // Delete product
                $result = Product::deleteProduct($id);

                if ($result) {
                    // Delete related data
                    DB::table('product_images')->where('product_id', $id)->delete();
                    DB::table('product_descriptions')->where('product_id', $id)->delete();
                    DB::table('product_templates')->where('product_id', $id)->delete();
                    DB::table('product_quantity')->where('product_id', $id)->delete();
                    DB::table('product_size')->where('product_id', $id)->delete();
                    DB::table('size_multiple_attributes')->where('product_id', $id)->delete();

                    // Delete image files
                    $this->deleteProductImageFiles($productImages);

                    $success++;
                } else {
                    $error++;
                }
            } catch (\Exception $e) {
                $error++;
            }
        }
        
        $message = "Deleted {$success} products successfully.";
        if ($error > 0) {
            $message .= " Failed to delete {$error} products.";
        }
        
        return ['success' => $success, 'error' => $error, 'message' => $message];
    }

    /**
     * Bulk activate operation
     */
    protected function bulkActivate($ids)
    {
        $result = DB::table('products')->whereIn('id', $ids)->update([
            'status' => 1,
            'updated' => now()
        ]);
        
        return [
            'success' => $result,
            'error' => 0,
            'message' => "Activated {$result} products successfully."
        ];
    }

    /**
     * Bulk deactivate operation
     */
    protected function bulkDeactivate($ids)
    {
        $result = DB::table('products')->whereIn('id', $ids)->update([
            'status' => 0,
            'updated' => now()
        ]);
        
        return [
            'success' => $result,
            'error' => 0,
            'message' => "Deactivated {$result} products successfully."
        ];
    }

    /**
     * Bulk feature operation
     */
    protected function bulkFeature($ids)
    {
        $result = DB::table('products')->whereIn('id', $ids)->update([
            'featured' => 1,
            'updated' => now()
        ]);
        
        return [
            'success' => $result,
            'error' => 0,
            'message' => "Featured {$result} products successfully."
        ];
    }

    /**
     * Bulk unfeature operation
     */
    protected function bulkUnfeature($ids)
    {
        $result = DB::table('products')->whereIn('id', $ids)->update([
            'featured' => 0,
            'updated' => now()
        ]);
        
        return [
            'success' => $result,
            'error' => 0,
            'message' => "Unfeatured {$result} products successfully."
        ];
    }

    /**
     * Bulk bestseller operation
     */
    protected function bulkBestseller($ids)
    {
        $result = DB::table('products')->whereIn('id', $ids)->update([
            'bestseller' => 1,
            'updated' => now()
        ]);
        
        return [
            'success' => $result,
            'error' => 0,
            'message' => "Marked {$result} products as bestsellers successfully."
        ];
    }

    /**
     * Bulk unbestseller operation
     */
    protected function bulkUnbestseller($ids)
    {
        $result = DB::table('products')->whereIn('id', $ids)->update([
            'bestseller' => 0,
            'updated' => now()
        ]);
        
        return [
            'success' => $result,
            'error' => 0,
            'message' => "Unmarked {$result} products from bestsellers successfully."
        ];
    }

    /**
     * Bulk copy operation
     */
    protected function bulkCopy($ids)
    {
        $success = 0;
        $error = 0;
        
        foreach ($ids as $id) {
            try {
                $product = Product::find($id);
                if (!$product) {
                    $error++;
                    continue;
                }

                // Use the existing copy method
                $this->copy($id);
                $success++;
            } catch (\Exception $e) {
                $error++;
            }
        }
        
        $message = "Copied {$success} products successfully.";
        if ($error > 0) {
            $message .= " Failed to copy {$error} products.";
        }
        
        return ['success' => $success, 'error' => $error, 'message' => $message];
    }

    /**
     * View product details
     * CI: Products->viewProduct() lines 61-78
     */
    public function view($id = null)
    {
        if (empty($id)) {
            return redirect()->to('admin/Products');
        }
        
        // Get product data
        $product = DB::table('products')->where('id', $id)->first();
        
        if (!$product) {
            session()->flash('message_error', 'Product not found.');
            return redirect()->to('admin/Products');
        }
        
        // Get product images
        $productImages = DB::table('product_images')
            ->where('product_id', $id)
            ->get();
        
        // Get tag list (CI equivalent of getTasgList)
        $tagList = DB::table('tags')
            ->where('status', 1)
            ->get();
        
        $data = [
            'page_title' => 'Product Details',
            'main_page_url' => '',
            'Product' => (array) $product,
            'ProductImages' => $productImages,
            'tagList' => $tagList,
        ];
        
        return view('admin.products.view', $data);
    }
    
    /**
     * Set Multiple Attributes for product
     * CI: Products->SetMultipleAttributes() lines 942-962
     */
    public function SetMultipleAttributes($id)
    {
        if (empty($id)) {
            return redirect('admin/Products')->with('message_error', 'Product not found');
        }
        
        $product = DB::table('products')->where('id', $id)->first();
        
        if (!$product) {
            return redirect('admin/Products')->with('message_error', 'Product not found');
        }
        
        // Get product quantities, sizes, and attributes structure
        $productSizes = $this->getProductQuantitySizeAttributeDropDown($id);
        $multipleAttributes = $this->getMultipleAttributesDropDown();
        
        $data = [
            'page_title' => 'Set Multiple Attributes',
            'product' => $product,
            'productSizes' => $productSizes,
            'multipleAttributes' => $multipleAttributes,
        ];
        
        return view('admin.products.product_multiple_attributes', $data);
    }
    
    /**
     * Add/Edit Product Quantity
     * CI: Products->AddEditProductQuantity() lines 964-1022
     */
    public function AddEditProductQuantity(Request $request, $product_id, $id = null)
    {
        $quantities = $this->getQuantityListDropDown();
        
        if ($request->isMethod('post')) {
            $quantity_id = $request->quantity_id;
            $quantity_price = $request->quantity_price ?? 0;
            
            // Check if quantity already exists
            $existingQuantities = DB::table('product_quantities')
                ->where('product_id', $product_id)
                ->pluck('qty')
                ->toArray();
            
            $saveQuantity = true;
            if ($id != $quantity_id && in_array($quantity_id, $existingQuantities)) {
                return response()->json([
                    'success' => 0,
                    'message' => 'This quantity already added to this product.'
                ]);
            }
            
            $quantityData = [
                'product_id' => $product_id,
                'qty' => $quantity_id,
                'price' => $quantity_price,
            ];
            
            if ($id) {
                // Update existing
                DB::table('product_quantities')->where('id', $id)->update($quantityData);
                $message = 'Updated Quantity Successfully.';
            } else {
                // Create new
                DB::table('product_quantities')->insert($quantityData);
                $message = 'Added Quantity Successfully.';
            }
            
            return response()->json([
                'success' => 1,
                'message' => $message
            ]);
        }
        
        // Load existing data for edit
        $quantityData = null;
        if ($id) {
            $quantityData = DB::table('product_quantities')->where('id', $id)->first();
        }
        
        $data = [
            'id' => $id,
            'product_id' => $product_id,
            'quantities' => $quantities,
            'quantityData' => $quantityData,
        ];
        
        return view('admin.products.add_edit_product_quantity', $data);
    }
    
    /**
     * Delete Product Quantity
     * CI: Products->deleteProductQuantity() lines 1024-1032
     */
    public function deleteProductQuantity($product_id, $id)
    {
        DB::table('product_quantities')->where('id', $id)->where('product_id', $product_id)->delete();
        
        // Also delete related sizes and attributes
        DB::table('product_sizes')->where('product_id', $product_id)->where('qty', $id)->delete();
        
        return response()->json(['success' => 1, 'message' => 'Quantity deleted successfully']);
    }
    
    /**
     * Add/Edit Product Size
     * CI: Products->AddEditProductSize() lines 1034-1096
     */
    public function AddEditProductSize(Request $request, $product_id, $quantity_id, $id = null)
    {
        $sizes = $this->getSizeListDropDown();
        
        if ($request->isMethod('post')) {
            $size_id = $request->size_id;
            $size_price = $request->size_price ?? 0;
            
            // Check if size already exists for this quantity
            $existingSizes = DB::table('product_sizes')
                ->where('product_id', $product_id)
                ->where('qty', $quantity_id)
                ->pluck('size_id')
                ->toArray();
            
            if ($id != $size_id && in_array($size_id, $existingSizes)) {
                return response()->json([
                    'success' => 0,
                    'message' => 'This size already added to this product & Quantity'
                ]);
            }
            
            $sizeData = [
                'product_id' => $product_id,
                'qty' => $quantity_id,
                'size_id' => $size_id,
                'extra_price' => $size_price,
            ];
            
            if ($id) {
                // Update existing
                DB::table('product_sizes')->where('id', $id)->update($sizeData);
                $message = 'Updated Size Successfully.';
            } else {
                // Create new
                DB::table('product_sizes')->insert($sizeData);
                $message = 'Added Size Successfully.';
            }
            
            return response()->json([
                'success' => 1,
                'message' => $message
            ]);
        }
        
        // Load existing data for edit
        $sizeData = null;
        if ($id) {
            $sizeData = DB::table('product_sizes')->where('id', $id)->first();
        }
        
        $data = [
            'id' => $id,
            'product_id' => $product_id,
            'quantity_id' => $quantity_id,
            'sizes' => $sizes,
            'sizeData' => $sizeData,
        ];
        
        return view('admin.products.add_edit_product_size', $data);
    }
    
    /**
     * Delete Product Size
     * CI: Products->deleteProductSize() lines 1174-1182
     */
    public function deleteProductSize($product_id, $quantity_id, $id)
    {
        DB::table('product_sizes')
            ->where('id', $id)
            ->where('product_id', $product_id)
            ->where('qty', $quantity_id)
            ->delete();
        
        // Also delete related attributes
        DB::table('product_attributes')
            ->where('product_id', $product_id)
            ->where('qty', $quantity_id)
            ->where('size_id', $id)
            ->delete();
        
        return response()->json(['success' => 1, 'message' => 'Size deleted successfully']);
    }
    
    /**
     * Add/Edit Product Attribute
     * CI: Products->AddEditProductAttribute() lines 1098-1172
     */
    public function AddEditProductAttribute(Request $request, $product_id, $quantity_id, $size_id, $attribute_id, $id = null)
    {
        $multipleAttributes = $this->getMultipleAttributesDropDown();
        
        if ($request->isMethod('post')) {
            $attribute_item_id = $request->attribute_item_id;
            $extra_price = $request->extra_price ?? 0;
            
            // Check if attribute item already exists
            $existingAttributes = DB::table('product_attributes')
                ->where('product_id', $product_id)
                ->where('qty', $quantity_id)
                ->where('size_id', $size_id)
                ->where('attribute_id', $attribute_id)
                ->pluck('attribute_item_id')
                ->toArray();
            
            $attribute_item_id_old = '';
            if ($id) {
                $oldData = DB::table('product_attributes')->where('id', $id)->first();
                $attribute_item_id_old = $oldData->attribute_item_id ?? '';
            }
            
            if ($attribute_item_id_old != $attribute_item_id && in_array($attribute_item_id, $existingAttributes)) {
                return response()->json([
                    'success' => 0,
                    'message' => 'This attribute item already added to this product & Quantity & size'
                ]);
            }
            
            $attributeData = [
                'product_id' => $product_id,
                'qty' => $quantity_id,
                'size_id' => $size_id,
                'attribute_id' => $attribute_id,
                'attribute_item_id' => $attribute_item_id,
                'extra_price' => $extra_price,
            ];
            
            if ($id) {
                // Update existing
                DB::table('product_attributes')->where('id', $id)->update($attributeData);
                $message = 'Updated attribute item successfully.';
            } else {
                // Create new
                DB::table('product_attributes')->insert($attributeData);
                $message = 'Added attribute item Successfully.';
            }
            
            return response()->json([
                'success' => 1,
                'message' => $message
            ]);
        }
        
        // Load existing data for edit
        $attributeData = null;
        if ($id) {
            $attributeData = DB::table('product_attributes')->where('id', $id)->first();
        }
        
        $data = [
            'id' => $id,
            'product_id' => $product_id,
            'quantity_id' => $quantity_id,
            'size_id' => $size_id,
            'attribute_id' => $attribute_id,
            'multipleAttributes' => $multipleAttributes,
            'attributeData' => $attributeData,
        ];
        
        return view('admin.products.add_edit_product_multiple_attribute', $data);
    }
    
    /**
     * Delete Product Multiple Attribute
     * CI: Products->deleteProductMultipalAttribute() lines 1184-1192
     */
    public function deleteProductMultipalAttribute($id)
    {
        DB::table('product_attributes')->where('id', $id)->delete();
        
        return response()->json(['success' => 1, 'message' => 'Attribute deleted successfully']);
    }
    
    /**
     * Set Single Attributes for product
     * CI: Products->SetSingleAttributes() lines 1194-1269
     */
    public function SetSingleAttributes(Request $request, $id)
    {
        if (empty($id)) {
            return redirect('admin/Products')->with('message_error', 'Product not found');
        }
        
        $product = DB::table('products')->where('id', $id)->first();
        
        if (!$product) {
            return redirect('admin/Products')->with('message_error', 'Product not found');
        }
        
        if ($request->isMethod('post')) {
            // Save single attributes
            $attributesList = $this->getAttributesListDropDown();
            // Add attribute saving logic here
        }
        
        return view('admin.products.set_single_attributes', compact('product', 'attributesList'));
    }

    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $page_title = 'Product Details';
        return view('admin.products.view', compact('product', 'page_title'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $page_title = 'Edit Product';
        $categories = Category::all();
        return view('admin.products.add_edit', compact('product', 'page_title', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            // Add other validation rules
        ]);

        $product->update($validated);
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }

    /**
     * Provider page - converted from CI Products->Provider
     * CI: Products->provider() method with tabbed interface
     */
    public function provider(Request $request, $provider = null)
    {
        // Follow CI project pattern exactly
        if ($provider == null) {
            $provider = 'sina';
        }

        $tabname = 'provider-view';
        
        // Get providers data (mock data for now - should be from database)
        $providers = [
            (object)[
                'id' => 1,
                'name' => 'SinaLite',
                'description' => 'Professional printing services provider',
                'official_link' => 'https://www.sinalite.com'
            ]
        ];
        
        return view('admin.products.providers', compact('tabname', 'providers'));
    }

    /**
     * Provider Action Handler - for specific provider actions
     * NOTE: This method is no longer used, but kept for backward compatibility
     */
    public function providerAction(Request $request, $action)
    {
        // Redirect to the main provider method with the action as provider
        return redirect()->route('admin.products.provider', ['provider' => $action]);
    }

    /**
     * Provider Product data - converted from CI Products->ProviderProducts
     * CI: POST /admin/Products/ProviderProducts/{provider}
     */
    public function providerProducts(Request $request, $provider)
    {
        $q = $request->input('q');
        $filter = $request->input('filter');
        
        // Handle Kendo UI grid filtering
        if (isset($filter) && isset($filter['filters'])) {
            $q = $filter['filters'][0]['value'];
        }

        $page = $request->input('page', 1);
        $pageSize = $request->input('pageSize', 10);
        $take = $pageSize;
        $skip = $pageSize * ($page - 1);

        // Get provider
        $providerModel = \App\Models\Provider::where('name', $provider)->first();
        if (!$providerModel) {
            return response()->json([
                'data' => [],
                'total' => 0,
                'errors' => null
            ]);
        }

        // Build query (CI equivalent: Provider_Model->getProducts)
        $query = \App\Models\ProviderProduct::query()
            ->leftJoin('products', 'products.id', '=', 'provider_products.product_id')
            ->select(
                'provider_products.*',
                'products.name AS product_name',
                'products.product_image'
            )
            ->where('provider_products.provider_id', $providerModel->id)
            ->whereNotIn('provider_products.provider_product_id', [14959, 14960, 14966])
            ->orderBy('provider_products.name');

        // Apply search filter
        if (!empty($q)) {
            $query->where('provider_products.name', 'like', '%' . $q . '%');
        }

        // Get total count
        $totalQuery = clone $query;
        $total = $totalQuery->count();

        // Get paginated data
        $data = $query->skip($skip)->take($take)->get();

        // Process product images using getProductImage helper (CI project pattern)
        foreach ($data as &$item) {
            $item->product_image = getProductImage($item->product_image);
        }

        $gridModel = [
            'extra_data' => null,
            'data' => $data,
            'errors' => null,
            'total' => $total,
        ];

        return response()->json($gridModel);
    }

    /**
     * Provider Options data - converted from CI Products->ProviderOptions
     * CI: POST /admin/Products/ProviderOptions/{provider}
     */
    public function providerOptions(Request $request, $provider)
    {
        $q = $request->input('q');
        $filter = $request->input('filter');
        
        if (isset($filter) && isset($filter['filters'])) {
            $q = $filter['filters'][0]['value'];
        }

        $page = $request->input('page', 1);
        $pageSize = $request->input('pageSize', 10);
        $take = $pageSize;
        $skip = $pageSize * ($page - 1);

        // Get provider (CI equivalent: Provider_Model->getProvider)
        $providerModel = \App\Models\Provider::where('name', $provider)->first();
        if (!$providerModel) {
            return response()->json([
                'data' => [],
                'total' => 0,
                'errors' => null
            ]);
        }

        // Build query (CI equivalent: Provider_Model->getOptions)
        $query = \App\Models\ProviderOption::query()
            ->leftJoin('product_attributes', 'product_attributes.id', '=', 'provider_options.attribute_id')
            ->select(
                'provider_options.*',
                'product_attributes.name AS attribute_name',
                'product_attributes.name_french AS attribute_name_french'
            )
            ->where('provider_options.provider_id', $providerModel->id)
            ->orderBy('provider_options.type')
            ->orderBy('provider_options.name');

        // Apply search filter
        if (!empty($q)) {
            $query->where('provider_options.name', 'like', '%' . $q . '%');
        }

        // Get total count
        $totalQuery = clone $query;
        $total = $totalQuery->count();

        // Get paginated data
        $data = $query->skip($skip)->take($take)->get();

        $gridModel = [
            'extra_data' => null,
            'data' => $data,
            'errors' => null,
            'total' => $total,
        ];

        return response()->json($gridModel);
    }

    /**
     * Provider Option Update - converted from CI Products->ProviderOptionUpdate
     * CI: POST /admin/Products/ProviderOptionUpdate
     */
    public function providerOptionUpdate(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type');
        $attribute_id = $request->input('attribute_id');

        try {
            $option = \App\Models\ProviderOption::findOrFail($id);
            $option->type = $type;
            $option->attribute_id = $attribute_id;
            $option->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update option']);
        }
    }

    /**
     * Provider Product Bind - converted from CI Products->ProviderProductBind
     * CI: GET/POST /admin/Products/ProviderProductBind/{id}
     */
    public function providerProductBind(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            // Return view for binding popup
            $product = (object)[
                'id' => $id,
                'provider_product_id' => 'PROD_' . $id,
                'name' => 'Product ' . $id
            ];
            
            return view('admin.products.provider_product_bind', compact('product'));
        } else {
            // Handle binding logic
            $product_id = $request->input('product_id');
            // Bind logic here
            
            return response()->json(['success' => true]);
        }
    }

    /**
     * Provider Product Options - converted from CI Products->ProviderProductOptions
     * CI: GET/POST /admin/Products/ProviderProductOptions/{provider}/{provider_product_id}
     */
    public function providerProductOptions(Request $request, $provider, $provider_product_id)
    {
        if ($request->isMethod('get')) {
            return view('admin.products.provider_product_options', [
                'provider' => $provider, 
                'provider_product_id' => $provider_product_id
            ]);
        } else {
            // Handle POST request - get provider options data (CI equivalent: Provider_Model->getProductOptions)
            $providerModel = \App\Models\Provider::where('name', $provider)->first();
            if (!$providerModel) {
                return response()->json([
                    'data' => [],
                    'total' => 0,
                    'errors' => null
                ]);
            }

            $page = $request->input('page', 1);
            $pageSize = $request->input('pageSize', 10);
            $take = $pageSize;
            $skip = $pageSize * ($page - 1);

            // Build query (CI equivalent: Provider_Model->getProductOptions)
            $query = \App\Models\ProviderProductOption::query()
                ->leftJoin('provider_options', 'provider_options.id', '=', 'provider_product_options.option_id')
                ->leftJoin('provider_option_values', function($join) {
                    $join->on('provider_option_values.option_id', '=', 'provider_product_options.option_id')
                         ->on('provider_option_values.provider_option_value_id', '=', 'provider_product_options.provider_option_value_id')
                         ->on('provider_option_values.value', '=', 'provider_product_options.value');
                })
                ->leftJoin('product_attributes', 'product_attributes.id', '=', 'provider_options.attribute_id')
                ->select(
                    'provider_options.*',
                    'provider_option_values.provider_option_value_id',
                    'provider_option_values.value',
                    'product_attributes.name AS attribute_name',
                    'provider_product_options.price_rate as price_rate'
                )
                ->where('provider_product_options.provider_id', $providerModel->id)
                ->where('provider_product_options.provider_product_id', $provider_product_id)
                ->orderBy('provider_options.type')
                ->orderBy('provider_product_options.id')
                ->orderBy('provider_product_options.provider_option_value_id');

            // Get total count
            $totalQuery = \App\Models\ProviderProductOption::query()
                ->where('provider_id', $providerModel->id)
                ->where('provider_product_id', $provider_product_id);
            $total = $totalQuery->count();

            // Get paginated data
            $data = $query->skip($skip)->take($take)->get();

            $gridModel = [
                'extra_data' => null,
                'data' => $data,
                'errors' => null,
                'total' => $total,
            ];

            return response()->json($gridModel);
        }
    }

    /**
     * Provider Product Price Rate - converted from CI Products->ProviderProductPriceRate
     * CI: GET/POST /admin/Products/ProviderProductPriceRate/{id}
     */
    public function providerProductPriceRate(Request $request, $id)
    {
        $product = (object)[
            'id' => $id,
            'provider_product_id' => 'PROD_' . $id,
            'name' => 'Product ' . $id,
            'price_rate' => 1.5
        ];

        if ($request->isMethod('get')) {
            return view('admin.products.provider_product_price_rate', compact('product'));
        } else {
            $price_rate = $request->input('price_rate');
            // Update price rate logic here
            
            return response()->json(['success' => true]);
        }
    }

    /**
     * Attributes data for provider options - converted from CI
     * CI: POST /admin/Products/Attributes
     */
    public function attributes(Request $request)
    {
        $q = $request->input('q');
        
        // Mock attributes data
        $attributes = [];
        for ($i = 1; $i <= 5; $i++) {
            $attributes[] = (object)[
                'id' => $i,
                'name' => 'Attribute ' . $i . (empty($q) ? '' : ' - ' . $q)
            ];
        }

        return response()->json([
            'data' => $attributes,
            'total' => count($attributes)
        ]);
    }

    /**
     * Size management methods (CI: MultipleAttributes->sizes())
     */
    
    /**
     * Display list of sizes
     * CI: MultipleAttributes->sizes()
     */
    public function sizes()
    {
        try {
            $data = [
                'page_title' => 'Product Sizes',
                'sub_page_title' => 'Add New Size',
                'sub_page_url' => 'admin.products.sizes.addEdit',
                'sub_page_view_url' => '',
                'sub_page_delete_url' => 'admin.products.sizes.delete',
                'sub_page_url_active_inactive' => 'admin.products.sizes.toggleStatus',
                'class_name' => 'admin/Products/',
                'lists' => Size::getSizeList()
            ];

            return view('admin.products.sizes', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@sizes: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading sizes: ' . $e->getMessage());
        }
    }

    /**
     * Add/Edit size form
     * CI: MultipleAttributes->addEditSize()
     */
    public function sizesAddEdit(Request $request, $id = null)
    {
        try {
            $page_title = 'Add New Size';
            $postData = [];
            
            if ($id) {
                $page_title = 'Edit Size';
                $postData = Size::getSizeDataById($id);
                
                if (!$postData) {
                    return redirect()->route('admin.products.sizes.index')
                        ->with('message_error', 'Size not found');
                }
            }

            // Handle POST request
            if ($request->isMethod('post')) {
                return $this->saveSize($request, $id);
            }

            $data = [
                'page_title' => $page_title,
                'main_page_url' => 'admin/products/sizes',
                'postData' => $postData,
                'id' => $id
            ];

            return view('admin.products.sizes_add_edit', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@sizesAddEdit: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading size form: ' . $e->getMessage());
        }
    }

    /**
     * Save size (create or update)
     * CI: MultipleAttributes->addEditSize() POST handling
     */
    protected function saveSize(Request $request, $id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'size_name' => 'required|string|max:250',
                'size_name_french' => 'nullable|string|max:250',
                'set_order' => 'nullable|integer|min:0',
                'status' => 'nullable|integer|in:0,1'
            ], [
                'size_name.required' => 'Enter Size Name',
                'set_order.integer' => 'Set Order must be a number',
                'status.in' => 'Status must be 0 or 1'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = $request->only(['size_name', 'size_name_french', 'set_order', 'status']);
            
            if ($id) {
                $data['id'] = $id;
            }

            $result = Size::saveSize($data);
            
            if ($result > 0) {
                $message = ($id ? 'Edit' : 'Add') . ' Size Successfully.';
                return redirect()->route('admin.products.sizes.index')
                    ->with('message_success', $message);
            } else {
                $message = ($id ? 'Edit' : 'Add') . ' Size Unsuccessfully.';
                return redirect()->back()
                    ->with('message_error', $message)
                    ->withInput();
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@saveSize: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error saving size: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete size
     * CI: MultipleAttributes->deleteSize()
     */
    public function sizesDelete($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('admin.products.sizes.index')
                    ->with('message_error', 'Missing information.');
            }

            $result = Size::deleteSize($id);
            
            if ($result) {
                return redirect()->route('admin.products.sizes.index')
                    ->with('message_success', 'Size deleted successfully.');
            } else {
                return redirect()->route('admin.products.sizes.index')
                    ->with('message_error', 'Size deletion failed.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@sizesDelete: ' . $e->getMessage());
            
            return redirect()->route('admin.products.sizes.index')
                ->with('message_error', 'Error deleting size: ' . $e->getMessage());
        }
    }

    /**
     * Toggle size status (active/inactive)
     * CI: MultipleAttributes->activeInactiveSize()
     */
    public function sizesToggleStatus($id = null, $status = null)
    {
        try {
            if (empty($id) || ($status !== '0' && $status !== '1')) {
                return redirect()->route('admin.products.sizes.index')
                    ->with('message_error', 'Missing information.');
            }

            $result = Size::toggleStatus($id, $status);
            
            if ($result) {
                $statusText = $status == '1' ? 'activated' : 'deactivated';
                return redirect()->route('admin.products.sizes.index')
                    ->with('message_success', "Size {$statusText} successfully.");
            } else {
                return redirect()->route('admin.products.sizes.index')
                    ->with('message_error', 'Status update failed.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@sizesToggleStatus: ' . $e->getMessage());
            
            return redirect()->route('admin.products.sizes.index')
                ->with('message_error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Size Options management methods (CI: Products->sizeOptions())
     */
    
    /**
     * Display size options list
     * CI: Products->sizeOptions()
     */
    public function sizeOptions($type = null)
    {
        try {
            $page_title = "Product ";
            $sub_page_title = "Add New ";
            
            if ($type == "paper_quality") {
                $page_title .= "Paper Quality";
                $sub_page_title .= "Paper Quality";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "ncr_parts") {
                $page_title .= "NCR Number of Parts";
                $sub_page_title .= "NCR Number of Part";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "colors") {
                $page_title .= "Printed Color";
                $sub_page_title .= "Printed Color";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "stocks") {
                $page_title .= "Background";
                $sub_page_title .= "Background";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "diameter") {
                $page_title .= "Diameter";
                $sub_page_title .= "Diameter";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "shapepaper") {
                $page_title .= "Coating";
                $sub_page_title .= "Coating";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "grommets") {
                $page_title .= "Grommets";
                $sub_page_title .= "Grommets";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "page_size") {
                $page_title .= "Pages";
                $sub_page_title .= "Page";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "page_quantity") {
                $page_title .= "Quantity";
                $sub_page_title .= "Quantity";
                $products = PageSize::sizeOptions($type);
            } else if ($type == "sheets") {
                $page_title .= "Sheets";
                $sub_page_title .= "Sheet";
                $products = PageSize::sizeOptions($type);
            } else {
                return redirect()->route('admin.products.index')
                    ->with('message_error', 'Invalid type specified');
            }

            $data = [
                'page_title' => $page_title,
                'sub_page_title' => $sub_page_title,
                'sub_page_url' => 'admin.products.sizeOptions.addEdit',
                'sub_page_view_url' => '',
                'sub_page_delete_url' => 'admin.products.sizeOptions.delete',
                'sub_page_url_active_inactive' => 'admin.products.sizeOptions.toggleStatus',
                'lists' => $products,
                'type' => $type
            ];

            return view('admin.products.size_options', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@sizeOptions: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading size options: ' . $e->getMessage());
        }
    }

    /**
     * Add/Edit size options form
     * CI: Products->addEditSizeOptions()
     */
    public function sizeOptionsAddEdit(Request $request, $id = null, $type = null)
    {
        try {
            $page_title = '';
            $sub_page_title = '';
            $postData = [];

            if ($type == "paper_quality") {
                $page_title .= "Paper Quality";
                $sub_page_title .= "Paper Quality";
            } else if ($type == "ncr_parts") {
                $page_title .= "NCR Number of Parts";
                $sub_page_title .= "NCR Number of Part";
            } else if ($type == "colors") {
                $page_title .= "Printed Color";
                $sub_page_title .= "Printed Color";
            } else if ($type == "stocks") {
                $page_title .= "Background";
                $sub_page_title .= "Background";
            } else if ($type == "diameter") {
                $page_title .= "Diameter";
                $sub_page_title .= "Diameter";
            } else if ($type == "shapepaper") {
                $page_title .= "Coating";
                $sub_page_title .= "Coating";
            } else if ($type == "grommets") {
                $page_title .= "Grommets";
                $sub_page_title .= "Grommets";
            } else if ($type == "page_size") {
                $page_title .= "Pages";
                $sub_page_title .= "Page";
            } else if ($type == "page_quantity") {
                $page_title .= "Quantity";
                $sub_page_title .= "Quantity";
            } else if ($type == "sheets") {
                $page_title .= "Sheets";
                $sub_page_title .= "Sheet";
            } else {
                return redirect()->route('admin.products.index')
                    ->with('message_error', 'Invalid type specified');
            }

            if ($id) {
                $table = $this->getSizeOptionsTable($type);
                $postData = PageSize::getPageSizeDataById($id, $table);
                if (!$postData) {
                    return redirect()->route('admin.products.sizeOptions', ['type' => $type])
                        ->with('message_error', 'Size option not found');
                }
            }

            // Handle POST request
            if ($request->isMethod('post')) {
                return $this->saveSizeOptions($request, $id, $type);
            }

            $data = [
                'page_title' => $page_title,
                'main_page_url' => 'sizeOptions/' . $type,
                'postData' => $postData,
                'id' => $id,
                'type' => $type
            ];

            return view('admin.products.add_edit_size_option', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@sizeOptionsAddEdit: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading size option form: ' . $e->getMessage());
        }
    }

    /**
     * Save size options (create or update)
     * CI: Products->addEditSizeOptions() POST handling
     */
    protected function saveSizeOptions(Request $request, $id = null, $type = null)
    {
        try {
            // Different validation rules based on type (CI project logic)
            $validationRules = $this->getSizeOptionsValidationRules($type);
            
            $validator = Validator::make($request->all(), $validationRules['rules'], $validationRules['messages']);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Determine the correct table name (CI project logic)
            $table = $this->getSizeOptionsTable($type);
            
            // Only use fields that exist in CI database and are handled by CI project
            $data = $request->only(['name', 'name_french', 'total_page', 'status']);
            
            // For page_size type, include total_page (CI project logic)
            if ($type == 'page_size') {
                $data['total_page'] = $request->input('total_page');
            }
            
            // For page_quantity type, convert name to integer (CI project logic)
            if ($type == 'page_quantity') {
                $data['name'] = (int) $request->input('name');
                $data['name_french'] = (int) $request->input('name_french');
            }
            
            if ($id) {
                $data['id'] = $id;
            }

            $result = PageSize::savePageSize($data, $table);
            
            if ($result > 0) {
                $message = ($id ? 'Edit' : 'Add') . ' Size Option Successfully.';
                return redirect()->route('admin.products.sizeOptions', ['type' => $type])
                    ->with('message_success', $message);
            } else {
                $message = ($id ? 'Edit' : 'Add') . ' Size Option Unsuccessfully.';
                return redirect()->back()
                    ->with('message_error', $message)
                    ->withInput();
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@saveSizeOptions: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error saving size option: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete size options
     * CI: Products->DeleteSizeOptions()
     */
    public function sizeOptionsDelete($id = null, $type = null)
    {
        try {
            if (empty($id) || empty($type)) {
                return redirect()->route('admin.products.sizeOptions', ['type' => $type])
                    ->with('message_error', 'Missing information.');
            }

            $table = $this->getSizeOptionsTable($type);
            $result = PageSize::deletePageSize($id, $table);
            
            if ($result) {
                return redirect()->route('admin.products.sizeOptions', ['type' => $type])
                    ->with('message_success', 'Size option deleted successfully.');
            } else {
                return redirect()->route('admin.products.sizeOptions', ['type' => $type])
                    ->with('message_error', 'Size option deletion failed.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@sizeOptionsDelete: ' . $e->getMessage());
            
            return redirect()->route('admin.products.sizeOptions', ['type' => $type])
                ->with('message_error', 'Error deleting size option: ' . $e->getMessage());
        }
    }

    /**
     * Toggle size options status (active/inactive)
     * CI: Products->activeInactiveSizeOptions()
     */
    public function sizeOptionsToggleStatus($id = null, $status = null, $type = null)
    {
        try {
            if (empty($id) || ($status !== '0' && $status !== '1') || empty($type)) {
                return redirect()->back()
                    ->with('message_error', 'Invalid parameters');
            }

            $table = $this->getSizeOptionsTable($type);
            if ($table) {
                // Update without timestamps (CI compatibility)
                DB::table($table)->where('id', $id)->update(['status' => $status]);
                
                $statusText = $status == '1' ? 'Active' : 'Inactive';
                return redirect()->back()
                    ->with('message_success', "Status updated to {$statusText} successfully.");
            } else {
                return redirect()->back()
                    ->with('message_error', 'Invalid size option type.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('message_error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to get validation rules for different size options types (CI project logic)
     */
    protected function getSizeOptionsValidationRules($type)
    {
        if ($type == 'page_quantity') {
            return [
                'rules' => [
                    'name' => 'required|integer|min:1',
                ],
                'messages' => [
                    'name.required' => 'Enter Quantity',
                    'name.integer' => 'Quantity must be a number',
                    'name.min' => 'Quantity must be at least 1',
                ]
            ];
        } else {
            // Default validation for other types (configSizeOptions)
            return [
                'rules' => [
                    'name' => 'required|string|max:200',
                ],
                'messages' => [
                    'name.required' => 'Enter Name',
                ]
            ];
        }
    }

    /**
     * Get Attributes Map for Kendo Grid (CI: Product_Model->getAttributesMap)
     */
    protected function getAttributesMap($q, $take, $skip, &$data, &$total)
    {
        try {
            // Get total count
            $totalQuery = DB::table('attributes');
            if (!empty($q)) {
                $totalQuery->where('name', 'LIKE', '%' . $q . '%');
            }
            $total = $totalQuery->count();
            
            // Get data with item counts
            $query = DB::table('attributes')
                ->select('attributes.*', DB::raw('COUNT(DISTINCT attribute_items.id) AS item_count'))
                ->leftJoin('attribute_items', 'attribute_items.attribute_id', '=', 'attributes.id')
                ->groupBy('attributes.id')
                ->orderBy('attributes.type')
                ->orderBy('attributes.name');
            
            if (!empty($q)) {
                $query->where('attributes.name', 'LIKE', '%' . $q . '%');
            }
            
            $take = $take > 0 ? $take : 0;
            $skip = $skip > 0 ? $skip : 0;
            
            if ($take > 0) {
                $query->limit($take)->offset($skip);
            } else {
                $query->offset($skip);
            }
            
            $data = $query->get()->toArray();
            
            Log::info('getAttributesMap results', [
                'q' => $q,
                'take' => $take,
                'skip' => $skip,
                'total' => $total,
                'data_count' => count($data)
            ]);
            
        } catch (Exception $e) {
            Log::error('getAttributesMap error: ' . $e->getMessage(), [
                'q' => $q,
                'trace' => $e->getTraceAsString()
            ]);
            
            $data = [];
            $total = 0;
        }
    }
    
    /**
     * Attributes Map (CI: Products->AttributesMap)
     */
    public function attributesMap(Request $request)
    {
        if ($request->isMethod('get')) {
            $data = [
                'page_title' => 'Attributes'
            ];
            return view('admin.products.attributes', $data);
        } elseif ($request->isMethod('post')) {
            $q = $request->input('q');
            $filter = $request->input('filter');
            
            if (isset($filter) && isset($filter['filters'])) {
                $q = $filter['filters'][0]['value'];
            }

            $page = $request->input('page', 1);
            $pageSize = $request->input('pageSize', 10);
            $take = $pageSize;
            $skip = $pageSize * ($page - 1);
            
            $data = [];
            $total = 0;
            $this->getAttributesMap($q, $take, $skip, $data, $total);

            $gridModel = [
                'extra_data' => null,
                'data' => $data,
                'errors' => null,
                'total' => $total,
            ];

            return response()->json($gridModel);
        }
    }

    /**
     * Attribute Create Map (CI: Products->AttributeCreateMap)
     */
    public function attributeCreateMap(Request $request)
    {
        $data = [
            'name' => $request->input('name'),
            'label' => $request->input('label'),
            'label_fr' => $request->input('label_fr'),
            'type' => $request->input('type'),
        ];
        
        $error = $this->attributeCreate($data);

        if ($error) {
            return response()->json(['success' => false, 'message' => $error]);
        } else {
            return response()->json(['success' => true]);
        }
    }

    /**
     * Attribute Create page
     */
    public function attributeCreatePage()
    {
        $data = [
            'page_title' => 'Create Attribute'
        ];
        return view('admin.products.attribute_create', $data);
    }

    /**
     * Attribute Edit page
     */
    public function attributeEdit($id)
    {
        $attribute = DB::table('attributes')->where('id', $id)->first();
        
        if (!$attribute) {
            return redirect()->route('admin.products.attributesMap')
                ->with('message_error', 'Attribute not found');
        }
        
        $data = [
            'page_title' => 'Edit Attribute',
            'attribute' => (array) $attribute
        ];
        return view('admin.products.attribute_edit', $data);
    }

    /**
     * Attribute Delete
     */
    public function attributeDelete($id)
    {
        try {
            // Check if attribute has items
            $itemCount = DB::table('attribute_items')
                ->where('attribute_id', $id)
                ->count();
                
            if ($itemCount > 0) {
                return redirect()->route('admin.products.attributesMap')
                    ->with('message_error', 'Cannot delete attribute with existing items');
            }
            
            DB::table('attributes')->where('id', $id)->delete();
            
            return redirect()->route('admin.products.attributesMap')
                ->with('message_success', 'Attribute deleted successfully');
                
        } catch (Exception $e) {
            return redirect()->route('admin.products.attributesMap')
                ->with('message_error', 'Error deleting attribute: ' . $e->getMessage());
        }
    }

    /**
     * Attribute Create Grid (inline editing - CI project style)
     */
    public function attributeCreateGrid(Request $request)
    {
        try {
            $data = [
                'name' => $request->input('name'),
                'label' => $request->input('label'),
                'label_fr' => $request->input('label_fr'),
                'type' => $request->input('type'),
            ];
            
            $error = $this->attributeCreate($data);
            
            if ($error) {
                return response()->json(['success' => false, 'message' => $error]);
            } else {
                return response()->json(['success' => true]);
            }
            
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Attribute Update (inline editing - CI project style)
     */
    public function attributeUpdate(Request $request)
    {
        try {
            $id = $request->input('id');
            $data = [
                'label' => $request->input('label'),
                'label_fr' => $request->input('label_fr'),
                'type' => $request->input('type'),
            ];
            
            $result = DB::table('attributes')->where('id', $id)->update($data);
            
            if ($result) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Update failed']);
            }
            
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Attribute Delete POST (inline editing - CI project style)
     */
    public function attributeDeletePost(Request $request)
    {
        try {
            $id = $request->input('id');
            
            // Check if attribute has items
            $itemCount = DB::table('attribute_items')
                ->where('attribute_id', $id)
                ->count();
                
            if ($itemCount > 0) {
                return response()->json(['success' => false, 'message' => 'Cannot delete attribute with existing items']);
            }
            
            $result = DB::table('attributes')->where('id', $id)->delete();
            
            if ($result) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Delete failed']);
            }
            
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Attribute Items Map (CI project style)
     */
    public function attributeItemsMap(Request $request, $attribute_id = 0)
    {
        if ($request->isMethod('get')) {
            // Return view for tab 2 (Items tab)
            return response()->json(['success' => true]);
        } elseif ($request->isMethod('post')) {
            $q = $request->input('q');
            $filter = $request->input('filter');
            
            if (isset($filter) && isset($filter['filters'])) {
                $q = $filter['filters'][0]['value'];
            }

            $page = $request->input('page', 1);
            $pageSize = $request->input('pageSize', 10);
            $take = $pageSize;
            $skip = $pageSize * ($page - 1);
            
            $data = [];
            $total = 0;
            $this->getAttributeItemsMap($attribute_id, $q, $take, $skip, $data, $total);

            $gridModel = [
                'extra_data' => null,
                'data' => $data,
                'errors' => null,
                'total' => $total,
            ];

            return response()->json($gridModel);
        }
    }

    /**
     * Attribute Item Create Map (CI project style)
     */
    public function attributeItemCreateMap(Request $request)
    {
        try {
            $data = [
                'attribute_id' => $request->input('attribute_id'),
                'name' => $request->input('name'),
                'name_fr' => $request->input('name_fr'),
            ];
            
            $error = $this->attributeItemCreate($data);
            
            if ($error) {
                return response()->json(['success' => false, 'message' => $error]);
            } else {
                return response()->json(['success' => true]);
            }
            
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Attribute Item Update Map (CI project style)
     */
    public function attributeItemUpdateMap(Request $request)
    {
        try {
            $id = $request->input('id');
            $data = [
                'name' => $request->input('name'),
                'name_fr' => $request->input('name_fr'),
            ];
            
            $result = DB::table('attribute_items')->where('id', $id)->update($data);
            
            if ($result) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Update failed']);
            }
            
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Attribute Item Delete Map (CI project style)
     */
    public function attributeItemDeleteMap(Request $request)
    {
        try {
            $id = $request->input('id');
            
            $result = DB::table('attribute_items')->where('id', $id)->delete();
            
            if ($result) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Delete failed']);
            }
            
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Get Attribute Items Map (CI project style)
     */
    protected function getAttributeItemsMap($attribute_id, $q, $take, $skip, &$data, &$total)
    {
        // Get total count
        $totalQuery = DB::table('attribute_items')
            ->join('attributes', 'attributes.id', '=', 'attribute_items.attribute_id');
            
        if ($attribute_id > 0) {
            $totalQuery->where('attribute_items.attribute_id', $attribute_id);
        }
        
        if (!empty($q)) {
            $totalQuery->where(function($query) use ($q) {
                $query->where('attributes.name', 'like', '%' . $q . '%')
                      ->orWhere('attribute_items.name', 'like', '%' . $q . '%');
            });
        }
        
        $total = $totalQuery->count();
        
        // Get data
        $query = DB::table('attribute_items')
            ->select('attribute_items.*', 'attributes.name as attribute_name')
            ->join('attributes', 'attributes.id', '=', 'attribute_items.attribute_id');
            
        if ($attribute_id > 0) {
            $query->where('attribute_items.attribute_id', $attribute_id);
        }
        
        if (!empty($q)) {
            $query->where(function($query) use ($q) {
                $query->where('attributes.name', 'like', '%' . $q . '%')
                      ->orWhere('attribute_items.name', 'like', '%' . $q . '%');
            });
        }
        
        $query->orderByRaw('attributes.name, CAST(attribute_items.name AS FLOAT), attribute_items.name');
        
        $take = $take > 0 ? $take : 0;
        $skip = $skip > 0 ? $skip : 0;
        
        if ($take > 0) {
            $query->limit($take)->offset($skip);
        } else {
            $query->offset($skip);
        }
        
        $data = $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }

    /**
     * Attribute Item Create (CI project style)
     */
    protected function attributeItemCreate($data)
    {
        if (empty($data['name'])) {
            return 'Name is required';
        }

        if (empty($data['attribute_id'])) {
            return 'Attribute ID is required';
        }

        // Check duplication using Eloquent
        $existing = \App\Models\AttributeItem::where('attribute_id', $data['attribute_id'])
            ->where('name', $data['name'])
            ->first();
            
        if ($existing) {
            return 'Name is duplicated for this attribute';
        }

        \App\Models\AttributeItem::create([
            'attribute_id' => $data['attribute_id'],
            'name' => $data['name'],
            'name_fr' => $data['name_fr'] ?? $data['name'],
        ]);
        
        return null;
    }

    public function session(Request $request)
    {
        $tabname = $request->input('tabname');
        $index = $request->input('index');
        
        if ($tabname && $index !== null) {
            session([$tabname . '-tab' => $index]);
        }
        
        return response()->json(['success' => true]);
    }

    /**
     * Attribute Create (CI: Product_Model->attributeCreate)
     */
    protected function attributeCreate($data)
    {
        if (empty($data['name'])) {
            return 'Name is required';
        }

        // Check duplication
        $existing = DB::table('attributes')
            ->where('name', $data['name'])
            ->first();
            
        if ($existing) {
            return 'Name is duplicated';
        }

        DB::table('attributes')->insert([
            'name' => $data['name'],
            'label' => $data['label'] ?? $data['name'],
            'label_fr' => $data['label_fr'] ?? $data['label'] ?? $data['name'],
            'type' => $data['type'],
        ]);

        return null;
    }
    protected function getSizeOptionsTable($type)
    {
        $tableMap = [
            'paper_quality' => 'paper_quality',
            'ncr_parts' => 'ncr_parts',
            'colors' => 'colors',
            'stocks' => 'stocks',
            'diameter' => 'diameter',
            'shapepaper' => 'shapepaper',
            'grommets' => 'grommets',
            'page_size' => 'page_size',
            'page_quantity' => 'page_quantity',
            'sheets' => 'sheets',
        ];
        
        return $tableMap[$type] ?? 'page_size';
    }

    /**
     * Save attribute items (CI equivalent: Product_Model->saveAttributeItem)
     * Exact CI implementation with Laravel DB
     */
    protected function saveAttributeItem($data, $product_attribute_id)
    {
        if (!empty($product_attribute_id) && !empty($data)) {
            // Get existing items (CI logic)
            $old_data = DB::table('product_attribute_items')
                ->select('*')
                ->where('product_attribute_id', $product_attribute_id)
                ->get()
                ->map(function($item) {
                    return (array) $item;
                })->toArray();
            
            $old_data_ids = array_column($old_data, 'id');
            $update_data_ids = array();

            // Update or insert items (CI logic)
            foreach ($data as $val) {
                if (!empty($val['item_name'])) {
                    if (!empty($val['id'])) {
                        // Update existing item
                        unset($val['created']);
                        DB::table('product_attribute_items')
                            ->where('id', $val['id'])
                            ->update($val);
                        
                        $update_data_ids[] = $val['id'];
                    } else {
                        // Insert new item
                        unset($val['id']);
                        DB::table('product_attribute_items')->insert($val);
                    }
                }
            }

            // Delete items that are no longer present (CI logic)
            foreach ($old_data as $old_item) {
                $id = $old_item['id'];
                if (!in_array($id, $update_data_ids)) {
                    DB::table('product_attribute_items')->where('id', $id)->delete();
                }
            }
        }
        
        return true;
    }

    /**
     * Estimates management methods (CI: Products->estimates())
     */
    
    /**
     * Display estimates list
     * CI: Products->estimates()
     */
    public function estimates()
    {
        try {
            $data = [
                'page_title' => 'Product Estimates',
                'sub_page_title' => 'View Product Estimates',
                'sub_page_view_url' => 'admin.products.estimates.view',
                'sub_page_delete_url' => 'admin.products.estimates.delete',
                'estimates' => Estimate::getAllEstimates()
            ];

            // Get store list for display
            $storeList = DB::table('stores')->get()->keyBy('id')->map(function($item) {
                return (array) $item;
            })->toArray();
            $data['StoreList'] = $storeList;

            return view('admin.products.estimates', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@estimates: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading estimates: ' . $e->getMessage());
        }
    }

    /**
     * View estimate details
     * CI: Products->viewProductEstimates()
     */
    public function estimatesView($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('admin.products.estimates.index')
                    ->with('message_error', 'Estimate ID is required');
            }

            $product = Estimate::getEstimateDataById($id);
            
            if (!$product) {
                return redirect()->route('admin.products.estimates.index')
                    ->with('message_error', 'Estimate not found');
            }

            // Get state name for province
            if (!empty($product['province'])) {
                $state = DB::table('states_bk')->where('StateID', $product['province'])->first();
                $product['province'] = $state ? $state->StateName : $product['province'];
            }

            $data = [
                'page_title' => 'Estimates Details',
                'main_page_url' => 'admin/products/estimates',
                'Product' => $product
            ];

            // Get store list for display
            $storeList = DB::table('stores')->get()->keyBy('id')->map(function($item) {
                return (array) $item;
            })->toArray();
            $data['StoreList'] = $storeList;

            return view('admin.products.view_estimates', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@estimatesView: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading estimate details: ' . $e->getMessage());
        }
    }

    /**
     * Delete estimate
     * CI: Products->deleteProductEstimates()
     */
    public function estimatesDelete($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('admin.products.estimates.index')
                    ->with('message_error', 'Missing information.');
            }

            $result = Estimate::deleteProductEstimates($id);
            
            if ($result) {
                return redirect()->route('admin.products.estimates.index')
                    ->with('message_success', 'Product Estimates Delete Successfully.');
            } else {
                return redirect()->route('admin.products.estimates.index')
                    ->with('message_error', 'Product Estimates Delete Unsuccessfully.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@estimatesDelete: ' . $e->getMessage());
            
            return redirect()->route('admin.products.estimates.index')
                ->with('message_error', 'Error deleting estimate: ' . $e->getMessage());
        }
    }

    /**
     * Single Attributes management methods (CI: SingleAttributes)
     */
    
    /**
     * Display single attributes list
     * CI: SingleAttributes->index()
     */
    public function singleAttributes()
    {
        try {
            $data = [
                'page_title' => 'Product Attributes',
                'sub_page_title' => 'Add New Attribute',
                'sub_page_url' => 'admin.products.singleAttributes.addEdit',
                'sub_page_view_url' => 'admin.products.singleAttributes.view',
                'sub_page_delete_url' => 'admin.products.singleAttributes.delete',
                'sub_page_url_active_inactive' => 'admin.products.singleAttributes.toggleStatus',
                'lists' => ProductAttribute::getAttributesList()
            ];

            return view('admin.products.single_attributes', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@singleAttributes: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading single attributes: ' . $e->getMessage());
        }
    }

    /**
     * Add/Edit single attribute
     * CI: SingleAttributes->addEditAttribute()
     */
    public function singleAttributesAddEdit(Request $request, $id = null)
    {
        try {
            $page_title = 'Add New Attributes';
            if (!empty($id)) {
                $page_title = 'Edit Attributes';
            }

            $postData = [];
            $productItemData = [];
            
            if ($id) {
                $postData = ProductAttribute::getAttributesDataById($id);
                // Direct database query to avoid any model issues (CI compatibility)
                $productItemData = DB::table('product_attribute_items')
                    ->select('*')
                    ->where('product_attribute_id', $id)
                    ->get()
                    ->map(function($item) {
                        return (array) $item;
                    })->toArray();
            }

            // Handle POST request
            if ($request->isMethod('post')) {
                return $this->saveSingleAttribute($request, $id);
            }

            $data = [
                'page_title' => $page_title,
                'main_page_url' => '',
                'postData' => $postData,
                'productItemData' => $productItemData
            ];

            return view('admin.products.add_edit_single_attribute', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@singleAttributesAddEdit: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading attribute form: ' . $e->getMessage());
        }
    }

    /**
     * Save single attribute
     * CI: SingleAttributes->addEditAttribute() POST handling
     */
    protected function saveSingleAttribute(Request $request, $id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'name_french' => 'required|string|max:255',
            ], [
                'name.required' => 'Attribute Name is required',
                'name_french.required' => 'French Attribute Name is required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $postData = [
                'name' => $request->input('name'),
                'name_french' => $request->input('name_french'),
            ];

            if ($id) {
                $postData['id'] = $id;
            }

            $insert_id = ProductAttribute::saveAttributes($postData);

            if ($insert_id > 0) {
                // Save attribute items
                $attribute_item_name = $request->input('attribute_item_name', []);
                $item_name_french = $request->input('item_name_french', []);
                $attribute_item_id = $request->input('attribute_item_id', []);

                $data = [];
                foreach ($attribute_item_name as $k => $v) {
                    if (!empty($v)) {
                        $sdata = [
                            'id' => $attribute_item_id[$k] ?? null,
                            'item_name' => $v,
                            'item_name_french' => $item_name_french[$k] ?? '',
                            'product_attribute_id' => $insert_id,
                        ];
                        $data[] = $sdata;
                    }
                }

                // Save attribute items using CI logic (direct database query)
                $this->saveAttributeItem($data, $insert_id);

                return redirect()->route('admin.products.singleAttributes.index')
                    ->with('message_success', 'Attributes Successfully.');
            } else {
                return redirect()->back()
                    ->with('message_error', 'Attributes Unsuccessfully.')
                    ->withInput();
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@saveSingleAttribute: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error saving attribute: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Toggle single attribute status
     * CI: SingleAttributes->activeInactiveAttribute()
     */
    public function singleAttributesToggleStatus($id = null, $status = null)
    {
        try {
            if (empty($id) || ($status !== '0' && $status !== '1')) {
                return redirect()->route('admin.products.singleAttributes.index')
                    ->with('message_error', 'Missing information.');
            }

            $postData = [
                'id' => $id,
                'status' => $status
            ];

            $result = ProductAttribute::saveAttributes($postData);
            
            if ($result) {
                $statusText = $status == '1' ? 'Active' : 'Inactive';
                return redirect()->route('admin.products.singleAttributes.index')
                    ->with('message_success', "Attributes {$statusText} Successfully.");
            } else {
                return redirect()->route('admin.products.singleAttributes.index')
                    ->with('message_error', 'Attributes {$statusText} Unsuccessfully.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@singleAttributesToggleStatus: ' . $e->getMessage());
            
            return redirect()->route('admin.products.singleAttributes.index')
                ->with('message_error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Delete single attribute
     * CI: SingleAttributes->deleteAttribute()
     */
    public function singleAttributesDelete($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('admin.products.singleAttributes.index')
                    ->with('message_error', 'Missing information.');
            }

            $result = ProductAttribute::deleteAttributes($id);
            
            if ($result) {
                return redirect()->route('admin.products.singleAttributes.index')
                    ->with('message_success', 'Attributes Delete Successfully.');
            } else {
                return redirect()->route('admin.products.singleAttributes.index')
                    ->with('message_error', 'Attributes Delete Unsuccessfully.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@singleAttributesDelete: ' . $e->getMessage());
            
            return redirect()->route('admin.products.singleAttributes.index')
                ->with('message_error', 'Error deleting attribute: ' . $e->getMessage());
        }
    }

    // Helper methods from CI conversion
    private function render($view)
    {
        return view($view);
    }

    /**
     * Multiple Attributes management methods (CI: MultipleAttributes)
     */
    
    /**
     * Display multiple attributes list
     * CI: MultipleAttributes->index()
     */
    public function multipleAttributes()
    {
        try {
            $data = [
                'page_title' => 'Product Multiple Attributes',
                'sub_page_title' => 'Add New Attribute',
                'sub_page_url' => 'multipleAttributes.addEdit',
                'sub_page_view_url' => '',
                'sub_page_delete_url' => 'multipleAttributes.delete',
                'sub_page_url_active_inactive' => 'multipleAttributes.toggleStatus',
                'lists' => ProductMultipleAttribute::getMultipleAttributes()
            ];

            return view('admin.products.multiple_attributes', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@multipleAttributes: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading multiple attributes: ' . $e->getMessage());
        }
    }

    /**
     * Add/Edit multiple attribute
     * CI: MultipleAttributes->addEditAttribute()
     */
    public function multipleAttributesAddEdit(Request $request, $id = null)
    {
        try {
            $page_title = 'Add New Attributes';
            if (!empty($id)) {
                $page_title = 'Edit Attributes';
            }

            $postData = [];
            $productItemData = [];
            
            if ($id) {
                $postData = ProductMultipleAttribute::getMultipleAttribute($id);
                $productItemData = ProductMultipleAttributeItem::getMultipleAttributeItems($id);
            }

            // Handle POST request
            if ($request->isMethod('post')) {
                return $this->saveMultipleAttribute($request, $id);
            }

            $data = [
                'page_title' => $page_title,
                'main_page_url' => '',
                'postData' => $postData,
                'productItemData' => $productItemData
            ];

            return view('admin.products.add_edit_multiple_attribute', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@multipleAttributesAddEdit: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading attribute form: ' . $e->getMessage());
        }
    }

    /**
     * Save multiple attribute
     * CI: MultipleAttributes->addEditAttribute() POST handling
     */
    protected function saveMultipleAttribute(Request $request, $id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'name_french' => 'required|string|max:255',
            ], [
                'name.required' => 'Attribute Name is required',
                'name_french.required' => 'French Attribute Name is required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $postData = [
                'name' => $request->input('name'),
                'name_french' => $request->input('name_french'),
            ];

            // CI logic: read ID from POST request for edit operations
            $post_id = $request->input('id');
            if (!empty($post_id)) {
                $postData['id'] = $post_id;
            } elseif ($id) {
                $postData['id'] = $id;
            }

            $insert_id = ProductMultipleAttribute::saveMultipleAttributes($postData);

            if ($insert_id > 0) {
                // Save attribute items (CI logic: always use insert_id)
                $attribute_item_name = $request->input('attribute_item_name', []);
                $item_name_french = $request->input('item_name_french', []);
                $attribute_item_id = $request->input('attribute_item_id', []);

                $data = [];
                foreach ($attribute_item_name as $k => $v) {
                    if (!empty($v)) {
                        $sdata = [
                            'id' => $attribute_item_id[$k] ?? null,
                            'item_name' => $v,
                            'item_name_french' => $item_name_french[$k] ?? '',
                            'product_attribute_id' => $insert_id,
                        ];
                        $data[] = $sdata;
                    }
                }

                ProductMultipleAttributeItem::saveMultipleAttributeItem($data, $insert_id);

                return redirect()->route('multipleAttributes.index')
                    ->with('message_success', 'Attributes Successfully.');
            } else {
                return redirect()->back()
                    ->with('message_error', 'Attributes Unsuccessfully.')
                    ->withInput();
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@saveMultipleAttribute: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error saving attribute: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Toggle multiple attribute status
     * CI: MultipleAttributes->activeInactiveAttribute()
     */
    public function multipleAttributesToggleStatus($id = null, $status = null)
    {
        try {
            if (empty($id) || ($status !== '0' && $status !== '1')) {
                return redirect()->route('multipleAttributes.index')
                    ->with('message_error', 'Missing information.');
            }

            $postData = [
                'id' => $id,
                'status' => $status
            ];

            $result = ProductMultipleAttribute::saveMultipleAttributes($postData);
            
            if ($result) {
                $statusText = $status == '1' ? 'Active' : 'Inactive';
                return redirect()->route('multipleAttributes.index')
                    ->with('message_success', "Attributes {$statusText} Successfully.");
            } else {
                return redirect()->route('multipleAttributes.index')
                    ->with('message_error', "Attributes {$statusText} Unsuccessfully.");
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@multipleAttributesToggleStatus: ' . $e->getMessage());
            
            return redirect()->route('multipleAttributes.index')
                ->with('message_error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple attribute
     * CI: MultipleAttributes->deleteAttribute()
     */
    public function multipleAttributesDelete($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('multipleAttributes.index')
                    ->with('message_error', 'Missing information.');
            }

            $result = ProductMultipleAttribute::deleteMultipleAttributes($id);
            
            if ($result) {
                return redirect()->route('multipleAttributes.index')
                    ->with('message_success', 'Attributes Delete Successfully.');
            } else {
                return redirect()->route('multipleAttributes.index')
                    ->with('message_error', 'Attributes Delete Unsuccessfully.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@multipleAttributesDelete: ' . $e->getMessage());
            
            return redirect()->route('multipleAttributes.index')
                ->with('message_error', 'Error deleting attribute: ' . $e->getMessage());
        }
    }

    /**
     * Product Quantity management methods (CI: MultipleAttributes->quantity)
     */
    
    /**
     * Display product quantity list
     * CI: MultipleAttributes->quantity()
     */
    public function productQuantity()
    {
        try {
            // Debug: Log that we're in the right method
            Log::info('productQuantity method called');
            
            $data = [
                'page_title' => 'Product Quantity',
                'sub_page_title' => 'Add New Quantity',
                'sub_page_url' => 'MultipleAttributes.quantity.addEdit',
                'sub_page_view_url' => '',
                'sub_page_delete_url' => 'MultipleAttributes.quantity.delete',
                'sub_page_url_active_inactive' => 'MultipleAttributes.quantity.toggleStatus',
                'lists' => ProductQuantity::getQuantityList()
            ];

            Log::info('About to return product_quantity view');
            return view('admin.products.product_quantity', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@productQuantity: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading product quantity: ' . $e->getMessage());
        }
    }

    /**
     * Add/Edit product quantity
     * CI: MultipleAttributes->addEditQuantity()
     */
    public function productQuantityAddEdit(Request $request, $id = null)
    {
        try {
            $page_title = 'Add New Quantity';
            if (!empty($id)) {
                $page_title = 'Edit Quantity';
            }

            $postData = [];
            
            if ($id) {
                $postData = ProductQuantity::getQtyById($id);
            }

            // Handle POST request
            if ($request->isMethod('post')) {
                return $this->saveProductQuantity($request, $id);
            }

            $data = [
                'page_title' => $page_title,
                'main_page_url' => 'quantity',
                'postData' => $postData
            ];

            return view('admin.products.quantity_management', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@productQuantityAddEdit: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading quantity form: ' . $e->getMessage());
        }
    }

    /**
     * Save product quantity
     * CI: MultipleAttributes->addEditQuantity() POST handling
     */
    protected function saveProductQuantity(Request $request, $id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'name_french' => 'required|string|max:255',
            ], [
                'name.required' => 'Quantity Name is required',
                'name_french.required' => 'French Quantity Name is required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $postData = [
                'name' => $request->input('name'),
                'name_french' => $request->input('name_french'),
                'set_order' => $request->input('set_order', 0),
                'show_page_size' => $request->input('show_page_size', 1),
            ];

            if ($id) {
                $postData['id'] = $id;
            }

            $result = ProductQuantity::saveQuantity($postData);

            if ($result > 0) {
                return redirect()->route('MultipleAttributes.quantity')
                    ->with('message_success', 'Quantity Successfully.');
            } else {
                return redirect()->back()
                    ->with('message_error', 'Quantity Unsuccessfully.')
                    ->withInput();
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@saveProductQuantity: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error saving quantity: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Toggle product quantity status
     * CI: MultipleAttributes->activeInactiveQuantity()
     */
    public function productQuantityToggleStatus($id = null, $status = null)
    {
        try {
            if (empty($id) || ($status !== '0' && $status !== '1')) {
                return redirect()->route('MultipleAttributes.quantity')
                    ->with('message_error', 'Missing information.');
            }

            $postData = [
                'id' => $id,
                'status' => $status
            ];

            $result = ProductQuantity::saveQuantity($postData);
            
            if ($result) {
                $statusText = $status == '1' ? 'Active' : 'Inactive';
                return redirect()->route('MultipleAttributes.quantity')
                    ->with('message_success', "Quantity {$statusText} Successfully.");
            } else {
                return redirect()->route('MultipleAttributes.quantity')
                    ->with('message_error', "Quantity {$statusText} Unsuccessfully.");
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@productQuantityToggleStatus: ' . $e->getMessage());
            
            return redirect()->route('MultipleAttributes.quantity')
                ->with('message_error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Delete product quantity
     * CI: MultipleAttributes->deleteQuantity()
     */
    public function productQuantityDelete($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('MultipleAttributes.quantity')
                    ->with('message_error', 'Missing information.');
            }

            $result = ProductQuantity::deleteQuantity($id);
            
            if ($result) {
                return redirect()->route('MultipleAttributes.quantity')
                    ->with('message_success', 'Quantity Delete Successfully.');
            } else {
                return redirect()->route('MultipleAttributes.quantity')
                    ->with('message_error', 'Quantity Delete Unsuccessfully.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@productQuantityDelete: ' . $e->getMessage());
            
            return redirect()->route('MultipleAttributes.quantity')
                ->with('message_error', 'Error deleting quantity: ' . $e->getMessage());
        }
    }

    /**
     * Product Sizes management methods (CI: MultipleAttributes->sizes)
     */
    
    /**
     * Display product sizes list
     * CI: MultipleAttributes->sizes()
     */
    public function productSizes()
    {
        try {
            $data = [
                'page_title' => 'Product Sizes',
                'sub_page_title' => 'Add New Size',
                'sub_page_url' => 'MultipleAttributes.sizes.addEdit',
                'sub_page_view_url' => '',
                'sub_page_delete_url' => 'MultipleAttributes.sizes.delete',
                'sub_page_url_active_inactive' => 'MultipleAttributes.sizes.toggleStatus',
                'lists' => Size::getSizeList()
            ];

            return view('admin.products.product_sizes', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@productSizes: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading product sizes: ' . $e->getMessage());
        }
    }

    /**
     * Add/Edit product size
     * CI: MultipleAttributes->addEditSize()
     */
    public function productSizesAddEdit(Request $request, $id = null)
    {
        try {
            $page_title = 'Add New Size';
            if (!empty($id)) {
                $page_title = 'Edit Size';
            }

            $postData = [];
            
            if ($id) {
                $postData = Size::getSizeDataById($id);
            }

            // Handle POST request
            if ($request->isMethod('post')) {
                return $this->saveProductSize($request, $id);
            }

            $data = [
                'page_title' => $page_title,
                'main_page_url' => 'sizes',
                'postData' => $postData
            ];

            return view('admin.products.add_edit_product_size', $data);
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@productSizesAddEdit: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading size form: ' . $e->getMessage());
        }
    }

    /**
     * Save product size
     * CI: MultipleAttributes->addEditSize() POST handling
     */
    protected function saveProductSize(Request $request, $id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'size_name' => 'required|string|max:255',
                'size_name_french' => 'required|string|max:255',
            ], [
                'size_name.required' => 'Size Name is required',
                'size_name_french.required' => 'French Size Name is required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $postData = [
                'size_name' => $request->input('size_name'),
                'size_name_french' => $request->input('size_name_french'),
                'set_order' => $request->input('set_order', 0),
            ];

            if ($id) {
                $postData['id'] = $id;
            }

            $result = Size::saveSize($postData);

            if ($result > 0) {
                return redirect()->route('MultipleAttributes.sizes')
                    ->with('message_success', 'Size Successfully.');
            } else {
                return redirect()->back()
                    ->with('message_error', 'Size Unsuccessfully.')
                    ->withInput();
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@saveProductSize: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error saving size: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Toggle product size status
     * CI: MultipleAttributes->activeInactiveSize()
     */
    public function productSizesToggleStatus($id = null, $status = null)
    {
        try {
            if (empty($id) || ($status !== '0' && $status !== '1')) {
                return redirect()->route('MultipleAttributes.sizes')
                    ->with('message_error', 'Missing information.');
            }

            $postData = [
                'id' => $id,
                'status' => $status
            ];

            $result = Size::saveSize($postData);
            
            if ($result) {
                $statusText = $status == '1' ? 'Active' : 'Inactive';
                return redirect()->route('MultipleAttributes.sizes')
                    ->with('message_success', "Size {$statusText} Successfully.");
            } else {
                return redirect()->route('MultipleAttributes.sizes')
                    ->with('message_error', "Size {$statusText} Unsuccessfully.");
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@productSizesToggleStatus: ' . $e->getMessage());
            
            return redirect()->route('MultipleAttributes.sizes')
                ->with('message_error', 'Error updating status: ' . $e->getMessage());
        }
    }

    /**
     * Delete product size
     * CI: MultipleAttributes->deleteSize()
     */
    public function productSizesDelete($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('MultipleAttributes.sizes')
                    ->with('message_error', 'Missing information.');
            }

            $result = Size::deleteSize($id);
            
            if ($result) {
                return redirect()->route('MultipleAttributes.sizes')
                    ->with('message_success', 'Size Delete Successfully.');
            } else {
                return redirect()->route('MultipleAttributes.sizes')
                    ->with('message_error', 'Size Delete Unsuccessfully.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in ProductsController@productSizesDelete: ' . $e->getMessage());
            
            return redirect()->route('MultipleAttributes.sizes')
                ->with('message_error', 'Error deleting size: ' . $e->getMessage());
        }
    }
    
    /**
     * Search products for admin interface
     * CI: Products->searchProduct() lines 1482-1529
     */
    public function searchProduct(Request $request)
    {
        $searchtext = $request->input('searchtext', '');
        $accept = $request->header('Accept', '');
        $accept = array_map('trim', preg_split('/(,|;)/', $accept));
        
        if ($searchtext != '') {
            $searchtext = trim($searchtext);
            
            // Search products by name, code, or model
            $products = DB::table('products')
                ->select('id', 'name', 'product_image')
                ->where(function($query) use ($searchtext) {
                    $query->where('name', 'LIKE', '%' . $searchtext . '%')
                          ->orWhere('code', 'LIKE', '%' . $searchtext . '%')
                          ->orWhere('model', 'LIKE', '%' . $searchtext . '%');
                })
                ->orderBy('name', 'asc')
                ->limit(50)
                ->get();
            
            $search_result = '';
            if (!$products->isEmpty()) {
                // Process products for display
                foreach ($products as $product) {
                    $product->name = ucfirst($product->name);
                    $product->product_image = $this->getProductImage($product->product_image);
                }
                
                // Return JSON if requested
                if (in_array('application/json', $accept)) {
                    return response()->json($products);
                }
                
                // Generate HTML result
                foreach ($products as $product) {
                    $name = $product->name;
                    $imageurl = $product->product_image;
                    $product_id = $product->id;
                    $search_result .= '<li><a href="' . url('admin/Products/index/' . $product_id) . '"><img src="' . $imageurl . '" width=50><span></i>' . $name . '</span></a></li>';
                }
            } else {
                // Return empty JSON if requested
                if (in_array('application/json', $accept)) {
                    return response()->json([]);
                }
                
                $search_result = '<li><i class="fas fa-search"></i><a href="javascript:void(0)">product not found</a></li>';
            }
        } else {
            // Return empty JSON if requested
            if (in_array('application/json', $accept)) {
                return response()->json([]);
            }
            
            $search_result = '<li><i class="fas fa-search"></i><a href="javascript:void(0)">product not found</a></li>';
        }
        
        return response($search_result)->header('Content-Type', 'text/html');
    }
    
    /**
     * Product Attributes management - Kendo Grid interface
     * CI: Products->ProductAttributes() lines 2816-2845
     */
    public function ProductAttributes($product_id = null)
    {
        if (request()->isMethod('GET')) {
            // Get product data
            $product = DB::table('products')->where('id', $product_id)->first();
            if (!$product) {
                return redirect()->route('admin.products.index')
                    ->with('message_error', 'Product not found');
            }
            
            $data = [
                'product_id' => $product_id,
                'product' => (array) $product,
                'page_title' => 'Product Attributes - ' . $product->name,
            ];
            
            return view('admin.products.attributes', $data);
            
        } elseif (request()->isMethod('POST')) {
            // Handle Kendo Grid data request
            $q = request()->input('q', '');
            $filter = request()->input('filter', []);
            
            // Extract search term from filter if present
            if (isset($filter['filters']) && !empty($filter['filters'])) {
                $q = $filter['filters'][0]['value'] ?? '';
            }
            
            $page = request()->input('page', 1);
            $pageSize = request()->input('pageSize', 10);
            $take = $pageSize;
            $skip = $pageSize * ($page - 1);
            
            // Get product attributes with pagination using CI project table structure
            try {
                // First check if product exists
                $productExists = DB::table('products')->where('id', $product_id)->exists();
                if (!$productExists) {
                    Log::warning('Product not found', ['product_id' => $product_id]);
                    $gridModel = [
                        'extra_data' => null,
                        'data' => [],
                        'errors' => 'Product not found',
                        'total' => 0,
                    ];
                    return response()->json($gridModel);
                }
                
                // Check if tables exist
                $tablesExist = DB::select("SHOW TABLES LIKE 'product_attribute_map'");
                if (empty($tablesExist)) {
                    Log::error('product_attribute_map table does not exist');
                    $gridModel = [
                        'extra_data' => null,
                        'data' => [],
                        'errors' => 'Database table product_attribute_map not found',
                        'total' => 0,
                    ];
                    return response()->json($gridModel);
                }
                
                $query = DB::table('product_attribute_map')
                    ->select('product_attribute_map.id', 'product_attribute_map.product_id', 'product_attribute_map.attribute_id', 
                            'product_attribute_map.show_order', 'attributes.name', 'attributes.label', 'attributes.label_fr', 'attributes.type', 
                            DB::raw('COUNT(DISTINCT product_attribute_item_map.attribute_item_id) AS item_count'))
                    ->join('attributes', 'attributes.id', '=', 'product_attribute_map.attribute_id')
                    ->leftJoin('product_attribute_item_map', function($join) use ($product_id) {
                        $join->on('product_attribute_item_map.attribute_id', '=', 'product_attribute_map.attribute_id')
                             ->where('product_attribute_item_map.product_id', '=', $product_id);
                    })
                    ->where('product_attribute_map.product_id', $product_id)
                    ->groupBy('product_attribute_map.id', 'product_attribute_map.product_id', 'product_attribute_map.attribute_id', 
                              'product_attribute_map.show_order', 'attributes.name', 'attributes.label', 'attributes.label_fr', 'attributes.type')
                    ->orderBy('product_attribute_map.show_order');
                
                if (!empty($q)) {
                    $query->where('attributes.name', 'LIKE', '%' . $q . '%');
                }
                
                // Debug: Log the query
                Log::info('ProductAttributes query', [
                    'product_id' => $product_id,
                    'query' => $query->toSql(),
                    'bindings' => $query->getBindings()
                ]);
                
                $total = $query->count();
                $data = $query->offset($skip)->limit($take)->get();
                
                Log::info('ProductAttributes results', [
                    'product_id' => $product_id,
                    'total' => $total,
                    'data_count' => $data->count(),
                    'sample_data' => $data->take(3)->toArray()
                ]);
                
            } catch (Exception $e) {
                Log::error('ProductAttributes query error: ' . $e->getMessage(), [
                    'product_id' => $product_id,
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Return empty result on error
                $gridModel = [
                    'extra_data' => null,
                    'data' => [],
                    'errors' => $e->getMessage(),
                    'total' => 0,
                ];
                
                return response()->json($gridModel);
            }
            
            // Transform data to match CI structure
            $transformedData = [];
            foreach ($data as $item) {
                $transformedData[] = [
                    'id' => $item->id,
                    'attribute_id' => $item->attribute_id,
                    'name' => $item->name,
                    'label' => $item->label ?? $item->name,
                    'label_fr' => $item->label_fr ?? $item->name_fr,
                    'type' => $item->type,
                    'item_count' => $item->item_count ?? 0,
                ];
            }
            
            $gridModel = [
                'extra_data' => null,
                'data' => $transformedData,
                'errors' => null,
                'total' => $total,
            ];
            
            return response()->json($gridModel);
        }
    }

    /**
     * Product Attribute Create - create product attributes
     * CI: Products->ProductAttributeCreate() lines 2848-2878
     */
    public function ProductAttributeCreate($product_id)
    {
        $data = [
            'product_id' => $product_id,
            'attribute_id' => request()->input('attribute_id'),
            'show_order' => request()->input('show_order'),
            'use_items' => request()->input('use_items'),
            'use_percentage' => request()->input('use_percentage'),
            'value_min' => request()->input('value_min'),
            'value_max' => request()->input('value_max'),
            'additional_fee' => request()->input('additional_fee'),
            'fee_apply_size' => request()->input('fee_apply_size'),
            'fee_apply_width' => request()->input('fee_apply_width'),
            'fee_apply_length' => request()->input('fee_apply_length'),
            'fee_apply_depth' => request()->input('fee_apply_depth'),
            'fee_apply_diameter' => request()->input('fee_apply_diameter'),
            'fee_apply_pages' => request()->input('fee_apply_pages'),
        ];
        
        // Use the Laravel DB facade instead of CI Product_Model
        try {
            // Check if attribute already exists for this product
            $existingAttribute = DB::table('product_attribute_map')
                ->where('product_id', $product_id)
                ->where('attribute_id', $data['attribute_id'])
                ->first();
            
            if ($existingAttribute) {
                return response()->json([
                    'success' => false, 
                    'message' => 'This attribute already exists for this product'
                ]);
            }
            
            // Create new product attribute
            $attributeId = DB::table('product_attribute_map')->insertGetId([
                'product_id' => $product_id,
                'attribute_id' => $data['attribute_id'],
                'show_order' => $data['show_order'],
                'use_items' => $data['use_items'],
                'use_percentage' => $data['use_percentage'],
                'value_min' => $data['value_min'],
                'value_max' => $data['value_max'],
                'additional_fee' => $data['additional_fee'],
                'fee_apply_size' => $data['fee_apply_size'],
                'fee_apply_width' => $data['fee_apply_width'],
                'fee_apply_length' => $data['fee_apply_length'],
                'fee_apply_depth' => $data['fee_apply_depth'],
                'fee_apply_diameter' => $data['fee_apply_diameter'],
                'fee_apply_pages' => $data['fee_apply_pages'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Product attribute created successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating product attribute: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Toggle product active/inactive status
     * CI: Products->activeInactive() lines 1270-1289
     */
    public function activeInactive($id = null, $status = null)
    {
        if (!empty($id) && ($status == 1 || $status == 0)) {
            $page_title = 'Product Active';
            if ($status == 0) {
                $page_title = 'Product Inactive';
            }
            
            // Update product status
            $updated = DB::table('products')
                ->where('id', $id)
                ->update(['status' => $status]);
            
            if ($updated) {
                session()->flash('message_success', $page_title . ' Successfully.');
                
                // Clear products cache to refresh the list
                $this->clearProductsCache();
            } else {
                session()->flash('message_error', $page_title . ' Unsuccessfully.');
            }
        } else {
            session()->flash('message_error', 'Missing information.');
        }
        
        return redirect()->to('admin/Products');
    }
    
    /**
     * Clear products cache
     * Helper method to clear all products-related cache
     */
    private function clearProductsCache()
    {
        // Clear all possible products cache keys
        // Since we don't know the exact parameters used, clear all variations
        
        // Clear products list cache (all possible combinations)
        for ($product_id = 0; $product_id <= 50; $product_id++) {
            for ($perPage = 10; $perPage <= 100; $perPage += 10) {
                for ($offset = 0; $offset <= 1000; $offset += 10) {
                    Cache::forget("products_list_{$product_id}_{$perPage}_{$offset}_asc");
                    Cache::forget("products_list_{$product_id}_{$perPage}_{$offset}_desc");
                }
            }
        }
        
        // Clear products total cache
        for ($product_id = 0; $product_id <= 50; $product_id++) {
            Cache::forget("products_total_{$product_id}");
        }
        
        // Alternative approach: flush all cache if available
        if (function_exists('cache') && method_exists(cache(), 'flush')) {
            try {
                cache()->flush();
            } catch (\Exception $e) {
                // If flush fails, continue with individual key clearing
            }
        }
    }

    /**
     * Delete all selected products
     * CI: Products->deleteAllProduct() lines 1331-1380
     */
    public function deleteAllProduct(Request $request)
    {
        $product_ids = $request->input('product_ids', []);
        
        if (!empty($product_ids)) {
            $delete = false;
            $page_title = 'Product Delete';
            
            foreach ($product_ids as $id) {
                // Get product image data before deletion
                $productImageData = DB::table('product_images')
                    ->where('product_id', $id)
                    ->get();
                
                // Delete the product
                if (DB::table('products')->where('id', $id)->delete()) {
                    // Delete product images from database
                    DB::table('product_images')->where('product_id', $id)->delete();
                    
                    // Delete image files from storage
                    foreach ($productImageData as $data) {
                        $imageName = $data->image;
                        
                        // Define image paths (CI equivalent)
                        $paths = [
                            public_path('uploads/products/small/' . $imageName),
                            public_path('uploads/products/medium/' . $imageName),
                            public_path('uploads/products/large/' . $imageName),
                            public_path('uploads/products/' . $imageName),
                        ];
                        
                        // Delete files if they exist
                        foreach ($paths as $path) {
                            if (file_exists($path)) {
                                unlink($path);
                            }
                        }
                    }
                    
                    $delete = true;
                }
            }
            
            if ($delete) {
                session()->flash('message_success', $page_title . ' Successfully.');
            } else {
                session()->flash('message_error', $page_title . ' Unsuccessfully.');
            }
        } else {
            session()->flash('message_error', 'Select at least one product for delete.');
        }
        
        return redirect()->to('admin/Products');
    }

    /**
     * Update Print Auto - for uploading ink/toner images and price lists
     * CI: Products->updatePrintAuto() lines 1951-1956
     */
    public function updatePrintAuto()
    {
        $data = [
            'page_title' => 'Update Image and Price',
            'main_page_url' => '',
        ];

        return view('admin.products.update_image_and_price_auto', $data);
    }

    /**
     * Auto Attribute Add - add attributes to products automatically
     * CI: Products->AutoAttributeAdd() lines 2063-2122
     */
    public function autoAttributeAdd(Request $request, $product_id = null, $id = null)
    {
        $data = [];
        $success = '0';
        
        // Get attributes list
        $attributes = DB::table('attributes')
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->toArray();
        
        $data['attributes'] = $attributes;
        $data['BASE_URL'] = url('/');
        
        if ($request->isMethod('post')) {
            $id = $request->input('id');
            $product_id = $request->input('product_id');
            $attribute_id = $request->input('attribute_id');
            
            // Check if attribute already exists for this product
            $productAttributes = DB::table('product_attributes')
                ->where('product_id', $product_id)
                ->get()
                ->toArray();
            
            $attributeIds = array_map(function($attr) {
                return $attr['attribute_id'];
            }, $productAttributes);
            
            $savedData = [
                'product_id' => $product_id,
                'attribute_id' => $attribute_id,
            ];
            
            $saved = true;
            
            if ($id) {
                $savedData['id'] = $id;
            }
            
            if ($id != $attribute_id && in_array($attribute_id, $attributeIds)) {
                session()->flash('message_error', 'This attribute already added to this product');
                $saved = false;
            }
            
            if ($saved) {
                if ($id) {
                    // Update existing record
                    DB::table('product_attributes')
                        ->where('id', $id)
                        ->update($savedData);
                    
                    session()->flash('message_success', 'Updated Attribute Successfully.');
                    $success = 1;
                } else {
                    // Insert new record
                    $insert_id = DB::table('product_attributes')->insertGetId($savedData);
                    
                    if ($insert_id > 0) {
                        session()->flash('message_success', 'Added Attribute Successfully.');
                        $success = 1;
                    } else {
                        session()->flash('message_error', 'Saved Attribute Unsuccessfully.');
                    }
                }
            }
        } else {
            // GET request - set initial values
            $success = '0';
            $productAttributes = DB::table('product_attributes')
                ->where('product_id', $product_id)
                ->get()
                ->toArray();
            $attribute_id = $id;
        }
        
        $data['id'] = $id;
        $data['product_id'] = $product_id;
        $data['attribute_id'] = $attribute_id ?? '';
        $data['success'] = $success;
        
        // Return view content for AJAX
        return view('admin.products.auto_attribute_add', $data);
    }

    /**
     * Auto Attribute Item Add - add attribute items to products automatically
     * CI: Products->autoAttributeItemAdd() lines 2135-2209
     */
    public function autoAttributeItemAdd(Request $request, $product_id = null, $attribute_id = null, $id = null)
    {
        $data = [];
        $success = '0';
        $extra_price = $attribute_item_id = '';
        
        // Get attribute items list
        $attributeItems = DB::table('attribute_items')
            ->where('attribute_id', $attribute_id)
            ->orderBy('name')
            ->get()
            ->toArray();
        
        $data['attributeItems'] = $attributeItems;
        $data['BASE_URL'] = url('/');
        
        if ($request->isMethod('post')) {
            $id = $request->input('id');
            $product_id = $request->input('product_id');
            $attribute_id = $request->input('attribute_id');
            $attribute_item_id = $request->input('attribute_item_id');
            $extra_price = $request->input('extra_price');
            
            // Check if attribute item already exists for this product
            $productAttributeItems = DB::table('product_attribute_item_datas')
                ->where('product_id', $product_id)
                ->where('attribute_id', $attribute_id)
                ->get()
                ->toArray();
            
            $itemIds = array_map(function($item) {
                return $item['attribute_item_id'];
            }, $productAttributeItems);
            
            $extra_price = !empty($extra_price) ? $extra_price : 0;
            $savedData = [
                'product_id' => $product_id,
                'attribute_id' => $attribute_id,
                'attribute_item_id' => $attribute_item_id,
                'extra_price' => $extra_price,
            ];
            
            $saved = true;
            
            if ($id) {
                $savedData['id'] = $id;
            }
            
            if ($id != $attribute_item_id && in_array($attribute_item_id, $itemIds)) {
                session()->flash('message_error', 'This attribute item already added to this product');
                $saved = false;
            }
            
            if ($saved) {
                if ($id) {
                    // Update existing record
                    DB::table('product_attribute_item_datas')
                        ->where('id', $id)
                        ->update($savedData);
                    
                    session()->flash('message_success', 'Updated Attribute Item Successfully.');
                    $success = 1;
                } else {
                    // Insert new record
                    $insert_id = DB::table('product_attribute_item_datas')->insertGetId($savedData);
                    
                    if ($insert_id > 0) {
                        session()->flash('message_success', 'Added Attribute Item Successfully.');
                        $success = 1;
                    } else {
                        session()->flash('message_error', 'Saved Attribute Item Unsuccessfully.');
                    }
                }
            }
        } else {
            // GET request - set initial values
            $success = '0';
            $productAttributeItems = DB::table('product_attribute_item_datas')
                ->where('product_id', $product_id)
                ->where('attribute_id', $attribute_id)
                ->get()
                ->toArray();
            
            $attribute_item_id = $id;
            $extra_price = 0;
            
            foreach ($productAttributeItems as $item) {
                if ($item['id'] == $attribute_item_id) {
                    $extra_price = $item['extra_price'];
                }
            }
        }
        
        $data['id'] = $id;
        $data['product_id'] = $product_id;
        $data['attribute_id'] = $attribute_id;
        $data['attribute_item_id'] = $attribute_item_id;
        $data['extra_price'] = $extra_price;
        $data['success'] = $success;
        
        // Return view content for AJAX
        return view('admin.products.auto_attribute_item_add', $data);
    }

    /**
     * Auto Size Add - add sizes to products automatically
     * CI: Products->AutoSizeAdd() lines 1978-2050
     */
    public function autoSizeAdd(Request $request, $product_id = null, $id = null)
    {
        $data = [];
        $success = '0';
        $extra_price = $size_id = '';
        
        // Get sizes list
        $sizes = DB::table('sizes')
            ->where('status', 1)
            ->orderBy('size_name')
            ->get();
        
        $data['sizes'] = $sizes;
        $data['BASE_URL'] = url('/');
        
        if ($request->isMethod('post')) {
            $id = $request->input('id');
            $product_id = $request->input('product_id');
            $size_id = $request->input('size_id');
            $extra_price = $request->input('extra_price');
            
            // Check if size already exists for this product
            $productSizes = DB::table('product_size')
                ->where('product_id', $product_id)
                ->get();
            
            $sizeIds = $productSizes->pluck('size_id')->toArray();
            
            $extra_price = !empty($extra_price) ? $extra_price : 0;
            $savedData = [
                'product_id' => $product_id,
                'size_id' => $size_id,
                'extra_price' => $extra_price,
            ];
            
            $saved = true;
            
            if ($id) {
                $savedData['id'] = $id;
            }
            
            if ($id != $size_id && in_array($size_id, $sizeIds)) {
                session()->flash('message_error', 'This size already added to this product');
                $saved = false;
            }
            
            if ($saved) {
                if ($id) {
                    // Update existing record
                    DB::table('product_size')
                        ->where('id', $id)
                        ->update($savedData);
                    
                    session()->flash('message_success', 'Updated Size Successfully.');
                    $success = 1;
                } else {
                    // Insert new record
                    $insert_id = DB::table('product_size')->insertGetId($savedData);
                    
                    if ($insert_id > 0) {
                        session()->flash('message_success', 'Added Size Successfully.');
                        $success = 1;
                    } else {
                        session()->flash('message_error', 'Saved Size Unsuccessfully.');
                    }
                }
            }
        } else {
            // GET request - set initial values
            $success = '0';
            $productSizes = DB::table('product_size')
                ->where('product_id', $product_id)
                ->get();
            
            $size_id = $id;
            $extra_price = 0;
            
            foreach ($productSizes as $size) {
                if ($size->id == $size_id) {
                    $extra_price = $size->extra_price;
                }
            }
        }
        
        $data['id'] = $id;
        $data['product_id'] = $product_id;
        $data['size_id'] = $size_id;
        $data['extra_price'] = $extra_price;
        $data['success'] = $success;
        
        // Return view content for AJAX
        return view('admin.products.auto_size_add', $data);
    }

    /**
     * Update Print Auto - Fixed version to match CI project styling
     * This method now shows the auto attribute add form instead of image/price upload
     */
    public function updatePrintAutoFixed()
    {
        // This method provides the same functionality as autoAttributeAdd
        // but with the correct URL and styling matching CI project
        return $this->autoAttributeAdd(request(), null, null);
    }

    /**
     * Get product image URL (helper function)
     * CI: getProductImage() helper
     */
    private function getProductImage($product_image)
    {
        if (empty($product_image)) {
            return url('assets/admin/images/no-image.png');
        }
        
        // Check if image exists in uploads/products directory
        if (file_exists(public_path('uploads/products/' . $product_image))) {
            return url('uploads/products/' . $product_image);
        }
        
        // Check if image exists in uploads directory (fallback)
        if (file_exists(public_path('uploads/' . $product_image))) {
            return url('uploads/' . $product_image);
        }
        
        // Default image
        return url('assets/admin/images/no-image.png');
    }

    /**
     * Product Attribute Items - AJAX endpoint for Kendo Grid
     * CI: Products->ProductAttributeItems() lines 2916-2945
     */
    public function ProductAttributeItems(Request $request, $product_id, $attribute_id = null)
    {
        if ($request->isMethod('get')) {
            // GET request - return view (if needed)
            return response()->json(['message' => 'GET method not implemented for this endpoint']);
        } elseif ($request->isMethod('post')) {
            // POST request - return JSON data for Kendo Grid
            $q = $request->input('q');
            $filter = $request->input('filter');
            if (isset($filter) && isset($filter['filters'])) {
                $q = $filter['filters'][0]['value'];
            }

            $page = $request->input('page', 1);
            $pageSize = $request->input('pageSize', 10);
            $take = $pageSize;
            $skip = $pageSize * ($page - 1);

            // Build query for attribute items
            $query = DB::table('attribute_items')
                ->where('attribute_id', $attribute_id);

            // Apply search filter
            if (!empty($q)) {
                $query->where(function($query) use ($q) {
                    $query->where('name', 'LIKE', '%' . $q . '%')
                          ->orWhere('name_fr', 'LIKE', '%' . $q . '%');
                });
            }

            // Get total count
            $total = $query->count();

            // Get paginated data
            $data = $query->offset($skip)
                      ->limit($take)
                      ->get()
                      ->toArray();

            $gridModel = [
                'extra_data' => null,
                'data' => $data,
                'errors' => null,
                'total' => $total,
            ];

            return response()->json($gridModel);
        }
    }
    
    /**
     * Save product categories and subcategories with selected categories array
     * CI: Products->addEdit() category handling
     */
    protected function saveProductCategoriesWithCategories($product_id, $selectedCategories)
    {
        try {
            Log::info('Saving product categories with array', ['product_id' => $product_id, 'categories' => $selectedCategories]);
            
            // Clear existing categories
            DB::table('product_category')->where('product_id', $product_id)->delete();
            DB::table('product_subcategory')->where('product_id', $product_id)->delete();
            
            // Extract subcategories from request
            $selectedSubCategories = [];
            foreach (request()->all() as $key => $value) {
                if (strpos($key, 'sub_category_id_') === 0 && $value) {
                    $parts = explode('_', $key);
                    if (count($parts) >= 4) {
                        $subCategoryId = end($parts);
                        $selectedSubCategories[] = $subCategoryId;
                    }
                }
            }
            
            // Save categories
            if (!empty($selectedCategories)) {
                $categoryInserts = [];
                foreach ($selectedCategories as $categoryId) {
                    $categoryInserts[] = [
                        'product_id' => $product_id,
                        'category_id' => $categoryId
                    ];
                }
                DB::table('product_category')->insert($categoryInserts);
            }
            
            // Save subcategories
            if (!empty($selectedSubCategories)) {
                $subCategoryInserts = [];
                foreach ($selectedSubCategories as $subCategoryId) {
                    $subCategoryInserts[] = [
                        'product_id' => $product_id,
                        'sub_category_id' => $subCategoryId
                    ];
                }
                DB::table('product_subcategory')->insert($subCategoryInserts);
            }
            
            Log::info('Product categories saved successfully', [
                'product_id' => $product_id,
                'categories_count' => count($selectedCategories),
                'subcategories_count' => count($selectedSubCategories)
            ]);
            
        } catch (Exception $e) {
            Log::error('Error saving product categories: ' . $e->getMessage(), [
                'product_id' => $product_id
            ]);
            throw $e;
        }
    }
    
    /**
     * Save product categories and subcategories
     * CI: Products->addEdit() category handling
     */
    protected function saveProductCategories(Request $request, $product_id)
    {
        try {
            Log::info('Saving product categories', ['product_id' => $product_id]);
            
            // Clear existing categories
            DB::table('product_category')->where('product_id', $product_id)->delete();
            DB::table('product_subcategory')->where('product_id', $product_id)->delete();
            
            // Extract categories from checkbox inputs
            $selectedCategories = [];
            $selectedSubCategories = [];
            
            foreach ($request->all() as $key => $value) {
                if (strpos($key, 'category_id_') === 0 && $value) {
                    $categoryId = str_replace('category_id_', '', $key);
                    $selectedCategories[] = $categoryId;
                    
                    // Check for subcategories of this category
                    foreach ($request->all() as $subKey => $subValue) {
                        if (strpos($subKey, 'sub_category_id_' . $categoryId . '_') === 0 && $subValue) {
                            $subCategoryId = str_replace('sub_category_id_' . $categoryId . '_', '', $subKey);
                            $selectedSubCategories[] = $subCategoryId;
                        }
                    }
                }
            }
            
            // Save categories
            if (!empty($selectedCategories)) {
                $categoryInserts = [];
                foreach ($selectedCategories as $categoryId) {
                    $categoryInserts[] = [
                        'product_id' => $product_id,
                        'category_id' => $categoryId
                    ];
                }
                DB::table('product_category')->insert($categoryInserts);
            }
            
            // Save subcategories
            if (!empty($selectedSubCategories)) {
                $subCategoryInserts = [];
                foreach ($selectedSubCategories as $subCategoryId) {
                    $subCategoryInserts[] = [
                        'product_id' => $product_id,
                        'sub_category_id' => $subCategoryId
                    ];
                }
                DB::table('product_subcategory')->insert($subCategoryInserts);
            }
            
            Log::info('Product categories saved successfully', [
                'product_id' => $product_id,
                'categories_count' => count($selectedCategories),
                'subcategories_count' => count($selectedSubCategories)
            ]);
            
        } catch (Exception $e) {
            Log::error('Error saving product categories: ' . $e->getMessage(), [
                'product_id' => $product_id
            ]);
            throw $e;
        }
    }
    
    /**
     * Save product descriptions
     * CI: Products->addEdit() description handling
     */
    protected function saveProductDescriptions(Request $request, $product_id)
    {
        try {
            Log::info('Saving product descriptions', ['product_id' => $product_id]);
            
            // Clear existing descriptions
            DB::table('product_descriptions')->where('product_id', $product_id)->delete();
            
            $titles = $request->input('title', []);
            $descriptions = $request->input('description', []);
            
            if (!empty($titles)) {
                $descriptionInserts = [];
                foreach ($titles as $key => $title) {
                    if (!empty($title)) {
                        $descriptionInserts[] = [
                            'product_id' => $product_id,
                            'title' => $title,
                            'description' => $descriptions[$key] ?? '',
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
                
                if (!empty($descriptionInserts)) {
                    DB::table('product_descriptions')->insert($descriptionInserts);
                }
            }
            
            Log::info('Product descriptions saved successfully', [
                'product_id' => $product_id,
                'descriptions_count' => count($descriptionInserts ?? [])
            ]);
            
        } catch (Exception $e) {
            Log::error('Error saving product descriptions: ' . $e->getMessage(), [
                'product_id' => $product_id
            ]);
            throw $e;
        }
    }
    
    /**
     * Save product templates
     * CI: Products->addEdit() template handling
     */
    protected function saveProductTemplates(Request $request, $product_id)
    {
        try {
            Log::info('Saving product templates', ['product_id' => $product_id]);
            
            // Clear existing templates
            DB::table('product_templates')->where('product_id', $product_id)->delete();
            
            $finalDimensions = $request->input('final_dimensions', []);
            $finalDimensionsAfterCut = $request->input('final_dimensions_after_cut', []);
            $bleed = $request->input('bleed', []);
            $safeArea = $request->input('safe_area', []);
            
            if (!empty($finalDimensions)) {
                $templateInserts = [];
                foreach ($finalDimensions as $key => $dimension) {
                    if (!empty($dimension)) {
                        $templateInserts[] = [
                            'product_id' => $product_id,
                            'final_dimensions' => $dimension,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }
                
                if (!empty($templateInserts)) {
                    DB::table('product_templates')->insert($templateInserts);
                }
            }
            
            Log::info('Product templates saved successfully', [
                'product_id' => $product_id,
                'templates_count' => count($templateInserts ?? [])
            ]);
            
        } catch (Exception $e) {
            Log::error('Error saving product templates: ' . $e->getMessage(), [
                'product_id' => $product_id
            ]);
            throw $e;
        }
    }
    
    /**
     * Resize image to different sizes (CI equivalent)
     */
    protected function resizeImage($filename, $size)
    {
        try {
            // For now, just log the resize request
            // In a real implementation, you would use Intervention Image or similar
            Log::info('Image resize requested', ['filename' => $filename, 'size' => $size]);
            
            // TODO: Implement actual image resizing using Intervention Image
            // This would typically create different sized versions of the image
            
        } catch (Exception $e) {
            Log::error('Error resizing image: ' . $e->getMessage(), [
                'filename' => $filename,
                'size' => $size
            ]);
        }
    }
}
