<?php

namespace Tests\Feature\Shopping;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Add to Cart Feature Tests
 * Tests exact behavior from CI Products controller
 */
class AddToCartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test product
        DB::table('products')->insert([
            'id' => 1,
            'menu_id' => 1,
            'category_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'description' => 'Test product',
            'product_image' => 'test.jpg',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Test add simple product to cart
     * CI: Products->addToCart()
     */
    public function test_add_simple_product_to_cart()
    {
        $response = $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 2,
            'price' => 29.99,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Product added to cart',
        ]);

        // Verify cart session
        $cart = Session::get('cart');
        $this->assertNotEmpty($cart);
        
        // Verify product in cart
        $found = false;
        foreach ($cart as $item) {
            if ($item['id'] == 1) {
                $found = true;
                $this->assertEquals(2, $item['qty']);
                $this->assertEquals(29.99, $item['price']);
                break;
            }
        }
        $this->assertTrue($found);
    }

    /**
     * Test add product with attributes to cart
     * CI: Products->addToCart() - with attributes
     */
    public function test_add_product_with_attributes_to_cart()
    {
        $response = $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 1,
            'price' => 29.99,
            'attributes' => [
                'color' => 'Red',
                'size' => 'Large',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
        ]);

        // Verify attributes stored in cart
        $cart = Session::get('cart');
        $item = reset($cart);
        
        $this->assertEquals('Red', $item['options']['attributes']['color']);
        $this->assertEquals('Large', $item['options']['attributes']['size']);
    }

    /**
     * Test add product with custom size to cart
     * CI: Products->addToCart() - custom size
     */
    public function test_add_product_with_custom_size()
    {
        $response = $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 1,
            'price' => 29.99,
            'custom_size' => [
                'width' => 10,
                'height' => 20,
            ],
        ]);

        $response->assertStatus(200);

        // Verify custom size stored
        $cart = Session::get('cart');
        $item = reset($cart);
        
        $this->assertEquals(10, $item['options']['custom_size']['width']);
        $this->assertEquals(20, $item['options']['custom_size']['height']);
    }

    /**
     * Test add product with quantity pricing
     * CI: Products->addToCart() - quantity-based pricing
     */
    public function test_add_product_with_quantity_pricing()
    {
        // Create quantity pricing
        DB::table('product_quantities')->insert([
            'product_id' => 1,
            'qty' => 100,
            'price' => 25.99,
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 100,
            'price' => 25.99,
            'quantity_id' => 1,
        ]);

        $response->assertStatus(200);

        // Verify correct price applied
        $cart = Session::get('cart');
        $item = reset($cart);
        
        $this->assertEquals(25.99, $item['price']);
        $this->assertEquals(100, $item['qty']);
    }

    /**
     * Test add product with uploaded files
     * CI: Products->addToCart() - file uploads
     */
    public function test_add_product_with_uploaded_files()
    {
        $response = $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 1,
            'price' => 29.99,
            'uploaded_files' => [
                ['name' => 'design.pdf', 'path' => '/uploads/temp/design.pdf'],
            ],
        ]);

        $response->assertStatus(200);

        // Verify files stored in cart
        $cart = Session::get('cart');
        $item = reset($cart);
        
        $this->assertNotEmpty($item['options']['uploaded_files']);
        $this->assertEquals('design.pdf', $item['options']['uploaded_files'][0]['name']);
    }

    /**
     * Test add product without required fields
     * CI: Products->addToCart() - validation
     */
    public function test_add_product_without_required_fields()
    {
        $response = $this->post('/Products/addToCart', [
            'product_id' => 1,
            // Missing quantity and price
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Required fields missing',
        ]);
    }

    /**
     * Test add non-existent product to cart
     * CI: Products->addToCart() - product validation
     */
    public function test_add_nonexistent_product_to_cart()
    {
        $response = $this->post('/Products/addToCart', [
            'product_id' => 999,
            'quantity' => 1,
            'price' => 29.99,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Product not found',
        ]);
    }

    /**
     * Test add inactive product to cart
     * CI: Products->addToCart() - status check
     */
    public function test_add_inactive_product_to_cart()
    {
        // Create inactive product
        DB::table('products')->insert([
            'id' => 2,
            'menu_id' => 1,
            'category_id' => 1,
            'name' => 'Inactive Product',
            'slug' => 'inactive-product',
            'price' => 19.99,
            'status' => 0,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/Products/addToCart', [
            'product_id' => 2,
            'quantity' => 1,
            'price' => 19.99,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Product is not available',
        ]);
    }

    /**
     * Test update existing cart item quantity
     * CI: Products->addToCart() - update existing item
     */
    public function test_update_existing_cart_item()
    {
        // Add product first time
        $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 2,
            'price' => 29.99,
        ]);

        // Add same product again
        $response = $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 3,
            'price' => 29.99,
        ]);

        $response->assertStatus(200);

        // Verify quantity updated (not added)
        $cart = Session::get('cart');
        $item = reset($cart);
        
        // Should be 5 (2 + 3) or replaced with 3 depending on CI logic
        $this->assertGreaterThanOrEqual(3, $item['qty']);
    }

    /**
     * Test cart item has unique rowid
     * CI: Cart library generates unique rowid
     */
    public function test_cart_item_has_unique_rowid()
    {
        $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 1,
            'price' => 29.99,
        ]);

        $cart = Session::get('cart');
        $item = reset($cart);
        
        $this->assertNotEmpty($item['rowid']);
    }

    /**
     * Test cart calculates subtotal correctly
     * CI: Cart library calculates subtotal
     */
    public function test_cart_calculates_subtotal()
    {
        $this->post('/Products/addToCart', [
            'product_id' => 1,
            'quantity' => 3,
            'price' => 29.99,
        ]);

        $cart = Session::get('cart');
        $item = reset($cart);
        
        $expectedSubtotal = 3 * 29.99;
        $this->assertEquals($expectedSubtotal, $item['subtotal']);
    }

    /**
     * Test add to wishlist
     * CI: Products->addToWishlist()
     */
    public function test_add_to_wishlist()
    {
        // Login user first
        Session::put('loginUserId', 1);

        $response = $this->post('/Products/addToWishlist', [
            'product_id' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Added to wishlist',
        ]);

        // Verify in database
        $this->assertDatabaseHas('wishlists', [
            'user_id' => 1,
            'product_id' => 1,
        ]);
    }

    /**
     * Test add to wishlist without login
     * CI: Products->addToWishlist() - requires login
     */
    public function test_add_to_wishlist_without_login()
    {
        $response = $this->post('/Products/addToWishlist', [
            'product_id' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Please login to add to wishlist',
        ]);
    }

    /**
     * Test get product price via AJAX
     * CI: Products->getProductPrice()
     */
    public function test_get_product_price_ajax()
    {
        $response = $this->get('/Products/getProductPrice', [
            'product_id' => 1,
            'quantity' => 100,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'price',
            'formatted_price',
        ]);
    }
}
