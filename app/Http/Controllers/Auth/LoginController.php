<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function index()
    {
        // Redirect if already logged in (line 11-13)
        if (session('loginId')) {
            return redirect('MyOrders');
        }
        
        $page_title = 'Login/Register';
        if (config('store.language_name') == 'French') {
            $page_title = "S'identifier S'enregistrer";
        }
        
        return view('logins.index', compact('page_title'));
    }
    
    /**
     * Process login via AJAX (lines 26-89)
     */
    public function checkLoginByAjax(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect('/');
        }
        
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];
        
        // Validation rules (lines 32-33)
        $validator = Validator::make($request->all(), [
            'loginemail' => 'required|email',
            'loginpassword' => 'required',
        ]);
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['errors'] = $validator->errors()->toArray();
        } else {
            $data = [
                'email' => $request->input('loginemail'),
                'password' => $request->input('loginpassword'),
            ];
            
            $loginUser = $this->checkUserLogin($data);
            
            if (!empty($loginUser)) {
                // Set session data (lines 60-68)
                session([
                    'loginId' => $loginUser['id'],
                    'loginName' => $loginUser['name'],
                    'loginFirstName' => $loginUser['fname'],
                    'loginLastName' => $loginUser['lname'],
                    'loginPic' => $loginUser['profile_pic'],
                    'loginMobile' => $loginUser['mobile'],
                    'loginEmail' => $loginUser['email'],
                ]);
                
                // Update last login (lines 70-74)
                User::where('id', $loginUser['id'])->update([
                    'last_login' => now(),
                    'last_login_ip' => $request->ip(),
                ]);
                
                // Determine redirect URL (lines 75-80)
                $total_items = session('cart') ? count(session('cart')) : 0;
                $url = url('MyOrders/');
                if ($total_items > 0) {
                    $url = url('Checkouts/');
                }
                $response['url'] = $url;
            } else {
                $response['status'] = 'error';
                $language = config('store.language_name');
                $response['msg'] = $language == 'French' 
                    ? "E-mail ou mot de passe incorrect" 
                    : 'Email or password is incorrect';
            }
        }
        
        return response()->json($response);
    }
    
    /**
     * Check user login (replicate User_Model->checkUserLogin lines 230-247)
     */
    private function checkUserLogin($data, $md5 = true)
    {
        $password = $data['password'];
        if ($md5) {
            $password = md5($data['password']);
        }
        
        $user = User::where('email', $data['email'])
                    ->where('password', $password)
                    ->first();
        
        if ($user) {
            return $user->toArray();
        }
        
        return [];
    }
    
    /**
     * Email verification (lines 474-536)
     */
    public function emailVerification($id)
    {
        $id = base64_decode($id);
        $userData = User::find($id);
        
        if (!empty($userData)) {
            $email_verification = $userData->email_verification;
            
            if ($email_verification == 0) {
                $userData->email_verification = 1;
                if ($userData->save()) {
                    $language = config('store.language_name');
                    if ($language == 'French') {
                        session()->flash('message_success', 'Votre compte a été vérifié avec succès');
                    } else {
                        session()->flash('message_success', 'Your Account is verified Successfully');
                    }
                    
                    return redirect('Logins');
                } else {
                    $language = config('store.language_name');
                    echo $language == 'French' 
                        ? "<h2>Le processus de vérification des e-mails a échoué</h1>" 
                        : "<h2>Email verification process has been failed</h1>";
                }
            } else {
                $language = config('store.language_name');
                echo $language == 'French' 
                    ? "<h2>votre jeton de vérification de courrier électronique a expiré</h1>" 
                    : "<h2>Your email verification token has been expired</h1>";
            }
        } else {
            $language = config('store.language_name');
            echo $language == 'French' 
                ? "<h2>Votre jeton de vérification d'e-mail n'est pas valide</h1>" 
                : "<h2>Your email verification token is not valid </h1>";
        }
    }
    
    /**
     * Send OTP for password reset (lines 544-594)
     */
    public function sendOtp(Request $request)
    {
        $email = $request->input('mobile');
        $json = ['status' => 0, 'msg' => ''];
        
        if (User::where('email', $email)->exists()) {
            $otp = $this->getOtp();
            $StoreData = config('store.main_store_data');
            $from_name = $StoreData['name'];
            $from_email = $StoreData['from_email'];
            $language = config('store.language_name');
            
            if ($language == 'French') {
                $massage = $otp . ' est le code de réinitialisation du mot de passe.code est confidentiel, veuillez ne pas partager ce code avec qui que ce soit pour assurer la sécurité des comptes';
                $subject = 'réinitialiser le mot de passe';
            } else {
                $massage = $otp . ' is reset password code.code is confidential, Please do not share this code with anyone to ensure accounts security';
                $subject = 'Reset Password';
            }
            
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">' . $massage . '</span></div>';
            
            // Send email (implement sendEmail helper)
            // sendEmail($email, $subject, $body, $from_email, $from_name);
            
            $json['status'] = 1;
            $json['msg'] = $language == 'French'
                ? 'Veuillez vérifier que votre code de mot de passe de réinitialisation de messagerie a été envoyé à votre identifiant de messagerie:' . $email
                : 'Please check your mail reset password code has been sent to your email id: ' . $email;
            $json['otp'] = $otp;
        } else {
            $language = config('store.language_name');
            $json['msg'] = $language == 'French'
                ? "Identifiant de messagerie non enregistré"
                : 'Email id not registered';
        }
        
        return response()->json($json);
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        session()->forget(['loginId', 'loginName', 'loginFirstName', 'loginLastName', 'loginPic', 'loginMobile', 'loginEmail']);
        return redirect('/');
    }
    
    /**
     * Generate OTP
     */
    private function getOtp()
    {
        return rand(100000, 999999);
    }
}
