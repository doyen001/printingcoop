<?php

namespace Tests\Feature\Shopping;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

/**
 * Product Browsing Feature Tests
 * Tests exact behavior from CI Products controller
 */
class ProductBrowsingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test menu
        DB::table('menus')->insert([
            'id' => 1,
            'name' => 'Test Menu',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
        ]);
        
        // Create test category
        DB::table('categories')->insert([
            'id' => 1,
            'menu_id' => 1,
            'name' => 'Test Category',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
        ]);
        
        // Create test subcategory
        DB::table('sub_categories')->insert([
            'id' => 1,
            'menu_id' => 1,
            'category_id' => 1,
            'name' => 'Test Subcategory',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
        ]);
        
        // Create test products
        DB::table('products')->insert([
            [
                'id' => 1,
                'menu_id' => 1,
                'category_id' => 1,
                'sub_category_id' => 1,
                'name' => 'Test Product 1',
                'slug' => 'test-product-1',
                'price' => 29.99,
                'description' => 'Test product description',
                'product_image' => 'test1.jpg',
                'status' => 1,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'menu_id' => 1,
                'category_id' => 1,
                'sub_category_id' => 1,
                'name' => 'Test Product 2',
                'slug' => 'test-product-2',
                'price' => 49.99,
                'description' => 'Another test product',
                'product_image' => 'test2.jpg',
                'status' => 1,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Test product listing page loads
     * CI: Products->index()
     */
    public function test_product_listing_page_loads()
    {
        $response = $this->get('/Products');
        
        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');
    }

    /**
     * Test product listing with menu filter
     * CI: Products->index($menu_id)
     */
    public function test_product_listing_with_menu_filter()
    {
        $response = $this->get('/Products/index/1');
        
        $response->assertStatus(200);
        $response->assertViewHas('products');
        
        // Verify only products from menu 1 are returned
        $products = $response->viewData('products');
        foreach ($products as $product) {
            $this->assertEquals(1, $product['menu_id']);
        }
    }

    /**
     * Test product listing with category filter
     * CI: Products->index($menu_id, $category_id)
     */
    public function test_product_listing_with_category_filter()
    {
        $response = $this->get('/Products/index/1/1');
        
        $response->assertStatus(200);
        $response->assertViewHas('products');
        
        // Verify only products from category 1 are returned
        $products = $response->viewData('products');
        foreach ($products as $product) {
            $this->assertEquals(1, $product['category_id']);
        }
    }

    /**
     * Test product listing with subcategory filter
     * CI: Products->index($menu_id, $category_id, $sub_category_id)
     */
    public function test_product_listing_with_subcategory_filter()
    {
        $response = $this->get('/Products/index/1/1/1');
        
        $response->assertStatus(200);
        $response->assertViewHas('products');
        
        // Verify only products from subcategory 1 are returned
        $products = $response->viewData('products');
        foreach ($products as $product) {
            $this->assertEquals(1, $product['sub_category_id']);
        }
    }

    /**
     * Test product detail page loads
     * CI: Products->view($id)
     */
    public function test_product_detail_page_loads()
    {
        $response = $this->get('/Products/view/1');
        
        $response->assertStatus(200);
        $response->assertViewIs('products.view');
        $response->assertViewHas('product');
    }

    /**
     * Test product detail shows correct data
     * CI: Products->view($id) - product data
     */
    public function test_product_detail_shows_correct_data()
    {
        $response = $this->get('/Products/view/1');
        
        $product = $response->viewData('product');
        
        $this->assertEquals('Test Product 1', $product['name']);
        $this->assertEquals(29.99, $product['price']);
        $this->assertEquals('Test product description', $product['description']);
    }

    /**
     * Test product detail with non-existent product
     * CI: Products->view($id) - 404 handling
     */
    public function test_product_detail_with_nonexistent_product()
    {
        $response = $this->get('/Products/view/999');
        
        $response->assertStatus(404);
    }

    /**
     * Test inactive products are not shown
     * CI: Products->index() - status = 1 filter
     */
    public function test_inactive_products_not_shown()
    {
        // Create inactive product
        DB::table('products')->insert([
            'id' => 3,
            'menu_id' => 1,
            'category_id' => 1,
            'sub_category_id' => 1,
            'name' => 'Inactive Product',
            'slug' => 'inactive-product',
            'price' => 19.99,
            'status' => 0,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
        
        $response = $this->get('/Products');
        
        $products = $response->viewData('products');
        
        // Verify inactive product is not in list
        foreach ($products as $product) {
            $this->assertNotEquals('Inactive Product', $product['name']);
        }
    }

    /**
     * Test product images are loaded
     * CI: Products->view($id) - product images
     */
    public function test_product_images_loaded()
    {
        // Create product images
        DB::table('product_images')->insert([
            [
                'product_id' => 1,
                'image' => 'image1.jpg',
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'product_id' => 1,
                'image' => 'image2.jpg',
                'created' => date('Y-m-d H:i:s'),
            ],
        ]);
        
        $response = $this->get('/Products/view/1');
        
        $response->assertViewHas('product_images');
        $images = $response->viewData('product_images');
        
        $this->assertCount(2, $images);
    }

    /**
     * Test product attributes are loaded
     * CI: Products->view($id) - product attributes
     */
    public function test_product_attributes_loaded()
    {
        // Create product attributes
        DB::table('product_attributes')->insert([
            'product_id' => 1,
            'attribute_id' => 1,
            'show_order' => 1,
            'created' => date('Y-m-d H:i:s'),
        ]);
        
        $response = $this->get('/Products/view/1');
        
        $response->assertViewHas('product_attributes');
    }

    /**
     * Test product quantities are loaded
     * CI: Products->view($id) - product quantities
     */
    public function test_product_quantities_loaded()
    {
        // Create product quantities
        DB::table('product_quantities')->insert([
            [
                'product_id' => 1,
                'qty' => 100,
                'price' => 25.99,
                'created' => date('Y-m-d H:i:s'),
            ],
            [
                'product_id' => 1,
                'qty' => 500,
                'price' => 20.99,
                'created' => date('Y-m-d H:i:s'),
            ],
        ]);
        
        $response = $this->get('/Products/view/1');
        
        $response->assertViewHas('product_quantities');
        $quantities = $response->viewData('product_quantities');
        
        $this->assertCount(2, $quantities);
    }

    /**
     * Test product search functionality
     * CI: Products->index() - search parameter
     */
    public function test_product_search()
    {
        $response = $this->get('/Products?search=Product 1');
        
        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        // Verify search results contain "Product 1"
        $found = false;
        foreach ($products as $product) {
            if (strpos($product['name'], 'Product 1') !== false) {
                $found = true;
                break;
            }
        }
        
        $this->assertTrue($found);
    }

    /**
     * Test product sorting
     * CI: Products->index() - sort parameter
     */
    public function test_product_sorting()
    {
        $response = $this->get('/Products?sort=price_asc');
        
        $response->assertStatus(200);
        $products = $response->viewData('products');
        
        // Verify products are sorted by price ascending
        $prices = array_column($products, 'price');
        $sortedPrices = $prices;
        sort($sortedPrices);
        
        $this->assertEquals($sortedPrices, $prices);
    }

    /**
     * Test related products are shown
     * CI: Products->view($id) - related products
     */
    public function test_related_products_shown()
    {
        $response = $this->get('/Products/view/1');
        
        $response->assertViewHas('related_products');
        $relatedProducts = $response->viewData('related_products');
        
        // Verify related products are from same category
        foreach ($relatedProducts as $product) {
            $this->assertEquals(1, $product['category_id']);
            $this->assertNotEquals(1, $product['id']); // Not the same product
        }
    }
}
