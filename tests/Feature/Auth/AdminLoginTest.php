<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * Admin Login Feature Tests
 * Tests exact behavior from CI admin/Logins controller
 */
class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test admin
        DB::table('admins')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Test admin login page loads
     * CI: admin/Logins->index() line 14
     * URL: pcoopadmin (CI routes.php line 56)
     */
    public function test_admin_login_page_loads()
    {
        $response = $this->get('/pcoopadmin');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.logins.index');
    }

    /**
     * Test successful admin login
     * CI: admin/Logins->index() POST
     */
    public function test_successful_admin_login()
    {
        $response = $this->post('/pcoopadmin', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/Dashboards');

        // Verify admin session keys (CI sets these)
        $this->assertTrue(Session::has('adminLoginId'));
        $this->assertTrue(Session::has('adminLoginEmail'));
        $this->assertTrue(Session::has('adminLoginName'));
        $this->assertTrue(Session::has('adminLoginRole'));
    }

    /**
     * Test admin login with invalid credentials
     * CI: admin/Logins->index() - invalid password
     */
    public function test_admin_login_with_invalid_password()
    {
        $response = $this->post('/pcoopadmin', [
            'email' => 'admin@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(200);
        $response->assertSessionHas('error', 'Invalid email or password');

        // Verify no session created
        $this->assertFalse(Session::has('adminLoginId'));
    }

    /**
     * Test admin login with non-existent email
     * CI: admin/Logins->index() - email not found
     */
    public function test_admin_login_with_nonexistent_email()
    {
        $response = $this->post('/pcoopadmin', [
            'email' => 'nonexistent@example.com',
            'password' => 'admin123',
        ]);

        $response->assertStatus(200);
        $response->assertSessionHas('error', 'Invalid email or password');
    }

    /**
     * Test admin login with inactive account
     * CI: Checks status = 1
     */
    public function test_admin_login_with_inactive_account()
    {
        // Create inactive admin
        DB::table('admins')->insert([
            'name' => 'Inactive Admin',
            'email' => 'inactive@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 0,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/pcoopadmin', [
            'email' => 'inactive@example.com',
            'password' => 'admin123',
        ]);

        $response->assertStatus(200);
        $response->assertSessionHas('error', 'Your account is inactive');
    }

    /**
     * Test admin session keys match CI exactly
     * CI sets: adminLoginId, adminLoginEmail, adminLoginName, adminLoginRole
     */
    public function test_admin_session_keys_match_ci()
    {
        $this->post('/pcoopadmin', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        // Get admin data
        $admin = DB::table('admins')->where('email', 'admin@example.com')->first();

        // Verify exact session keys from CI
        $this->assertEquals($admin->id, Session::get('adminLoginId'));
        $this->assertEquals($admin->email, Session::get('adminLoginEmail'));
        $this->assertEquals($admin->name, Session::get('adminLoginName'));
        $this->assertEquals($admin->role, Session::get('adminLoginRole'));
    }

    /**
     * Test admin logout
     * CI: admin/Accounts->logout()
     */
    public function test_admin_logout_clears_session()
    {
        // Login first
        $this->post('/pcoopadmin', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        // Verify session exists
        $this->assertTrue(Session::has('adminLoginId'));

        // Logout
        $response = $this->get('/admin/Dashboards/logout');

        // Verify session cleared
        $this->assertFalse(Session::has('adminLoginId'));
        $this->assertFalse(Session::has('adminLoginEmail'));
        $this->assertFalse(Session::has('adminLoginName'));
        $this->assertFalse(Session::has('adminLoginRole'));

        // Verify redirect to admin login
        $response->assertRedirect('/pcoopadmin');
    }

    /**
     * Test sub-admin login
     * CI: Different dashboard for sub-admin role
     */
    public function test_sub_admin_login()
    {
        // Create sub-admin
        DB::table('admins')->insert([
            'name' => 'Sub Admin',
            'email' => 'subadmin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'sub_admin',
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/pcoopadmin', [
            'email' => 'subadmin@example.com',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/admin/Dashboards');

        // Verify role is set correctly
        $this->assertEquals('sub_admin', Session::get('adminLoginRole'));
    }

    /**
     * Test admin authentication middleware
     * CI: Admin_Controller checks session
     */
    public function test_admin_routes_require_authentication()
    {
        // Try to access admin dashboard without login
        $response = $this->get('/admin/Dashboards');

        // Should redirect to login
        $response->assertRedirect('/pcoopadmin');
    }

    /**
     * Test authenticated admin can access dashboard
     * CI: Admin_Controller allows access when logged in
     */
    public function test_authenticated_admin_can_access_dashboard()
    {
        // Login
        $this->post('/pcoopadmin', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        // Access dashboard
        $response = $this->get('/admin/Dashboards');

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboards.index');
    }

    /**
     * Test admin login updates last_login timestamp
     * CI: Updates admin record on successful login
     */
    public function test_admin_login_updates_timestamp()
    {
        $admin = DB::table('admins')->where('email', 'admin@example.com')->first();
        $originalUpdated = $admin->updated;

        // Wait a moment to ensure timestamp difference
        sleep(1);

        $this->post('/pcoopadmin', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        $admin = DB::table('admins')->where('email', 'admin@example.com')->first();
        
        $this->assertNotEquals($originalUpdated, $admin->updated);
    }

    /**
     * Test admin role-based access
     * CI: Admin_Controller checks adminLoginRole
     */
    public function test_admin_role_stored_in_session()
    {
        $this->post('/pcoopadmin', [
            'email' => 'admin@example.com',
            'password' => 'admin123',
        ]);

        $this->assertEquals('admin', Session::get('adminLoginRole'));
    }
}
