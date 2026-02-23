<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

/**
 * Public Registration Feature Tests
 * Tests exact behavior from CI Logins controller
 */
class PublicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Mail::fake();
    }

    /**
     * Test check mobile/email availability via AJAX
     * CI: Logins->checkMobileByAjax() line 91
     */
    public function test_check_mobile_availability()
    {
        $response = $this->post('/Logins/checkMobileByAjax', [
            'mobile' => '1234567890',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Mobile number is available',
        ]);
    }

    /**
     * Test check mobile when already exists
     * CI: Logins->checkMobileByAjax() - duplicate check
     */
    public function test_check_mobile_already_exists()
    {
        // Create existing user
        DB::table('users')->insert([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'mobile' => '1234567890',
            'password' => Hash::make('password123'),
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/Logins/checkMobileByAjax', [
            'mobile' => '1234567890',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Mobile number already exists',
        ]);
    }

    /**
     * Test check email availability
     * CI: Logins->checkMobileByAjax() - email parameter
     */
    public function test_check_email_availability()
    {
        $response = $this->post('/Logins/checkMobileByAjax', [
            'email' => 'newuser@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Email is available',
        ]);
    }

    /**
     * Test check email when already exists
     * CI: Logins->checkMobileByAjax() - duplicate email check
     */
    public function test_check_email_already_exists()
    {
        // Create existing user
        DB::table('users')->insert([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'mobile' => '1234567890',
            'password' => Hash::make('password123'),
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/Logins/checkMobileByAjax', [
            'email' => 'existing@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Email already exists',
        ]);
    }

    /**
     * Test successful regular signup
     * CI: Logins->signup() line 144
     */
    public function test_successful_signup()
    {
        $response = $this->post('/Logins/signup', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '9876543210',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'otp' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Registration successful',
        ]);

        // Verify user created in database
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'mobile' => '9876543210',
            'name' => 'New User',
        ]);

        // Verify user is active by default
        $user = DB::table('users')->where('email', 'newuser@example.com')->first();
        $this->assertEquals(1, $user->status);
    }

    /**
     * Test signup with mismatched passwords
     * CI: Logins->signup() - password validation
     */
    public function test_signup_with_mismatched_passwords()
    {
        $response = $this->post('/Logins/signup', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '9876543210',
            'password' => 'password123',
            'confirm_password' => 'differentpassword',
            'otp' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Passwords do not match',
        ]);

        // Verify user not created
        $this->assertDatabaseMissing('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    /**
     * Test signup with duplicate email
     * CI: Logins->signup() - duplicate check
     */
    public function test_signup_with_duplicate_email()
    {
        // Create existing user
        DB::table('users')->insert([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'mobile' => '1234567890',
            'password' => Hash::make('password123'),
            'status' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/Logins/signup', [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'mobile' => '9876543210',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'otp' => '123456',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Email already exists',
        ]);
    }

    /**
     * Test signup with invalid OTP
     * CI: Logins->signup() - OTP verification
     */
    public function test_signup_with_invalid_otp()
    {
        $response = $this->post('/Logins/signup', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '9876543210',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'otp' => 'wrongotp',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Invalid OTP',
        ]);

        // Verify user not created
        $this->assertDatabaseMissing('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    /**
     * Test preferred customer signup
     * CI: Logins->preferred_customer_signup() line 261
     */
    public function test_preferred_customer_signup()
    {
        $response = $this->post('/Logins/preferred_customer_signup', [
            'name' => 'Preferred Customer',
            'email' => 'preferred@example.com',
            'mobile' => '5555555555',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'company_name' => 'Test Company',
            'business_type' => 'Retail',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Registration successful',
        ]);

        // Verify user created with preferred customer flag
        $this->assertDatabaseHas('users', [
            'email' => 'preferred@example.com',
            'is_preferred_customer' => 1,
        ]);
    }

    /**
     * Test email verification link
     * CI: Logins->emailVerification() line 474
     */
    public function test_email_verification()
    {
        // Create unverified user
        $userId = DB::table('users')->insertGetId([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
            'mobile' => '7777777777',
            'password' => Hash::make('password123'),
            'status' => 1,
            'email_verified' => 0,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->get('/Logins/emailVerification/' . $userId);

        $response->assertStatus(200);
        $response->assertSessionHas('message_success', 'Email verified successfully');

        // Verify email_verified flag updated
        $user = DB::table('users')->where('id', $userId)->first();
        $this->assertEquals(1, $user->email_verified);
    }

    /**
     * Test signup without POST data redirects
     * CI: Logins->signup() line 146-148
     */
    public function test_signup_without_post_redirects()
    {
        $response = $this->get('/Logins/signup');
        
        $response->assertRedirect('/');
    }

    /**
     * Test password is hashed correctly
     * CI: Uses password_hash()
     */
    public function test_password_is_hashed()
    {
        $this->post('/Logins/signup', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'mobile' => '9876543210',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'otp' => '123456',
        ]);

        $user = DB::table('users')->where('email', 'newuser@example.com')->first();
        
        // Verify password is hashed, not plain text
        $this->assertNotEquals('password123', $user->password);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    /**
     * Test user fields are saved correctly
     * CI: Saves all user fields
     */
    public function test_user_fields_saved_correctly()
    {
        $this->post('/Logins/signup', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobile' => '1112223333',
            'password' => 'password123',
            'confirm_password' => 'password123',
            'otp' => '123456',
        ]);

        $user = DB::table('users')->where('email', 'test@example.com')->first();
        
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertEquals('1112223333', $user->mobile);
        $this->assertEquals(1, $user->status);
        $this->assertNotNull($user->created);
        $this->assertNotNull($user->updated);
    }
}
