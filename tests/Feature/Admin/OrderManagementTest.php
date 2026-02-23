<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

/**
 * Admin Order Management Feature Tests
 * Tests exact behavior from CI admin/Orders controller
 */
class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Mail::fake();
        
        // Create admin user
        DB::table('admins')->insert([
            'id' => 1,
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
        
        // Login admin
        Session::put('adminLoginId', 1);
        Session::put('adminLoginEmail', 'admin@example.com');
        Session::put('adminLoginName', 'Test Admin');
        Session::put('adminLoginRole', 'admin');
        
        // Create test user
        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'user@example.com',
            'mobile' => '1234567890',
            'password' => bcrypt('password'),
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
        
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
        
        // Create test order
        DB::table('product_orders')->insert([
            'id' => 1,
            'user_id' => 1,
            'order_id' => 'ORD-12345',
            'email' => 'user@example.com',
            'sub_total_amount' => 59.98,
            'total_sales_tax' => 7.80,
            'delivery_charge' => 5.00,
            'total_amount' => 72.78,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
        
        // Create order items
        DB::table('product_order_items')->insert([
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 2,
            'price' => 29.99,
            'total' => 59.98,
            'created' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Test admin orders listing page loads
     * CI: admin/Orders->index()
     */
    public function test_admin_orders_listing_page_loads()
    {
        $response = $this->get('/admin/Orders');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.index');
        $response->assertViewHas('orders');
    }

    /**
     * Test orders listing requires admin authentication
     * CI: Admin_Controller checks session
     */
    public function test_orders_listing_requires_authentication()
    {
        Session::forget('adminLoginId');
        
        $response = $this->get('/admin/Orders');
        
        $response->assertRedirect('/pcoopadmin');
    }

    /**
     * Test view order details
     * CI: admin/Orders->view($id)
     */
    public function test_view_order_details()
    {
        $response = $this->get('/admin/Orders/view/1');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.orders.view');
        $response->assertViewHas('order');
        $response->assertViewHas('order_items');
    }

    /**
     * Test order details show correct data
     * CI: admin/Orders->view($id) - order data
     */
    public function test_order_details_show_correct_data()
    {
        $response = $this->get('/admin/Orders/view/1');
        
        $order = $response->viewData('order');
        
        $this->assertEquals('ORD-12345', $order['order_id']);
        $this->assertEquals(72.78, $order['total_amount']);
        $this->assertEquals('user@example.com', $order['email']);
    }

    /**
     * Test change order status
     * CI: admin/Orders->changeOrderStatus()
     */
    public function test_change_order_status()
    {
        $response = $this->post('/admin/Orders/changeOrderStatus', [
            'order_id' => 1,
            'status' => 2, // Processing
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Order status updated',
        ]);

        // Verify status updated in database
        $order = DB::table('product_orders')->where('id', 1)->first();
        $this->assertEquals(2, $order->status);
    }

    /**
     * Test change order status sends email notification
     * CI: admin/Orders->changeOrderStatus() - email notification
     */
    public function test_change_order_status_sends_email()
    {
        $this->post('/admin/Orders/changeOrderStatus', [
            'order_id' => 1,
            'status' => 3, // Shipped
        ]);

        // Verify email sent to customer
        Mail::assertSent(function ($mail) {
            return $mail->hasTo('user@example.com') &&
                   $mail->subject === 'Order Status Updated';
        });
    }

    /**
     * Test change payment status
     * CI: admin/Orders->changePaymentStatus()
     */
    public function test_change_payment_status()
    {
        $response = $this->post('/admin/Orders/changePaymentStatus', [
            'order_id' => 1,
            'payment_status' => 'paid',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Payment status updated',
        ]);

        // Verify payment status updated
        $order = DB::table('product_orders')->where('id', 1)->first();
        $this->assertEquals('paid', $order->payment_status);
    }

    /**
     * Test delete order
     * CI: admin/Orders->delete($id)
     */
    public function test_delete_order()
    {
        $response = $this->get('/admin/Orders/delete/1');

        $response->assertRedirect('/admin/Orders');
        $response->assertSessionHas('message_success', 'Order deleted successfully');

        // Verify order deleted (soft delete or status change)
        $order = DB::table('product_orders')->where('id', 1)->first();
        $this->assertNull($order); // Or check deleted_at/status
    }

    /**
     * Test export orders to CSV
     * CI: admin/Orders->exportCSV()
     */
    public function test_export_orders_to_csv()
    {
        $response = $this->get('/admin/Orders/exportCSV');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="orders.csv"');
    }

    /**
     * Test download order invoice PDF
     * CI: admin/Orders->downloadInvoice($id)
     */
    public function test_download_order_invoice()
    {
        $response = $this->get('/admin/Orders/downloadInvoice/1');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test download order PDF
     * CI: admin/Orders->downloadOrder($id)
     */
    public function test_download_order_pdf()
    {
        $response = $this->get('/admin/Orders/downloadOrder/1');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test filter orders by status
     * CI: admin/Orders->index() - status filter
     */
    public function test_filter_orders_by_status()
    {
        // Create order with different status
        DB::table('product_orders')->insert([
            'id' => 2,
            'user_id' => 1,
            'order_id' => 'ORD-12346',
            'email' => 'user@example.com',
            'sub_total_amount' => 29.99,
            'total_amount' => 35.99,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'status' => 3, // Shipped
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Orders?status=3');

        $orders = $response->viewData('orders');
        
        // Verify only shipped orders returned
        foreach ($orders as $order) {
            $this->assertEquals(3, $order['status']);
        }
    }

    /**
     * Test filter orders by date range
     * CI: admin/Orders->index() - date filter
     */
    public function test_filter_orders_by_date_range()
    {
        $response = $this->get('/admin/Orders', [
            'date_from' => date('Y-m-d', strtotime('-7 days')),
            'date_to' => date('Y-m-d'),
        ]);

        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    /**
     * Test search orders by order ID
     * CI: admin/Orders->index() - search
     */
    public function test_search_orders_by_order_id()
    {
        $response = $this->get('/admin/Orders?search=ORD-12345');

        $orders = $response->viewData('orders');
        
        $this->assertCount(1, $orders);
        $this->assertEquals('ORD-12345', $orders[0]['order_id']);
    }

    /**
     * Test search orders by customer email
     * CI: admin/Orders->index() - email search
     */
    public function test_search_orders_by_customer_email()
    {
        $response = $this->get('/admin/Orders?search=user@example.com');

        $orders = $response->viewData('orders');
        
        foreach ($orders as $order) {
            $this->assertEquals('user@example.com', $order['email']);
        }
    }

    /**
     * Test order statistics on dashboard
     * CI: admin/Dashboards->index() - order stats
     */
    public function test_order_statistics_displayed()
    {
        $response = $this->get('/admin/Dashboards');

        $response->assertViewHas('totalOrders');
        $response->assertViewHas('pendingOrders');
        $response->assertViewHas('completedOrders');
        $response->assertViewHas('totalRevenue');
    }

    /**
     * Test sub-admin has limited order access
     * CI: Admin_Controller checks role permissions
     */
    public function test_sub_admin_limited_order_access()
    {
        // Change to sub-admin
        Session::put('adminLoginRole', 'sub_admin');

        $response = $this->get('/admin/Orders/delete/1');

        $response->assertStatus(403);
        $response->assertSessionHas('error', 'You do not have permission');
    }

    /**
     * Test order item details displayed
     * CI: admin/Orders->view($id) - order items
     */
    public function test_order_item_details_displayed()
    {
        $response = $this->get('/admin/Orders/view/1');

        $orderItems = $response->viewData('order_items');
        
        $this->assertCount(1, $orderItems);
        $this->assertEquals(1, $orderItems[0]['product_id']);
        $this->assertEquals(2, $orderItems[0]['quantity']);
        $this->assertEquals(29.99, $orderItems[0]['price']);
    }

    /**
     * Test order totals calculated correctly
     * CI: admin/Orders->view($id) - calculations
     */
    public function test_order_totals_calculated_correctly()
    {
        $response = $this->get('/admin/Orders/view/1');

        $order = $response->viewData('order');
        
        $expectedTotal = $order['sub_total_amount'] + 
                        $order['total_sales_tax'] + 
                        $order['delivery_charge'];
        
        $this->assertEquals($expectedTotal, $order['total_amount']);
    }

    /**
     * Test download uploaded order files
     * CI: admin/Orders->download($location, $name)
     */
    public function test_download_uploaded_order_files()
    {
        // Create test file reference
        DB::table('order_files')->insert([
            'order_id' => 1,
            'file_name' => 'design.pdf',
            'file_path' => 'uploads/orders/design.pdf',
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Orders/download/orders/design.pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test order status workflow
     * CI: admin/Orders - status progression
     */
    public function test_order_status_workflow()
    {
        // 1. Pending
        $order = DB::table('product_orders')->where('id', 1)->first();
        $this->assertEquals(1, $order->status);

        // 2. Processing
        $this->post('/admin/Orders/changeOrderStatus', [
            'order_id' => 1,
            'status' => 2,
        ]);
        $order = DB::table('product_orders')->where('id', 1)->first();
        $this->assertEquals(2, $order->status);

        // 3. Shipped
        $this->post('/admin/Orders/changeOrderStatus', [
            'order_id' => 1,
            'status' => 3,
        ]);
        $order = DB::table('product_orders')->where('id', 1)->first();
        $this->assertEquals(3, $order->status);

        // 4. Delivered
        $this->post('/admin/Orders/changeOrderStatus', [
            'order_id' => 1,
            'status' => 4,
        ]);
        $order = DB::table('product_orders')->where('id', 1)->first();
        $this->assertEquals(4, $order->status);
    }
}
