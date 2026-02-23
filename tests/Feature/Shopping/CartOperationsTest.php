<?php

namespace Tests\Feature\Shopping;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Cart Operations Feature Tests
 * Tests exact behavior from CI ShoppingCarts controller
 */
class CartOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test products
        DB::table('products')->insert([
            [
                'id' => 1,
                'menu_id' => 1,
                'category_id' => 1,
                'name' => 'Test Product 1',
                'slug' => 'test-product-1',
                'price' => 29.99,
                'status' => 1,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'menu_id' => 1,
                'category_id' => 1,
                'name' => 'Test Product 2',
                'slug' => 'test-product-2',
                'price' => 49.99,
                'status' => 1,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ],
        ]);
        
        // Add items to cart
        Session::put('cart', [
            'item1' => [
                'rowid' => 'item1',
                'id' => 1,
                'name' => 'Test Product 1',
                'qty' => 2,
                'price' => 29.99,
                'subtotal' => 59.98,
                'options' => [],
            ],
            'item2' => [
                'rowid' => 'item2',
                'id' => 2,
                'name' => 'Test Product 2',
                'qty' => 1,
                'price' => 49.99,
                'subtotal' => 49.99,
                'options' => [],
            ],
        ]);
    }

    /**
     * Test shopping cart page loads
     * CI: ShoppingCarts->index()
     */
    public function test_shopping_cart_page_loads()
    {
        $response = $this->get('/ShoppingCarts');
        
        $response->assertStatus(200);
        $response->assertViewIs('shopping-carts.index');
        $response->assertViewHas('cart');
    }

    /**
     * Test cart displays correct items
     * CI: ShoppingCarts->index() - cart items
     */
    public function test_cart_displays_correct_items()
    {
        $response = $this->get('/ShoppingCarts');
        
        $cart = $response->viewData('cart');
        
        $this->assertCount(2, $cart);
        $this->assertEquals('Test Product 1', $cart['item1']['name']);
        $this->assertEquals('Test Product 2', $cart['item2']['name']);
    }

    /**
     * Test update cart item quantity
     * CI: ShoppingCarts->updateCart()
     */
    public function test_update_cart_item_quantity()
    {
        $response = $this->post('/ShoppingCarts/updateCart', [
            'rowid' => 'item1',
            'quantity' => 5,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Cart updated',
        ]);

        // Verify quantity updated
        $cart = Session::get('cart');
        $this->assertEquals(5, $cart['item1']['qty']);
        $this->assertEquals(5 * 29.99, $cart['item1']['subtotal']);
    }

    /**
     * Test update cart with invalid quantity
     * CI: ShoppingCarts->updateCart() - validation
     */
    public function test_update_cart_with_invalid_quantity()
    {
        $response = $this->post('/ShoppingCarts/updateCart', [
            'rowid' => 'item1',
            'quantity' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Invalid quantity',
        ]);

        // Verify quantity not changed
        $cart = Session::get('cart');
        $this->assertEquals(2, $cart['item1']['qty']);
    }

    /**
     * Test remove item from cart
     * CI: ShoppingCarts->removeCart($rowid)
     */
    public function test_remove_item_from_cart()
    {
        $response = $this->get('/ShoppingCarts/removeCart/item1');

        $response->assertRedirect('/ShoppingCarts');
        $response->assertSessionHas('message_success', 'Item removed from cart');

        // Verify item removed
        $cart = Session::get('cart');
        $this->assertArrayNotHasKey('item1', $cart);
        $this->assertCount(1, $cart);
    }

    /**
     * Test remove non-existent item
     * CI: ShoppingCarts->removeCart($rowid) - validation
     */
    public function test_remove_nonexistent_item()
    {
        $response = $this->get('/ShoppingCarts/removeCart/invalid_rowid');

        $response->assertRedirect('/ShoppingCarts');
        $response->assertSessionHas('error', 'Item not found');

        // Verify cart unchanged
        $cart = Session::get('cart');
        $this->assertCount(2, $cart);
    }

    /**
     * Test cart calculates total correctly
     * CI: ShoppingCarts->index() - cart total
     */
    public function test_cart_calculates_total_correctly()
    {
        $response = $this->get('/ShoppingCarts');
        
        $cartTotal = $response->viewData('cart_total');
        
        $expectedTotal = 59.98 + 49.99;
        $this->assertEquals($expectedTotal, $cartTotal);
    }

    /**
     * Test apply coupon code
     * CI: ShoppingCarts->applyCoupon()
     */
    public function test_apply_valid_coupon()
    {
        // Create coupon
        DB::table('coupons')->insert([
            'code' => 'TEST10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'status' => 1,
            'valid_from' => date('Y-m-d', strtotime('-1 day')),
            'valid_to' => date('Y-m-d', strtotime('+1 day')),
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/ShoppingCarts/applyCoupon', [
            'coupon_code' => 'TEST10',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Coupon applied successfully',
        ]);

        // Verify coupon stored in session
        $this->assertTrue(Session::has('coupon_code'));
        $this->assertEquals('TEST10', Session::get('coupon_code'));
    }

    /**
     * Test apply invalid coupon
     * CI: ShoppingCarts->applyCoupon() - validation
     */
    public function test_apply_invalid_coupon()
    {
        $response = $this->post('/ShoppingCarts/applyCoupon', [
            'coupon_code' => 'INVALID',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Invalid coupon code',
        ]);

        // Verify no coupon stored
        $this->assertFalse(Session::has('coupon_code'));
    }

    /**
     * Test apply expired coupon
     * CI: ShoppingCarts->applyCoupon() - date validation
     */
    public function test_apply_expired_coupon()
    {
        // Create expired coupon
        DB::table('coupons')->insert([
            'code' => 'EXPIRED',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'status' => 1,
            'valid_from' => date('Y-m-d', strtotime('-10 days')),
            'valid_to' => date('Y-m-d', strtotime('-1 day')),
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/ShoppingCarts/applyCoupon', [
            'coupon_code' => 'EXPIRED',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Coupon has expired',
        ]);
    }

    /**
     * Test remove coupon
     * CI: ShoppingCarts->removeCoupon()
     */
    public function test_remove_coupon()
    {
        // Apply coupon first
        Session::put('coupon_code', 'TEST10');
        Session::put('coupon_discount', 10.99);

        $response = $this->get('/ShoppingCarts/removeCoupon');

        $response->assertRedirect('/ShoppingCarts');
        $response->assertSessionHas('message_success', 'Coupon removed');

        // Verify coupon removed from session
        $this->assertFalse(Session::has('coupon_code'));
        $this->assertFalse(Session::has('coupon_discount'));
    }

    /**
     * Test coupon discount calculation - percentage
     * CI: ShoppingCarts->applyCoupon() - percentage discount
     */
    public function test_coupon_percentage_discount_calculation()
    {
        DB::table('coupons')->insert([
            'code' => 'PERCENT20',
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'status' => 1,
            'valid_from' => date('Y-m-d'),
            'valid_to' => date('Y-m-d', strtotime('+1 day')),
            'created' => date('Y-m-d H:i:s'),
        ]);

        $this->post('/ShoppingCarts/applyCoupon', [
            'coupon_code' => 'PERCENT20',
        ]);

        $response = $this->get('/ShoppingCarts');
        
        $discount = $response->viewData('coupon_discount');
        $cartTotal = 59.98 + 49.99; // 109.97
        $expectedDiscount = $cartTotal * 0.20; // 21.994
        
        $this->assertEquals($expectedDiscount, $discount, '', 0.01);
    }

    /**
     * Test coupon discount calculation - fixed amount
     * CI: ShoppingCarts->applyCoupon() - fixed discount
     */
    public function test_coupon_fixed_discount_calculation()
    {
        DB::table('coupons')->insert([
            'code' => 'FIXED15',
            'discount_type' => 'fixed',
            'discount_value' => 15.00,
            'status' => 1,
            'valid_from' => date('Y-m-d'),
            'valid_to' => date('Y-m-d', strtotime('+1 day')),
            'created' => date('Y-m-d H:i:s'),
        ]);

        $this->post('/ShoppingCarts/applyCoupon', [
            'coupon_code' => 'FIXED15',
        ]);

        $response = $this->get('/ShoppingCarts');
        
        $discount = $response->viewData('coupon_discount');
        
        $this->assertEquals(15.00, $discount);
    }

    /**
     * Test empty cart displays message
     * CI: ShoppingCarts->index() - empty cart
     */
    public function test_empty_cart_displays_message()
    {
        // Clear cart
        Session::forget('cart');

        $response = $this->get('/ShoppingCarts');
        
        $response->assertStatus(200);
        $response->assertSee('Your cart is empty');
    }

    /**
     * Test cart item count
     * CI: Cart library total_items()
     */
    public function test_cart_item_count()
    {
        $response = $this->get('/ShoppingCarts');
        
        $itemCount = $response->viewData('cart_item_count');
        
        // 2 items (product 1) + 1 item (product 2) = 3 total items
        $this->assertEquals(3, $itemCount);
    }

    /**
     * Test cart preserves product options
     * CI: Cart library stores options
     */
    public function test_cart_preserves_product_options()
    {
        // Add item with options
        Session::put('cart.item3', [
            'rowid' => 'item3',
            'id' => 1,
            'name' => 'Test Product 1',
            'qty' => 1,
            'price' => 29.99,
            'subtotal' => 29.99,
            'options' => [
                'color' => 'Red',
                'size' => 'Large',
            ],
        ]);

        $response = $this->get('/ShoppingCarts');
        
        $cart = $response->viewData('cart');
        
        $this->assertEquals('Red', $cart['item3']['options']['color']);
        $this->assertEquals('Large', $cart['item3']['options']['size']);
    }

    /**
     * Test update multiple cart items at once
     * CI: ShoppingCarts->updateCart() - batch update
     */
    public function test_update_multiple_cart_items()
    {
        $response = $this->post('/ShoppingCarts/updateCart', [
            'updates' => [
                'item1' => ['quantity' => 3],
                'item2' => ['quantity' => 2],
            ],
        ]);

        $response->assertStatus(200);

        // Verify both items updated
        $cart = Session::get('cart');
        $this->assertEquals(3, $cart['item1']['qty']);
        $this->assertEquals(2, $cart['item2']['qty']);
    }
}
