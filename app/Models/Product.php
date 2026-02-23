<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    
    protected $table = 'products';
    
    const CREATED_AT = 'created';
    const UPDATED_AT = 'updated';
    
    protected $fillable = [
        'name', 'name_french', 'code', 'code_french', 'model', 'model_french',
        'price', 'price_euro', 'price_gbp', 'price_usd', 'discount', 'category_id', 'sub_category_id', 'menu_id',
        'short_description', 'short_description_french', 'full_description', 'full_description_french',
        'product_slug', 'page_title', 'page_title_french', 'meta_description_content', 'meta_description_content_french',
        'meta_keywords_content', 'meta_keywords_content_french', 'is_stock', 'status', 'featured', 'bestseller',
        'today_deal', 'special', 'product_tag', 'use_custom_size', 'product_image',
        'shipping_box_length', 'shipping_box_width', 'shipping_box_height', 'shipping_box_weight',
        'add_length_width', 'min_length', 'max_length', 'min_width', 'max_width', 
        'min_length_min_width_price', 'length_width_unit_price_black', 'length_width_price_color', 
        'length_width_color_show', 'length_width_pages_type', 'length_width_quantity_show', 
        'length_width_min_quantity', 'length_width_max_quantity', 'page_add_length_width', 
        'page_min_length', 'page_max_length', 'page_min_width', 'page_max_width', 
        'page_min_length_min_width_price', 'page_length_width_price_color', 
        'page_length_width_price_black', 'page_length_width_color_show', 'page_length_width_pages_type', 
        'page_length_width_pages_show', 'page_length_width_sheets_type', 'page_length_width_sheets_show', 
        'page_length_width_quantity_type', 'page_length_width_quantity_show', 
        'page_length_width_min_quantity', 'page_length_width_max_quantity', 'depth_add_length_width', 
        'min_depth', 'max_depth', 'depth_min_length', 'depth_max_length', 'depth_min_width', 
        'depth_max_width', 'depth_width_length_price', 'depth_unit_price_black', 'depth_price_color', 
        'depth_color_show', 'depth_width_length_type', 'depth_width_length_quantity_show', 
        'depth_min_quantity', 'depth_max_quantity', 'votre_text', 'recto_verso', 'recto_verso_price', 
        'call', 'phone_number', 'is_today_deal', 'is_today_deal_date', 'is_featured', 'is_bestseller', 
        'is_special', 'total_stock', 'reviews', 'rating', 'total_visited', 'delivery_charge', 
        'is_bestdeal', 'product_type', 'min_order_quantity', 'discount_id', 'free_shipping', 
        'poster_plans', 'banners_frames', 'cards_invites', 'photo_gifts', 'cart_name', 
        'catalog', 'brochure', 'is_printed_product'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'price_euro' => 'decimal:2',
        'price_gbp' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'is_today_deal' => 'boolean',
        'is_today_deal_date' => 'date',
        'status' => 'boolean',
        'menu_id' => 'integer',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_special' => 'boolean',
        'is_stock' => 'boolean',
        'poster_plans' => 'boolean',
        'banners_frames' => 'boolean',
        'cards_invites' => 'boolean',
        'photo_gifts' => 'boolean',
        'cart_name' => 'boolean',
        'catalog' => 'boolean',
        'brochure' => 'boolean',
        'is_printed_product' => 'boolean',
        'total_stock' => 'integer',
        'discount' => 'integer',
        'reviews' => 'integer',
        'rating' => 'integer',
        'total_visited' => 'integer',
        'delivery_charge' => 'decimal:0',
        'is_bestdeal' => 'boolean',
        'product_type' => 'integer',
        'min_order_quantity' => 'integer',
        'discount_id' => 'integer',
        'free_shipping' => 'integer',
        'add_length_width' => 'boolean',
        'min_length' => 'decimal:1',
        'max_length' => 'decimal:1',
        'min_width' => 'decimal:1',
        'max_width' => 'decimal:1',
        'min_length_min_width_price' => 'decimal:4',
        'length_width_min_quantity' => 'integer',
        'length_width_max_quantity' => 'integer',
        'length_width_quantity_show' => 'boolean',
        'length_width_unit_price_black' => 'decimal:4',
        'length_width_price_color' => 'decimal:4',
        'length_width_color_show' => 'boolean',
        'votre_text' => 'boolean',
        'recto_verso' => 'boolean',
        'recto_verso_price' => 'integer',
        'page_add_length_width' => 'boolean',
        'page_min_length' => 'decimal:1',
        'page_max_length' => 'decimal:1',
        'page_min_width' => 'decimal:1',
        'page_max_width' => 'decimal:1',
        'page_min_length_min_width_price' => 'decimal:4',
        'page_length_width_pages_show' => 'boolean',
        'page_length_width_sheets_show' => 'boolean',
        'page_length_width_price_color' => 'decimal:4',
        'page_length_width_price_black' => 'decimal:4',
        'page_length_width_color_show' => 'boolean',
        'page_length_width_min_quantity' => 'integer',
        'page_length_width_max_quantity' => 'integer',
        'page_length_width_quantity_show' => 'integer',
        'call' => 'boolean',
        'depth_add_length_width' => 'boolean',
        'min_depth' => 'decimal:1',
        'max_depth' => 'decimal:1',
        'depth_min_length' => 'decimal:1',
        'depth_min_width' => 'decimal:1',
        'depth_max_width' => 'decimal:1',
        'depth_width_length_price' => 'decimal:4',
        'depth_max_length' => 'decimal:1',
        'depth_min_quantity' => 'integer',
        'depth_max_quantity' => 'integer',
        'depth_price_color' => 'decimal:4',
        'depth_unit_price_black' => 'decimal:4',
        'depth_color_show' => 'boolean',
        'shipping_box_length' => 'decimal:2',
        'shipping_box_width' => 'decimal:2',
        'shipping_box_height' => 'decimal:2',
        'shipping_box_weight' => 'decimal:2',
        'use_custom_size' => 'integer',
    ];

    protected $hidden = [
        'created', 'updated'
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category', 'product_id', 'category_id');
    }

    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'product_subcategory', 'product_id', 'sub_category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class, 'product_id');
    }

    public function templates()
    {
        return $this->hasMany(ProductTemplate::class, 'product_id');
    }

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }

    public function providerProducts()
    {
        return $this->hasMany(ProviderProduct::class, 'product_id');
    }

    // Scopes
    public function scopeActive(Builder $query)
    {
        return $query->where('status', 1);
    }

    public function scopeInStock(Builder $query)
    {
        return $query->where('is_stock', 0);
    }

    public function scopeOutOfStock(Builder $query)
    {
        return $query->where('is_stock', 1);
    }

    public function scopeTodayDeal(Builder $query)
    {
        return $query->where('is_today_deal', 1)
                    ->where('is_today_deal_date', now()->format('Y-m-d'));
    }

    public function scopeSpecial(Builder $query)
    {
        return $query->where('is_special', 1);
    }

    public function scopeBestseller(Builder $query)
    {
        return $query->where('is_bestseller', 1);
    }

    public function scopeFeatured(Builder $query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeByCategory(Builder $query, $categoryId)
    {
        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('category_id', $categoryId);
        });
    }

    public function scopeBySubCategory(Builder $query, $categoryId, $subCategoryId)
    {
        return $query->whereHas('subCategories', function ($q) use ($categoryId, $subCategoryId) {
            $q->where('category_id', $categoryId)
              ->where('sub_category_id', $subCategoryId);
        });
    }

    public function scopeSearch(Builder $query, $searchtext)
    {
        $searchtext = trim($searchtext);
        return $query->where(function ($q) use ($searchtext) {
            $q->where('name', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('code', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('model', 'LIKE', '%' . $searchtext . '%');
        })->where('status', 1);
    }

    public function scopeSearchFrench(Builder $query, $searchtext)
    {
        $searchtext = trim($searchtext);
        return $query->where(function ($q) use ($searchtext) {
            $q->where('name_french', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('code_french', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('model_french', 'LIKE', '%' . $searchtext . '%');
        })->where('status', 1);
    }

    public function scopeSearchAdmin(Builder $query, $searchtext)
    {
        $searchtext = trim($searchtext);
        return $query->where(function ($q) use ($searchtext) {
            $q->where('name', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('code', 'LIKE', '%' . $searchtext . '%')
              ->orWhere('model', 'LIKE', '%' . $searchtext . '%');
        });
    }

    public function scopeWithFilters(Builder $query, $categoryId = null, $subCategoryId = null, 
                                   $printerBrand = null, $printerSeries = null, $printerModels = null)
    {
        if (!empty($categoryId)) {
            $query->byCategory($categoryId);
        }
        
        if (!empty($subCategoryId)) {
            $query->bySubCategory($categoryId, $subCategoryId);
        }

        if (!empty($printerBrand)) {
            $query->where('name', 'LIKE', '%' . $printerBrand . '%');
        }

        if (!empty($printerSeries)) {
            $query->where('code', 'LIKE', '%' . $printerSeries . '%');
        }

        if (!empty($printerModels)) {
            $query->where('model', 'LIKE', '%' . $printerModels . '%');
        }

        return $query;
    }

    // Converted Methods from CI Model

    /**
     * Get product list with relationships - converted from getProductList()
     */
    public function scopeGetProductList(Builder $query, $id = null, $productId = null, 
                                      $limit = null, $start = null, $order = 'desc')
    {
        $query->select('products.*',
               'categories.name as category_name', 
               'categories.name_french as category_name_french',
               'sub_categories.name as sub_category_name', 
               'sub_categories.name_french as sub_category_name_french',
               'provider_products.provider_product_id')
              ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
              ->leftJoin('sub_categories', 'sub_categories.id', '=', 'products.sub_category_id')
              ->leftJoin('provider_products', 'provider_products.product_id', '=', 'products.id')
              ->when($id, function ($q, $id) {
                  return $q->where('products.id', $id);
              })
              ->when($productId, function ($q, $productId) {
                  return $q->where('products.id', $productId);
              })
              ->orderBy('products.updated', $order);

        if ($limit) {
            $query->limit($limit);
            if ($start) {
                $query->offset($start);
            }
        }

        return $query;
    }

    /**
     * Get product by slug - converted from getProductslug()
     */
    public function scopeGetProductSlug(Builder $query, $slug = null, $productId = null, 
                                       $limit = null, $start = null, $order = 'desc')
    {
        $query->with(['category', 'subCategory', 'providerProducts'])
              ->when($slug, function ($q, $slug) {
                  return $q->where('product_slug', $slug);
              })
              ->when($productId, function ($q, $productId) {
                  return $q->where('product_slug', $productId);
              })
              ->orderBy('updated', $order);

        if ($limit) {
            $query->limit($limit);
            if ($start) {
                $query->offset($start);
            }
        }

        return $query;
    }

    /**
     * Get product total count - converted from getProductTotal()
     */
    public function scopeGetProductTotal(Builder $query, $productId = null)
    {
        return $query->when($productId, function ($q, $productId) {
                        return $q->where('id', $productId);
                    })
                    ->count();
    }

    /**
     * Get active products for frontend - converted from getActiveProductList()
     */
    public function scopeGetActiveProductList(Builder $query, $categoryId = null, $subCategoryId = null, 
                                            $orderBy = 'name', $type = 'asc', $start = 0, 
                                            $limit = 12, $printerBrand = null, $printerSeries = null, 
                                            $printerModels = null)
    {
        return $query->active()
                    ->withFilters($categoryId, $subCategoryId, $printerBrand, $printerSeries, $printerModels)
                    ->orderBy($orderBy, $type)
                    ->offset($start)
                    ->limit($limit);
    }

    /**
     * Get latest products - converted from getLatestProducts()
     */
    public function scopeGetLatestProducts(Builder $query, $limit = 8)
    {
        return $query->active()->orderBy('created', 'desc')->limit($limit);
    }

    /**
     * Get today's deal products - converted from getTodayDealProducts()
     */
    public function scopeGetTodayDealProducts(Builder $query)
    {
        return $query->todayDeal()->orderBy('name');
    }

    /**
     * Get special products - converted from getSpecialProducts()
     */
    public function scopeGetSpecialProducts(Builder $query)
    {
        return $query->special()->orderBy('name');
    }

    /**
     * Get bestseller products - converted from getBestsellerProducts()
     */
    public function scopeGetBestsellerProducts(Builder $query)
    {
        return $query->bestseller()->orderBy('name');
    }

    /**
     * Get top visited products - converted from getTopVisitedProducts()
     */
    public function scopeGetTopVisitedProducts(Builder $query, $limit = 30)
    {
        return $query->active()->orderBy('total_visited', 'desc')->limit($limit);
    }

    /**
     * Get CSV product list - converted from getCSVProductList()
     */
    public function scopeGetCSVProductList(Builder $query, $categoryId = null, $subCategoryId = null, 
                                          $orderBy = 'name', $type = 'asc', $printerBrand = null)
    {
        return $query->withFilters($categoryId, $subCategoryId, $printerBrand)
                    ->orderBy($orderBy, $type);
    }

    /**
     * Get total active products count - converted from getTotalActiveProduct()
     */
    public function scopeGetTotalActiveProduct(Builder $query, $categoryId = null, $subCategoryId = null, 
                                             $printerBrand = null, $printerSeries = null, 
                                             $printerModels = null)
    {
        return $query->active()
                    ->withFilters($categoryId, $subCategoryId, $printerBrand, $printerSeries, $printerModels)
                    ->count();
    }

    // Static Methods for Complex Operations

    /**
     * Auto batch price update - converted from autoBatchPrice()
     */
    public static function autoBatchPrice($data, $index)
    {
        $colBrand = $colCategory = $colPart = $colModel = $colDescription = $colPrice = $colTitle = '';
        
        if ($index == "nuton") {
            $colTitle = 'B';
            $colPrice = 'E';
        } elseif ($index == "densi") {
            $colBrand = 'A';
            $colPart = 'C';
            $colModel = 'F';
            $colDescription = 'G';
            $colPrice = 'M';
        }

        foreach ($data as $key => $items) {
            if ($key == 1) continue;

            $brand = $code = $model = $description = '';
            
            if ($index == "densi") {
                $brand = $items[$colBrand];
                $code = $items[$colPart];
                $secondcode = self::getSecondCode($code);
                $model = $brand . ': ' . $items[$colModel] . '<p class=hidden>' . $secondcode . '</p>';
                $description = '<h2>BRAND</h2><p>' . $brand . '</p>' . $items[$colDescription];
            }
            
            $currentDate = now();
            $name = ($index == "nuton") ? $items[$colTitle] : $items[$colBrand] . " -" . $items[$colPart];
            $priceStr = explode('$', $items[$colPrice])[1] ?? '';
            $price = isset($priceStr) ? (float)$priceStr : 0;
            
            if ($price) {
                $price = ($price >= 9) ? round($price * 1.6, 2) : 12.99;
            }

            $count = 0;
            if ($index == "nuton") {
                $count = self::where('name', $name)->count();
            } elseif ($index == "densi") {
                $count = self::where('code', $code)->count();
            }

            $field = ($index == "nuton") ? 'name' : 'code';
            $value = ($index == "nuton") ? $name : $code;

            if ($count == 0) {
                self::create([
                    'name' => $name,
                    'price' => $price,
                    'created' => $currentDate,
                    'code' => $code,
                    'model' => $model,
                    'full_description' => $description,
                    'category_id' => 13
                ]);
            } else {
                self::where($field, $value)->update([
                    'price' => $price,
                    'updated' => $currentDate,
                    'model' => $model
                ]);
            }
        }
        
        return true;
    }

    /**
     * Get second code from product code - converted from getSecondCode()
     */
    public static function getSecondCode($code)
    {
        $temp = explode('-', $code);
        $secondcode = '';
        for ($i = 0; $i < count($temp); $i++) {
            $secondcode .= $temp[$i];
        }
        return $secondcode;
    }

    /**
     * Get image name by code - converted from getImageNameByCode()
     */
    public static function getImageNameByCode($imageCode)
    {
        return self::where('code', $imageCode)
                  ->select('id', 'product_image')
                  ->get()
                  ->toArray();
    }

    /**
     * Update image name - converted from updateImageName()
     */
    public static function updateImageName($productId, $imageName)
    {
        return self::where('id', $productId)->update(['product_image' => $imageName]);
    }

    /**
     * Insert new image product - converted from insertNewImage()
     */
    public static function insertNewImage($productName, $imageName)
    {
        self::create([
            'name' => $productName,
            'product_image' => $imageName,
            'code' => $productName
        ]);
        
        return self::where('name', $productName)->select('id')->get()->toArray();
    }

    /**
     * Get product IDs by category - converted from getProductIdsByCategory()
     */
    public static function getProductIdsByCategory($categoryId)
    {
        return DB::table('product_category')
                ->where('category_id', $categoryId)
                ->pluck('product_id')
                ->toArray();
    }

    /**
     * Get product IDs by subcategory - converted from getProductIdsBySubCategory()
     */
    public static function getProductIdsBySubCategory($categoryId, $subCategoryId)
    {
        return DB::table('product_subcategory')
                ->where('category_id', $categoryId)
                ->where('sub_category_id', $subCategoryId)
                ->pluck('product_id')
                ->toArray();
    }

    /**
     * Get product dropdown list - converted from getProductDropDownList()
     */
    public static function getProductDropDownList($menuId = null)
    {
        $query = self::where('status', 1);
        
        if (!empty($menuId)) {
            $query->where('menu_id', $menuId);
        }
        
        return $query->orderBy('name', 'asc')
                    ->pluck('name', 'id')
                    ->map(function ($name) {
                        return ucfirst($name);
                    })
                    ->toArray();
    }

    /**
     * Get brand dropdown list - converted from getBrandDropDownList()
     */
    public static function getBrandDropDownList()
    {
        return DB::table('brands')
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->map(function ($name) {
                    return ucfirst($name);
                })
                ->toArray();
    }

    /**
     * Get personalise data - converted from getPersonailise()
     */
    public static function getPersonailise($id = null)
    {
        $query = DB::table('product_personalise');
        
        if (!empty($id)) {
            $query->where('product_Id', $id);
        }
        
        $result = $query->get();
        
        return !empty($id) ? (array) $result->first() : $result->toArray();
    }

    /**
     * Get total sum and average rating - converted from getTotalSumAvgReting()
     */
    public static function getTotalSumAvgRating($productId = null)
    {
        $result = DB::table('rating')
                   ->where('product_id', $productId)
                   ->selectRaw('COUNT(id) as total, SUM(rate) as sum, AVG(rate) as avg')
                   ->first();
        
        return (array) $result;
    }

    /**
     * Get total rating count - converted from getToatalReting()
     */
    public static function getTotalRating($productId = null, $rate = null)
    {
        $query = DB::table('rating')->where('product_id', $productId);
        
        if (!empty($rate)) {
            $query->where('rate', $rate);
        }
        
        return $query->count();
    }

    /**
     * Get ratings for product - converted from getRatings()
     */
    public static function getRatings($productId)
    {
        return DB::table('rating')
                ->where('product_id', $productId)
                ->orderBy('id', 'desc')
                ->get()
                ->toArray();
    }

    /**
     * Check rating by user and product - converted from CheckRatingByUserIdAndProductId()
     */
    public static function checkRatingByUserIdAndProductId($userId, $productId)
    {
        return DB::table('rating')
                ->where('user_id', $userId)
                ->where('product_id', $productId)
                ->count();
    }

    /**
     * Get count products - converted from getCountProducts()
     */
    public static function getCountProducts($condition = [])
    {
        $query = self::query();
        
        if (!empty($condition)) {
            $query->where($condition);
        }
        
        return $query->count();
    }

    /**
     * Get products by tag name - converted from getProductByTagName()
     */
    public static function getProductByTagName($tagName = null)
    {
        return self::select('products.*', 'categories.name as category_name')
                   ->join('categories', 'categories.id', '=', 'products.category_id')
                   ->where('products.product_tag', 'LIKE', '%' . $tagName . '%')
                   ->where('products.status', 1)
                   ->get()
                   ->toArray();
    }

    /**
     * Get products by tag ID - converted from getProductByTagId()
     */
    public static function getProductByTagId($id = null, $limit = 4)
    {
        return self::where('product_tag', 'LIKE', '%,' . $id . ',%')
                   ->orWhere('product_tag', 'LIKE', '%,' . $id)
                   ->orWhere('product_tag', 'LIKE', $id . ',%')
                   ->where('status', 1)
                   ->limit($limit)
                   ->get()
                   ->toArray();
    }

    // Accessors and Mutators
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getNameFrenchAttribute($value)
    {
        return ucfirst($value);
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    public function getFormattedPriceEuroAttribute()
    {
        return number_format($this->price_euro, 2);
    }

    public function getFormattedPriceGbpAttribute()
    {
        return number_format($this->price_gbp, 2);
    }

    public function getFormattedPriceUsdAttribute()
    {
        return number_format($this->price_usd, 2);
    }

    public function getStockStatusAttribute()
    {
        return $this->is_stock ? 'Out of Stock' : 'In Stock';
    }

    public function getImageUrlAttribute()
    {
        return getProductImage($this->product_image);
    }

    // Custom Methods
    public function createSlug($name, $table, $field)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (DB::table($table)->where($field, $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function getMultipleCategories()
    {
        return $this->categories()
                    ->pluck('category_id')
                    ->flip()
                    ->toArray();
    }

    public static function getMultipleCategoriesAndSubCategories()
    {
        $categories = DB::table('categories')
                        ->select('id', 'name')
                        ->where('status', 1)
                        ->orderBy('name', 'asc')
                        ->get();
        
        $result = [];
        foreach ($categories as $key => $category) {
            $subCategories = DB::table('sub_categories')
                            ->select('id', 'name')
                            ->where('status', 1)
                            ->where('category_id', $category->id)
                            ->orderBy('name', 'asc')
                            ->get();
            
            $result[$key] = [
                'id' => $category->id,
                'name' => $category->name,
                'sub_categories' => $subCategories->toArray()
            ];
        }
        
        return $result;
    }

    public function getProductMultipalCategoriesAndSubCategories($productId)
    {
        $categories = DB::table('product_category')
                       ->where('product_id', $productId)
                       ->pluck('category_id')
                       ->toArray();

        $subCategories = DB::table('product_subcategory')
                          ->where('product_id', $productId)
                          ->pluck('sub_category_id')
                          ->toArray();

        return [
            'categories' => $categories,
            'sub_categories' => $subCategories
        ];
    }

    public function getProductDataById($id)
    {
        return self::find($id)?->toArray() ?? [];
    }

    public function deleteProduct($id)
    {
        $product = self::find($id);
        return $product ? $product->delete() : false;
    }

    public function saveProduct($data)
    {
        $id = $data['id'] ?? null;
        
        if (!empty($id)) {
            $product = self::find($id);
            if ($product) {
                $product->update($data);
                return $id;
            }
            return 0;
        } else {
            unset($data['id']);
            $product = self::create($data);
            return $product->id;
        }
    }

    /**
     * Get quantity dropdown list - converted from CI ProductQuantitySizeAttributeDropDown
     */
    public static function getQuantityListDropDown()
    {
        return DB::table('quantity')
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
    }

    /**
     * Get store dropdown list - converted from CI equivalent
     */
    public static function getStoreDropDownList()
    {
        return DB::table('stores')
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
    }
}
