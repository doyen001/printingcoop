<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * Public Login Feature Tests
 * Tests exact behavior from CI Logins controller
 */
class PublicLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobile' => '1234567890',
            'password' => Hash::make('password123'),
            'status' => 1,
            'email_verified' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Test login page loads correctly
     * CI: Logins->index() line 16
     */
    public function test_login_page_loads()
    {
        $response = $this->get('/Logins');
        
        $response->assertStatus(200);
        $response->assertViewIs('logins.index');
    }

    /**
     * Test successful login via AJAX
     * CI: Logins->checkLoginByAjax() line 26
     */
    public function test_successful_login_via_ajax()
    {
        $response = $this->post('/Logins/checkLoginByAjax', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
        ]);

        // Verify session keys (CI sets these)
        $this->assertTrue(Session::has('loginUserId'));
        $this->assertTrue(Session::has('loginUserEmail'));
        $this->assertTrue(Session::has('loginUserName'));
    }

    /**
     * Test login with invalid credentials
     * CI: Logins->checkLoginByAjax() - invalid password
     */
    public function test_login_with_invalid_password()
    {
        $response = $this->post('/Logins/checkLoginByAjax', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Invalid Email or Password',
        ]);

        // Verify no session is created
        $this->assertFalse(Session::has('loginUserId'));
    }

    /**
     * Test login with non-existent email
     * CI: Logins->checkLoginByAjax() - email not found
     */
    public function test_login_with_nonexistent_email()
    {
        $response = $this->post('/Logins/checkLoginByAjax', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Invalid Email or Password',
        ]);
    }

    /**
     * Test login with inactive user account
     * CI: Checks status = 1
     */
    public function test_login_with_inactive_account()
    {
        // Create inactive user
        DB::table('users')->insert([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'mobile' => '9876543210',
            'password' => Hash::make('password123'),
            'status' => 0,
            'email_verified' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/Logins/checkLoginByAjax', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Your account is inactive. Please contact administrator.',
        ]);
    }

    /**
     * Test login with unverified email
     * CI: Checks email_verified = 1
     */
    public function test_login_with_unverified_email()
    {
        // Create unverified user
        DB::table('users')->insert([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'mobile' => '5555555555',
            'password' => Hash::make('password123'),
            'status' => 1,
            'email_verified' => 0,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/Logins/checkLoginByAjax', [
            'email' => 'unverified@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Please verify your email address.',
        ]);
    }

    /**
     * Test login without POST data redirects
     * CI: Logins->checkLoginByAjax() line 27-29
     */
    public function test_login_without_post_redirects()
    {
        $response = $this->get('/Logins/checkLoginByAjax');
        
        $response->assertRedirect('/');
    }

    /**
     * Test logout functionality
     * CI: Logins->logout()
     */
    public function test_logout_clears_session()
    {
        // Login first
        $this->post('/Logins/checkLoginByAjax', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Verify session exists
        $this->assertTrue(Session::has('loginUserId'));

        // Logout
        $response = $this->get('/Logins/logout');

        // Verify session cleared
        $this->assertFalse(Session::has('loginUserId'));
        $this->assertFalse(Session::has('loginUserEmail'));
        $this->assertFalse(Session::has('loginUserName'));

        // Verify redirect to home
        $response->assertRedirect('/');
    }

    /**
     * Test session keys match CI exactly
     * CI sets: loginUserId, loginUserEmail, loginUserName, loginUserMobile
     */
    public function test_session_keys_match_ci()
    {
        $response = $this->post('/Logins/checkLoginByAjax', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Get user data
        $user = DB::table('users')->where('email', 'test@example.com')->first();

        // Verify exact session keys from CI
        $this->assertEquals($user->id, Session::get('loginUserId'));
        $this->assertEquals($user->email, Session::get('loginUserEmail'));
        $this->assertEquals($user->name, Session::get('loginUserName'));
        $this->assertEquals($user->mobile, Session::get('loginUserMobile'));
    }

    /**
     * Test login updates last_login timestamp
     * CI: Updates user record on successful login
     */
    public function test_login_updates_last_login()
    {
        $user = DB::table('users')->where('email', 'test@example.com')->first();
        $originalUpdated = $user->updated;

        // Wait a moment to ensure timestamp difference
        sleep(1);

        $this->post('/Logins/checkLoginByAjax', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $user = DB::table('users')->where('email', 'test@example.com')->first();
        
        $this->assertNotEquals($originalUpdated, $user->updated);
    }
}
