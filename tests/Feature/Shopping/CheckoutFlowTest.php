<?php

namespace Tests\Feature\Shopping;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

/**
 * Checkout Flow Feature Tests
 * Tests exact behavior from CI Checkouts controller
 */
class CheckoutFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Mail::fake();
        
        // Create test user
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobile' => '1234567890',
            'password' => bcrypt('password'),
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
        
        // Login user
        Session::put('loginUserId', 1);
        Session::put('loginUserEmail', 'test@example.com');
        Session::put('loginUserName', 'Test User');
        
        // Create test product
        DB::table('products')->insert([
            'id' => 1,
            'menu_id' => 1,
            'category_id' => 1,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'price' => 29.99,
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
        
        // Add to cart
        Session::put('cart', [
            'item1' => [
                'rowid' => 'item1',
                'id' => 1,
                'name' => 'Test Product',
                'qty' => 2,
                'price' => 29.99,
                'subtotal' => 59.98,
                'options' => [],
            ],
        ]);
        
        // Create addresses
        DB::table('countries')->insert([
            'id' => 1,
            'name' => 'Canada',
            'iso2' => 'CA',
            'status' => 1,
        ]);
        
        DB::table('states')->insert([
            'id' => 1,
            'country_id' => 1,
            'name' => 'Ontario',
            'status' => 1,
        ]);
        
        DB::table('cities')->insert([
            'id' => 1,
            'state_id' => 1,
            'name' => 'Toronto',
            'status' => 1,
        ]);
    }

    /**
     * Test checkout page loads
     * CI: Checkouts->index()
     */
    public function test_checkout_page_loads()
    {
        $response = $this->get('/Checkouts');
        
        $response->assertStatus(200);
        $response->assertViewIs('checkouts.index');
        $response->assertViewHas('cart');
    }

    /**
     * Test checkout requires login
     * CI: Checkouts->index() - login check
     */
    public function test_checkout_requires_login()
    {
        // Logout
        Session::forget('loginUserId');
        
        $response = $this->get('/Checkouts');
        
        $response->assertRedirect('/Logins');
    }

    /**
     * Test checkout with empty cart redirects
     * CI: Checkouts->index() - cart check
     */
    public function test_checkout_with_empty_cart_redirects()
    {
        Session::forget('cart');
        
        $response = $this->get('/Checkouts');
        
        $response->assertRedirect('/ShoppingCarts');
        $response->assertSessionHas('error', 'Your cart is empty');
    }

    /**
     * Test checkout displays order summary
     * CI: Checkouts->index() - order summary
     */
    public function test_checkout_displays_order_summary()
    {
        $response = $this->get('/Checkouts');
        
        $response->assertViewHas('cart_total');
        $response->assertViewHas('tax_amount');
        $response->assertViewHas('shipping_fee');
        $response->assertViewHas('grand_total');
    }

    /**
     * Test place order with valid data
     * CI: Checkouts->placeOrder()
     */
    public function test_place_order_with_valid_data()
    {
        $response = $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'shipping_name' => 'Test User',
            'shipping_address' => '123 Test St',
            'shipping_country' => 1,
            'shipping_state' => 1,
            'shipping_city' => 1,
            'shipping_pin_code' => 'M5V 3A8',
            'payment_method' => 'cod',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Order placed successfully',
        ]);

        // Verify order created in database
        $this->assertDatabaseHas('product_orders', [
            'user_id' => 1,
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test place order generates unique order ID
     * CI: Checkouts->placeOrder() - order ID generation
     */
    public function test_place_order_generates_unique_order_id()
    {
        $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'shipping_name' => 'Test User',
            'shipping_address' => '123 Test St',
            'shipping_country' => 1,
            'shipping_state' => 1,
            'shipping_city' => 1,
            'shipping_pin_code' => 'M5V 3A8',
            'payment_method' => 'cod',
        ]);

        $order = DB::table('product_orders')->where('user_id', 1)->first();
        
        $this->assertNotEmpty($order->order_id);
        $this->assertMatchesRegularExpression('/^ORD-\d+$/', $order->order_id);
    }

    /**
     * Test place order calculates totals correctly
     * CI: Checkouts->placeOrder() - calculation logic
     */
    public function test_place_order_calculates_totals_correctly()
    {
        $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'shipping_name' => 'Test User',
            'shipping_address' => '123 Test St',
            'shipping_country' => 1,
            'shipping_state' => 1,
            'shipping_city' => 1,
            'shipping_pin_code' => 'M5V 3A8',
            'payment_method' => 'cod',
        ]);

        $order = DB::table('product_orders')->where('user_id', 1)->first();
        
        // Verify calculations
        $this->assertEquals(59.98, $order->sub_total_amount);
        $this->assertGreaterThan(0, $order->total_sales_tax);
        $this->assertGreaterThan(0, $order->delivery_charge);
        $this->assertEquals(
            $order->sub_total_amount + $order->total_sales_tax + $order->delivery_charge,
            $order->total_amount
        );
    }

    /**
     * Test place order creates order items
     * CI: Checkouts->placeOrder() - order items
     */
    public function test_place_order_creates_order_items()
    {
        $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'shipping_name' => 'Test User',
            'shipping_address' => '123 Test St',
            'shipping_country' => 1,
            'shipping_state' => 1,
            'shipping_city' => 1,
            'shipping_pin_code' => 'M5V 3A8',
            'payment_method' => 'cod',
        ]);

        $order = DB::table('product_orders')->where('user_id', 1)->first();
        
        // Verify order items created
        $this->assertDatabaseHas('product_order_items', [
            'order_id' => $order->id,
            'product_id' => 1,
            'quantity' => 2,
            'price' => 29.99,
        ]);
    }

    /**
     * Test place order sends confirmation email
     * CI: Checkouts->placeOrder() - email notification
     */
    public function test_place_order_sends_confirmation_email()
    {
        $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'shipping_name' => 'Test User',
            'shipping_address' => '123 Test St',
            'shipping_country' => 1,
            'shipping_state' => 1,
            'shipping_city' => 1,
            'shipping_pin_code' => 'M5V 3A8',
            'payment_method' => 'cod',
        ]);

        // Verify email sent
        Mail::assertSent(function ($mail) {
            return $mail->hasTo('test@example.com') &&
                   $mail->subject === 'Order Confirmation';
        });
    }

    /**
     * Test place order clears cart
     * CI: Checkouts->placeOrder() - cart clearing
     */
    public function test_place_order_clears_cart()
    {
        $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'shipping_name' => 'Test User',
            'shipping_address' => '123 Test St',
            'shipping_country' => 1,
            'shipping_state' => 1,
            'shipping_city' => 1,
            'shipping_pin_code' => 'M5V 3A8',
            'payment_method' => 'cod',
        ]);

        // Verify cart cleared
        $this->assertFalse(Session::has('cart'));
    }

    /**
     * Test place order with coupon applies discount
     * CI: Checkouts->placeOrder() - coupon discount
     */
    public function test_place_order_with_coupon_applies_discount()
    {
        // Apply coupon
        Session::put('coupon_code', 'TEST10');
        Session::put('coupon_discount', 5.99);

        $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'shipping_name' => 'Test User',
            'shipping_address' => '123 Test St',
            'shipping_country' => 1,
            'shipping_state' => 1,
            'shipping_city' => 1,
            'shipping_pin_code' => 'M5V 3A8',
            'payment_method' => 'cod',
        ]);

        $order = DB::table('product_orders')->where('user_id', 1)->first();
        
        $this->assertEquals('TEST10', $order->coupon_code);
        $this->assertEquals(5.99, $order->coupon_discount_amount);
    }

    /**
     * Test place order with missing required fields
     * CI: Checkouts->placeOrder() - validation
     */
    public function test_place_order_with_missing_fields()
    {
        $response = $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            // Missing other required fields
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Required fields missing',
        ]);

        // Verify no order created
        $this->assertDatabaseMissing('product_orders', [
            'user_id' => 1,
        ]);
    }

    /**
     * Test payment gateway integration - PayPal
     * CI: Checkouts->paypal_response()
     */
    public function test_paypal_payment_response()
    {
        // Create pending order
        $orderId = DB::table('product_orders')->insertGetId([
            'user_id' => 1,
            'order_id' => 'ORD-12345',
            'email' => 'test@example.com',
            'sub_total_amount' => 59.98,
            'total_amount' => 69.98,
            'payment_method' => 'paypal',
            'payment_status' => 'pending',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/Checkouts/paypal_response', [
            'order_id' => 'ORD-12345',
            'payment_status' => 'Completed',
            'txn_id' => 'TXN123456',
        ]);

        $response->assertStatus(200);

        // Verify payment status updated
        $order = DB::table('product_orders')->where('id', $orderId)->first();
        $this->assertEquals('paid', $order->payment_status);
        $this->assertEquals('TXN123456', $order->transaction_id);
    }

    /**
     * Test same as billing address checkbox
     * CI: Checkouts->placeOrder() - shipping address logic
     */
    public function test_same_as_billing_address()
    {
        $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'same_as_billing' => 1,
            'payment_method' => 'cod',
        ]);

        $order = DB::table('product_orders')->where('user_id', 1)->first();
        
        // Verify shipping address same as billing
        $this->assertEquals($order->billing_address, $order->shipping_address);
        $this->assertEquals($order->billing_city, $order->shipping_city);
        $this->assertEquals($order->billing_state, $order->shipping_state);
    }

    /**
     * Test order status is set correctly
     * CI: Checkouts->placeOrder() - initial status
     */
    public function test_order_initial_status()
    {
        $this->post('/Checkouts/placeOrder', [
            'billing_name' => 'Test User',
            'billing_email' => 'test@example.com',
            'billing_mobile' => '1234567890',
            'billing_address' => '123 Test St',
            'billing_country' => 1,
            'billing_state' => 1,
            'billing_city' => 1,
            'billing_pin_code' => 'M5V 3A8',
            'shipping_name' => 'Test User',
            'shipping_address' => '123 Test St',
            'shipping_country' => 1,
            'shipping_state' => 1,
            'shipping_city' => 1,
            'shipping_pin_code' => 'M5V 3A8',
            'payment_method' => 'cod',
        ]);

        $order = DB::table('product_orders')->where('user_id', 1)->first();
        
        // Verify initial status (1 = Pending)
        $this->assertEquals(1, $order->status);
    }
}
