<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

/**
 * Password Reset Feature Tests
 * Tests exact behavior from CI Logins controller
 */
class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        Mail::fake();
        
        // Create test user
        DB::table('users')->insert([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'mobile' => '1234567890',
            'password' => Hash::make('oldpassword'),
            'status' => 1,
            'email_verified' => 1,
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Test forgot password page loads
     * CI: Logins->forgotPassword() line 538
     */
    public function test_forgot_password_page_loads()
    {
        $response = $this->get('/Logins/forgotPassword');
        
        $response->assertStatus(200);
        $response->assertViewIs('logins.forgot_password');
    }

    /**
     * Test send OTP for password reset
     * CI: Logins->sendOtp() line 544
     */
    public function test_send_otp_for_password_reset()
    {
        $response = $this->post('/Logins/sendOtp', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'OTP sent to your email',
        ]);

        // Verify OTP stored in session
        $this->assertTrue(Session::has('reset_otp'));
        $this->assertTrue(Session::has('reset_email'));
        $this->assertEquals('test@example.com', Session::get('reset_email'));
    }

    /**
     * Test send OTP with non-existent email
     * CI: Logins->sendOtp() - email not found
     */
    public function test_send_otp_with_nonexistent_email()
    {
        $response = $this->post('/Logins/sendOtp', [
            'email' => 'nonexistent@example.com',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Email not found',
        ]);

        // Verify no OTP stored
        $this->assertFalse(Session::has('reset_otp'));
    }

    /**
     * Test reset password with valid OTP
     * CI: Logins->resetPassword()
     */
    public function test_reset_password_with_valid_otp()
    {
        // Set OTP in session (simulating OTP sent)
        Session::put('reset_otp', '123456');
        Session::put('reset_email', 'test@example.com');

        $response = $this->post('/Logins/resetPassword', [
            'otp' => '123456',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 1,
            'msg' => 'Password reset successful',
        ]);

        // Verify password updated
        $user = DB::table('users')->where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Verify OTP cleared from session
        $this->assertFalse(Session::has('reset_otp'));
        $this->assertFalse(Session::has('reset_email'));
    }

    /**
     * Test reset password with invalid OTP
     * CI: Logins->resetPassword() - OTP validation
     */
    public function test_reset_password_with_invalid_otp()
    {
        // Set OTP in session
        Session::put('reset_otp', '123456');
        Session::put('reset_email', 'test@example.com');

        $response = $this->post('/Logins/resetPassword', [
            'otp' => 'wrongotp',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Invalid OTP',
        ]);

        // Verify password not changed
        $user = DB::table('users')->where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('oldpassword', $user->password));
    }

    /**
     * Test reset password with mismatched passwords
     * CI: Logins->resetPassword() - password validation
     */
    public function test_reset_password_with_mismatched_passwords()
    {
        Session::put('reset_otp', '123456');
        Session::put('reset_email', 'test@example.com');

        $response = $this->post('/Logins/resetPassword', [
            'otp' => '123456',
            'password' => 'newpassword123',
            'confirm_password' => 'differentpassword',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'Passwords do not match',
        ]);

        // Verify password not changed
        $user = DB::table('users')->where('email', 'test@example.com')->first();
        $this->assertTrue(Hash::check('oldpassword', $user->password));
    }

    /**
     * Test admin forgot password page
     * CI: admin/Logins->forgotPassword() line 71
     */
    public function test_admin_forgot_password_page_loads()
    {
        $response = $this->get('/pcoopadmin/forgot-password');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.logins.forgot_password');
    }

    /**
     * Test admin password reset
     * CI: admin/Logins->resetPassword() line 118
     * URL: pcoopadmin/reset-password/(:any) (CI routes.php line 57)
     */
    public function test_admin_password_reset()
    {
        // Create admin with reset token
        $resetToken = bin2hex(random_bytes(32));
        DB::table('admins')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('oldpassword'),
            'role' => 'admin',
            'status' => 1,
            'reset_token' => $resetToken,
            'reset_token_expires' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/pcoopadmin/reset-password/' . $resetToken, [
            'password' => 'newadminpass',
            'confirm_password' => 'newadminpass',
        ]);

        $response->assertStatus(200);
        $response->assertSessionHas('message_success', 'Password reset successful');

        // Verify admin password updated
        $admin = DB::table('admins')->where('email', 'admin@example.com')->first();
        $this->assertTrue(Hash::check('newadminpass', $admin->password));

        // Verify reset token cleared
        $this->assertNull($admin->reset_token);
    }

    /**
     * Test admin password reset with expired token
     * CI: admin/Logins->resetPassword() - token expiration check
     */
    public function test_admin_password_reset_with_expired_token()
    {
        // Create admin with expired reset token
        $resetToken = bin2hex(random_bytes(32));
        DB::table('admins')->insert([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('oldpassword'),
            'role' => 'admin',
            'status' => 1,
            'reset_token' => $resetToken,
            'reset_token_expires' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
        ]);

        $response = $this->post('/pcoopadmin/reset-password/' . $resetToken, [
            'password' => 'newadminpass',
            'confirm_password' => 'newadminpass',
        ]);

        $response->assertStatus(200);
        $response->assertSessionHas('error', 'Reset token has expired');

        // Verify password not changed
        $admin = DB::table('admins')->where('email', 'admin@example.com')->first();
        $this->assertTrue(Hash::check('oldpassword', $admin->password));
    }

    /**
     * Test admin password reset with invalid token
     * CI: admin/Logins->resetPassword() - token validation
     */
    public function test_admin_password_reset_with_invalid_token()
    {
        $response = $this->post('/pcoopadmin/reset-password/invalidtoken', [
            'password' => 'newadminpass',
            'confirm_password' => 'newadminpass',
        ]);

        $response->assertStatus(200);
        $response->assertSessionHas('error', 'Invalid reset token');
    }

    /**
     * Test OTP expiration
     * CI: OTP should expire after certain time
     */
    public function test_otp_expires_after_time()
    {
        // Set OTP with old timestamp
        Session::put('reset_otp', '123456');
        Session::put('reset_email', 'test@example.com');
        Session::put('reset_otp_time', time() - 3600); // 1 hour ago

        $response = $this->post('/Logins/resetPassword', [
            'otp' => '123456',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 0,
            'msg' => 'OTP has expired',
        ]);
    }

    /**
     * Test password reset email is sent
     * CI: Logins->sendOtp() sends email
     */
    public function test_password_reset_email_sent()
    {
        $this->post('/Logins/sendOtp', [
            'email' => 'test@example.com',
        ]);

        // Verify email was sent
        Mail::assertSent(function ($mail) {
            return $mail->hasTo('test@example.com') &&
                   $mail->subject === 'Password Reset OTP';
        });
    }

    /**
     * Test new password is hashed
     * CI: Uses password_hash()
     */
    public function test_new_password_is_hashed()
    {
        Session::put('reset_otp', '123456');
        Session::put('reset_email', 'test@example.com');

        $this->post('/Logins/resetPassword', [
            'otp' => '123456',
            'password' => 'newpassword123',
            'confirm_password' => 'newpassword123',
        ]);

        $user = DB::table('users')->where('email', 'test@example.com')->first();
        
        // Verify password is hashed, not plain text
        $this->assertNotEquals('newpassword123', $user->password);
        $this->assertTrue(Hash::check('newpassword123', $user->password));
    }
}
