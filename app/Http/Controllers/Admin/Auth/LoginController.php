<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Services\AdminAuthService;

class LoginController extends Controller
{
    /**
     * Show admin login form (line 14)
     */
    public function index(Request $request)
    {
        $page_title = 'Login';
        
        if ($request->isMethod('post')) {
            // Get current user IP (line 19)
            $currentUserIp = $request->ip();
            
            // Check if IP is blocked (line 20)
            $ip_info = DB::table('blocked_ips')
                ->where('created', '>=', DB::raw('date_sub(now(),interval ' . config('app.blocked_ips_access_time_in_minutes', 30) . ' minute)'))
                ->where('ip', $currentUserIp)
                ->first();
            
            if (!empty($ip_info)) {
                // Check if block time has expired (lines 22-30)
                $start_date = new \DateTime($ip_info->created);
                $since_start = $start_date->diff(new \DateTime(date('Y-m-d H:i:s')));
                
                if ($since_start->i > config('app.blocked_ips_access_time_in_minutes', 30)) {
                    session()->forget('hits_count');
                    DB::table('blocked_ips')->where('ip', $currentUserIp)->delete();
                } else {
                    session()->flash('message_error', 'Your ip address has been blocked by Security Reasons. Please try again after Sometime.');
                }
            } else {
                // Validate login (lines 32-36)
                $validator = Validator::make($request->all(), [
                    'username' => 'required',
                    'password' => 'required',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                
                if ($validator->passes()) {
                    $data = [
                        'username' => $request->input('username'),
                        'password' => $request->input('password'),
                    ];
                    
                    // Use AdminAuthService for CI-compatible authentication
                    $authenticatedAdmin = AdminAuthService::authenticate($data['username'], $data['password']);
                    
                    if ($authenticatedAdmin) {
                        if (empty($authenticatedAdmin->status)) {
                            session()->flash('message_error', 'Your account has been inactive by the admin for more enquiry please contact to support');
                        } else {
                            // Login using CI-compatible session
                            AdminAuthService::login($authenticatedAdmin);
                            
                            return redirect('/admin/Dashboards');
                        }
                    } else {
                        // Track failed login attempts (lines 52-64)
                        $count = 1;
                        if (session()->has('hits_count')) {
                            $count = session('hits_count') + 1;
                        }
                        session(['hits_count' => $count]);
                        
                        if ($count >= 3) {
                            // Block IP after 3 failed attempts (line 59)
                            DB::table('blocked_ips')->insert([
                                'ip' => $currentUserIp,
                                'created' => date('Y-m-d H:i:s'),
                            ]);
                            session()->flash('message_error', 'Your ip address has been blocked by Security Reasons. Please try again after Sometime.');
                        } else {
                            session()->flash('message_error', 'Username or password is incorrect');
                        }
                    }
                }
            }

        }
        
        return view('admin.logins.index', compact('page_title'));
    }
    
    /**
     * Check admin login (replicate Admin_Model->checkAdminLogin lines 146-159)
     */
    private function checkAdminLogin($data)
    {
        $admin = Admin::where('username', $data['username'])->first();
        
        if ($admin) {
            return $admin;
        }
        
        return null;
    }
    
    /**
     * Forgot password (lines 71-116)
     */
    public function forgotPassword(Request $request)
    {
        $page_title = 'Forgot Password';
        
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            
            if ($validator->passes()) {
                $email = $request->input('email');
                $adminData = Admin::where('email', $email)->first();
                
                if (!empty($adminData)) {
                    $url = url('pcoopadmin/reset-password/' . base64_encode($adminData->id));
                    
                    $toEmail = $email;
                    $subject = 'Reset Password';
                    $name = $adminData->name;
                    $body = '<div class="top-info" style="margin-top: 25px;text-align: left;">
                        <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                            Dear ' . $name . ',
                        <br>
                            You have successfully forgot password  your ' . config('app.name') . ' admin account. Please click on the link bellow to for reset password.
                        </span>
                    </div>
                    <div style="margin: 25px 0px;"><a style="font-size: 14px;text-transform: uppercase;color: #000;font-weight: 600;padding: 10px 30px;border-radius: 3px;border: none;background: #00a9d0;cursor: pointer;text-decoration: none;" href="' . $url . '">Visit Website</a>
                    </div>';
                    
                    // Send email (implement emailTemplate and sendEmail helpers)
                    // sendEmail($toEmail, $subject, $body);
                    
                    session()->flash('message_success', 'Please check your mail reset password link send your email id.');
                } else {
                    session()->flash('message_error', 'This email address does not exist in any account');
                }
            } else {
                session()->flash('message_error', 'Missing information.');
            }
        }
        
        return view('admin.logins.forgot_password', compact('page_title'));
    }
    
    /**
     * Reset password (lines 118-151)
     */
    public function resetPassword(Request $request, $id = null)
    {
        $page_title = 'Reset Password';
        $id = base64_decode($id);
        $userData = Admin::find($id);
        
        if (empty($userData)) {
            session()->flash('message_error', 'invalid reset password token');
            return redirect('pcoopadmin');
        }
        
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:8|max:20',
                'confirm_password' => 'required|same:password',
            ]);
            
            if ($validator->passes()) {
                $userData->password = md5($request->input('password'));
                
                if ($userData->save()) {
                    // Save password to file (line 139)
                    admin_security(['id' => $id, 'password' => $request->input('password')]);
                    
                    session()->flash('message_success', 'Your Password change has been successfully login new password.');
                    return redirect('pcoopadmin');
                } else {
                    session()->flash('message_error', 'Your Password change has been unsuccessfully.');
                }
            } else {
                session()->flash('message_error', 'Missing information.');
            }
        }
        
        return view('admin.logins.reset_password', compact('page_title'));
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        // Logout using AdminAuthService (CI-compatible)
        AdminAuthService::logout();
        
        // Also clear any Laravel auth session that might exist
        if (auth()->guard('admin')->check()) {
            auth()->guard('admin')->logout();
        }
        
        return redirect('pcoopadmin');
    }
}
