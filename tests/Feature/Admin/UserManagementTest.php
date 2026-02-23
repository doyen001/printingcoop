<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

/**
 * Admin User Management Feature Tests
 * Tests exact behavior from CI admin/Users controller
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
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
        
        // Create test users
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Test User 1',
                'email' => 'user1@example.com',
                'mobile' => '1234567890',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified' => 1,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ],
            [
                'id' => 2,
                'name' => 'Test User 2',
                'email' => 'user2@example.com',
                'mobile' => '0987654321',
                'password' => Hash::make('password'),
                'status' => 1,
                'email_verified' => 1,
                'created' => date('Y-m-d H:i:s'),
                'updated' => date('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Test admin users listing page loads
     * CI: admin/Users->index()
     */
    public function test_admin_users_listing_page_loads()
    {
        $response = $this->get('/admin/Users');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('users');
    }

    /**
     * Test users listing requires admin authentication
     * CI: Admin_Controller checks session
     */
    public function test_users_listing_requires_authentication()
    {
        Session::forget('adminLoginId');
        
        $response = $this->get('/admin/Users');
        
        $response->assertRedirect('/pcoopadmin');
    }

    /**
     * Test add user page loads
     * CI: admin/Users->addEdit()
     */
    public function test_add_user_page_loads()
    {
        $response = $this->get('/admin/Users/addEdit');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.add_edit');
    }

    /**
     * Test create new user
     * CI: admin/Users->addEdit() POST
     */
    public function test_create_new_user()
    {
        $response = $this->post('/admin/Users/addEdit', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '5555555555',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'status' => 1,
        ]);

        $response->assertRedirect('/admin/Users');
        $response->assertSessionHas('message_success', 'User created successfully');

        // Verify user created in database
        $this->assertDatabaseHas('users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '5555555555',
        ]);
    }

    /**
     * Test edit user page loads
     * CI: admin/Users->addEdit($id)
     */
    public function test_edit_user_page_loads()
    {
        $response = $this->get('/admin/Users/addEdit/1');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.users.add_edit');
        $response->assertViewHas('user');
    }

    /**
     * Test update existing user
     * CI: admin/Users->addEdit($id) POST
     */
    public function test_update_existing_user()
    {
        $response = $this->post('/admin/Users/addEdit/1', [
            'name' => 'Updated User',
            'email' => 'user1@example.com',
            'mobile' => '1234567890',
            'status' => 1,
        ]);

        $response->assertRedirect('/admin/Users');
        $response->assertSessionHas('message_success', 'User updated successfully');

        // Verify user updated
        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals('Updated User', $user->name);
    }

    /**
     * Test delete user
     * CI: admin/Users->delete($id)
     */
    public function test_delete_user()
    {
        $response = $this->get('/admin/Users/delete/1');

        $response->assertRedirect('/admin/Users');
        $response->assertSessionHas('message_success', 'User deleted successfully');

        // Verify user deleted
        $user = DB::table('users')->where('id', 1)->first();
        $this->assertNull($user);
    }

    /**
     * Test activate/deactivate user
     * CI: admin/Users->activeInactive($id)
     */
    public function test_toggle_user_status()
    {
        // Deactivate
        $response = $this->get('/admin/Users/activeInactive/1');

        $response->assertRedirect('/admin/Users');

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals(0, $user->status);

        // Activate
        $response = $this->get('/admin/Users/activeInactive/1');

        $user = DB::table('users')->where('id', 1)->first();
        $this->assertEquals(1, $user->status);
    }

    /**
     * Test view user details
     * CI: admin/Users->view($id)
     */
    public function test_view_user_details()
    {
        $response = $this->get('/admin/Users/view/1');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.view');
        $response->assertViewHas('user');
    }

    /**
     * Test user details show correct data
     * CI: admin/Users->view($id) - user data
     */
    public function test_user_details_show_correct_data()
    {
        $response = $this->get('/admin/Users/view/1');

        $user = $response->viewData('user');
        
        $this->assertEquals('Test User 1', $user['name']);
        $this->assertEquals('user1@example.com', $user['email']);
        $this->assertEquals('1234567890', $user['mobile']);
    }

    /**
     * Test view user order history
     * CI: admin/Users->view($id) - order history
     */
    public function test_view_user_order_history()
    {
        // Create test order
        DB::table('product_orders')->insert([
            'user_id' => 1,
            'order_id' => 'ORD-12345',
            'email' => 'user1@example.com',
            'total_amount' => 99.99,
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Users/view/1');

        $response->assertViewHas('user_orders');
        $orders = $response->viewData('user_orders');
        
        $this->assertCount(1, $orders);
        $this->assertEquals('ORD-12345', $orders[0]['order_id']);
    }

    /**
     * Test view user wishlist
     * CI: admin/Users->wishlist($id)
     */
    public function test_view_user_wishlist()
    {
        // Create wishlist items
        DB::table('wishlists')->insert([
            'user_id' => 1,
            'product_id' => 1,
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Users/wishlist/1');

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.wishlist');
        $response->assertViewHas('wishlist_items');
    }

    /**
     * Test preferred customer listing
     * CI: admin/Users->preferredCustomer()
     */
    public function test_preferred_customer_listing()
    {
        // Create preferred customer
        DB::table('users')->insert([
            'id' => 3,
            'name' => 'Preferred Customer',
            'email' => 'preferred@example.com',
            'mobile' => '3333333333',
            'password' => Hash::make('password'),
            'is_preferred_customer' => 1,
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Users/preferredCustomer');

        $response->assertStatus(200);
        $response->assertViewHas('preferred_customers');
        
        $customers = $response->viewData('preferred_customers');
        $this->assertCount(1, $customers);
    }

    /**
     * Test subscribe email listing
     * CI: admin/Users->subscribeEmail()
     */
    public function test_subscribe_email_listing()
    {
        // Create email subscription
        DB::table('email_subscriptions')->insert([
            'email' => 'subscriber@example.com',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Users/subscribeEmail');

        $response->assertStatus(200);
        $response->assertViewHas('subscribers');
    }

    /**
     * Test user validation - required fields
     * CI: admin/Users->addEdit() - validation
     */
    public function test_user_validation_required_fields()
    {
        $response = $this->post('/admin/Users/addEdit', [
            'name' => '',
            'email' => '',
            'mobile' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'mobile']);
    }

    /**
     * Test user validation - unique email
     * CI: admin/Users->addEdit() - email validation
     */
    public function test_user_validation_unique_email()
    {
        $response = $this->post('/admin/Users/addEdit', [
            'name' => 'Another User',
            'email' => 'user1@example.com', // Duplicate email
            'mobile' => '9999999999',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'status' => 1,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * Test user validation - password match
     * CI: admin/Users->addEdit() - password validation
     */
    public function test_user_validation_password_match()
    {
        $response = $this->post('/admin/Users/addEdit', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '7777777777',
            'password' => 'password123',
            'confirm_password' => 'differentpassword',
            'status' => 1,
        ]);

        $response->assertSessionHasErrors(['confirm_password']);
    }

    /**
     * Test search users by name
     * CI: admin/Users->index() - search
     */
    public function test_search_users_by_name()
    {
        $response = $this->get('/admin/Users?search=Test User 1');

        $users = $response->viewData('users');
        
        $this->assertCount(1, $users);
        $this->assertEquals('Test User 1', $users[0]['name']);
    }

    /**
     * Test search users by email
     * CI: admin/Users->index() - email search
     */
    public function test_search_users_by_email()
    {
        $response = $this->get('/admin/Users?search=user1@example.com');

        $users = $response->viewData('users');
        
        foreach ($users as $user) {
            $this->assertEquals('user1@example.com', $user['email']);
        }
    }

    /**
     * Test filter users by status
     * CI: admin/Users->index() - status filter
     */
    public function test_filter_users_by_status()
    {
        // Create inactive user
        DB::table('users')->insert([
            'id' => 3,
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'mobile' => '8888888888',
            'password' => Hash::make('password'),
            'status' => 0,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Users?status=0');

        $users = $response->viewData('users');
        
        foreach ($users as $user) {
            $this->assertEquals(0, $user['status']);
        }
    }

    /**
     * Test sub-admin cannot delete users
     * CI: Admin_Controller role-based permissions
     */
    public function test_sub_admin_cannot_delete_users()
    {
        Session::put('adminLoginRole', 'sub_admin');

        $response = $this->get('/admin/Users/delete/1');

        $response->assertStatus(403);
        $response->assertSessionHas('error', 'You do not have permission');
    }

    /**
     * Test password is hashed when creating user
     * CI: admin/Users->addEdit() - password hashing
     */
    public function test_password_is_hashed_when_creating_user()
    {
        $this->post('/admin/Users/addEdit', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '6666666666',
            'password' => 'plainpassword',
            'confirm_password' => 'plainpassword',
            'status' => 1,
        ]);

        $user = DB::table('users')->where('email', 'newuser@example.com')->first();
        
        // Verify password is hashed
        $this->assertNotEquals('plainpassword', $user->password);
        $this->assertTrue(Hash::check('plainpassword', $user->password));
    }

    /**
     * Test update user without changing password
     * CI: admin/Users->addEdit($id) - optional password
     */
    public function test_update_user_without_changing_password()
    {
        $originalPassword = DB::table('users')->where('id', 1)->value('password');

        $this->post('/admin/Users/addEdit/1', [
            'name' => 'Updated User',
            'email' => 'user1@example.com',
            'mobile' => '1234567890',
            'status' => 1,
            // No password fields
        ]);

        $user = DB::table('users')->where('id', 1)->first();
        
        // Verify password unchanged
        $this->assertEquals($originalPassword, $user->password);
    }

    /**
     * Test user statistics on dashboard
     * CI: admin/Dashboards->index() - user stats
     */
    public function test_user_statistics_displayed()
    {
        $response = $this->get('/admin/Dashboards');

        $response->assertViewHas('totalUsers');
        $response->assertViewHas('activeUsers');
        $response->assertViewHas('newUsersThisMonth');
    }

    /**
     * Test export users to CSV
     * CI: admin/Users->exportCSV()
     */
    public function test_export_users_to_csv()
    {
        $response = $this->get('/admin/Users/exportCSV');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv');
        $response->assertHeader('Content-Disposition', 'attachment; filename="users.csv"');
    }

    /**
     * Test bulk user status update
     * CI: admin/Users - bulk actions
     */
    public function test_bulk_user_status_update()
    {
        $response = $this->post('/admin/Users/bulkStatusUpdate', [
            'user_ids' => [1, 2],
            'status' => 0,
        ]);

        $response->assertRedirect('/admin/Users');

        // Verify users deactivated
        $user1 = DB::table('users')->where('id', 1)->first();
        $user2 = DB::table('users')->where('id', 2)->first();
        
        $this->assertEquals(0, $user1->status);
        $this->assertEquals(0, $user2->status);
    }

    /**
     * Test user addresses displayed
     * CI: admin/Users->view($id) - addresses
     */
    public function test_user_addresses_displayed()
    {
        // Create user address
        DB::table('addresses')->insert([
            'user_id' => 1,
            'address_type' => 'billing',
            'address' => '123 Test St',
            'city' => 'Toronto',
            'state' => 'Ontario',
            'country' => 'Canada',
            'pin_code' => 'M5V 3A8',
            'created' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/admin/Users/view/1');

        $response->assertViewHas('user_addresses');
        $addresses = $response->viewData('user_addresses');
        
        $this->assertCount(1, $addresses);
    }
}
