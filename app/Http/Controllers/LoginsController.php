<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * LoginsController
 * Replicate CI Logins controller
 * CI: application/controllers/Logins.php
 */
class LoginsController extends Controller
{
    /**
     * Display login/register page
     * CI: Logins->index() lines 16-24
     */
    public function index()
    {
        // Redirect if already logged in (CI lines 11-13)
        if (session('loginId')) {
            return redirect('MyOrders');
        }
        
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? "S'identifier S'enregistrer" : 'Login/Register',
            'language_name' => $language_name,
        ];
        
        return view('logins.index', $data);
    }
    
    /**
     * Check login credentials via AJAX
     * CI: Logins->checkLoginByAjax() lines 26-89
     */
    public function checkLoginByAjax(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];
        
        // Validate input
        $validator = \Validator::make($request->all(), [
            'loginemail' => 'required|email',
            'loginpassword' => 'required',
        ]);
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['errors'] = $validator->errors()->toArray();
        } else {
            $email = $request->input('loginemail');
            $password = $request->input('loginpassword');
            
            // Check user login
            $user = DB::table('users')
                ->where('email', $email)
                ->where('password', md5($password))
                ->first();
            
            if ($user) {
                // Set session data
                session([
                    'loginId' => $user->id,
                    'loginName' => $user->name,
                    'loginFirstName' => $user->fname,
                    'loginLastName' => $user->lname,
                    'loginPic' => $user->profile_pic ?? '',
                    'loginMobile' => $user->mobile ?? '',
                    'loginEmail' => $user->email,
                ]);
                // Update last login
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'last_login' => now(),
                        'last_login_ip' => $request->ip(),
                    ]);
                
                // Redirect to MyOrders after successful login
                $response['url'] = url('MyOrders');
            } else {
                $response['status'] = 'error';
                $response['msg'] = $language_name == 'french' ? "E-mail ou mot de passe incorrect" : 'Email or password is incorrect';
            }
        }
        
        echo json_encode($response);
    }
    
    /**
     * Check if mobile/email exists and send OTP for registration
     * CI: Logins->checkMobileByAjax() lines 91-142
     */
    public function checkMobileByAjax(Request $request)
    {
        $email = $request->input('ck_moblie_number');
        $language_name = config('store.language_name', 'english');
        
        $json = ['status' => 0, 'msg' => ''];
        
        // Check if email already exists
        $userExists = DB::table('users')->where('email', $email)->exists();
        
        if ($userExists) {
            $json['status'] = 1;
            $json['login'] = 1; // Email exists, user should login
        } else {
            // Generate OTP for new registration
            $otp = rand(100000, 999999);
            $json['status'] = 1;
            $json['login'] = 2; // New user, proceed with registration
            $json['otp'] = $otp;
            
            // Get store data
            $store = DB::table('stores')->where('id', config('store.main_store_id', 1))->first();
            
            // Prepare email content
            if ($language_name == 'french') {
                $message = $otp . " Le code de vérification de l'e-mail pour signup.code est confidentiel, veuillez ne pas partager ce code avec qui que ce soit pour assurer la sécurité des comptes";
                $subject = "Code de vérification de l'e-mail";
                $json['msg'] = 'Veuillez vérifier que le code de vérification de votre e-mail a été envoyé à votre identifiant de messagerie';
            } else {
                $message = $otp . ' is email verification code for signup.code is confidential, Please do not share this code with anyone to ensure accounts security';
                $subject = 'Email Verification Code';
                $json['msg'] = 'Please check your mail email verification code has been sent to your email id';
            }
            
            // Send email
            try {
                \Mail::raw($message, function($mail) use ($email, $subject, $store) {
                    $mail->to($email)
                         ->subject($subject)
                         ->from($store->from_email ?? 'info@printing.coop', $store->name ?? 'Printing Coop');
                });
            } catch (\Exception $e) {
                // Email sending failed, but still return OTP for testing
            }
        }
        
        echo json_encode($json);
    }
    
    /**
     * Register new user
     * CI: Logins->signup() lines 144-257
     */
    public function signup(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];
        
        // Validation rules
        $rules = [
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password',
        ];
        
        // Custom error messages
        $messages = [];
        if ($language_name == 'french') {
            $messages = [
                'fname.required' => 'entrez votre prénom',
                'lname.required' => 'Entrer le nom de famille',
                'email.required' => "Entrez l'identifiant de l'e-mail",
                'email.unique' => 'Identifiant de messagerie déjà enregistré',
                'password.required' => 'Entrer le mot de passe',
                'confirm_password.same' => 'Le mot de passe de confirmation ne correspond pas',
            ];
        }
        
        $validator = \Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['errors'] = $validator->errors()->toArray();
        } else {
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');
            $password = $request->input('password');
            $emailVerification = $request->input('email_verification', 0);
            
            // Check if email already exists (double check)
            if (DB::table('users')->where('email', $email)->exists()) {
                $response['status'] = 'error';
                $response['msg'] = $language_name == 'french' ? "Identifiant de messagerie déjà enregistré" : 'Email id already registered';
            } else {
                // Create user
                $name = empty($lname) ? $fname : $fname . ' ' . $lname;
                
                $userId = DB::table('users')->insertGetId([
                    'fname' => $fname,
                    'lname' => $lname,
                    'name' => $name,
                    'email' => $email,
                    'password' => md5($password),
                    'email_verification' => $emailVerification,
                    'store_id' => config('store.main_store_id', 1),
                    'status' => 1,
                    'active_area' => '',
                    'created' => now(),
                    'updated' => now(),
                ]);
                
                if ($userId) {
                    $response['msg'] = $language_name == 'french' 
                        ? "Votre compte a été créé avec succès. Veuillez vérifier votre messagerie et vérifier votre identifiant de messagerie." 
                        : 'Your account has been created successfully. Please check your mail and verify email id.';
                    
                    // Get store data for email
                    $store = DB::table('stores')->where('id', config('store.main_store_id', 1))->first();
                    $verificationUrl = url('Logins/emailVerification/' . base64_encode($userId));
                    
                    // Send verification email
                    try {
                        if ($language_name == 'french') {
                            $subject = "vérification de l'E-mail";
                            $body = "<div style='margin-top: 25px;text-align: left;'><span style='font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;'>
                                {$name},<br>
                                Vous avez créé avec succès votre {$store->name} Compte. Veuillez cliquer sur le lien ci-dessous pour vérifier votre adresse e-mail et terminer votre inscription.
                            </span></div>
                            <div style='margin: 25px 0px;'><a style='font-size: 14px;text-transform: uppercase;color: #000;font-weight: 600;padding: 10px 30px;border-radius: 3px;border: none;background: #00a9d0;cursor: pointer;text-decoration: none;' href='{$verificationUrl}'>Visit Website</a></div>";
                        } else {
                            $subject = 'Email Verification';
                            $body = "<div style='margin-top: 25px;text-align: left;'><span style='font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;'>
                                {$name},<br>
                                You have Successfully created your {$store->name} account. Please click on the link below to verify your email address and complete your registration.
                            </span></div>
                            <div style='margin: 25px 0px;'><a style='font-size: 14px;text-transform: uppercase;color: #000;font-weight: 600;padding: 10px 30px;border-radius: 3px;border: none;background: #00a9d0;cursor: pointer;text-decoration: none;' href='{$verificationUrl}'>Visit Website</a></div>";
                        }
                        
                        \Mail::send([], [], function($mail) use ($email, $subject, $body, $store) {
                            $mail->to($email)
                                 ->subject($subject)
                                 ->from($store->from_email ?? 'info@printing.coop', $store->name ?? 'Printing Coop')
                                 ->html($body);
                        });
                    } catch (\Exception $e) {
                        // Email sending failed, but account is created
                    }
                }
            }
        }
        
        echo json_encode($response);
    }
    
    /**
     * Verify email address
     * CI: Logins->emailVerification() lines 259-279
     */
    public function emailVerification($id)
    {
        $userId = base64_decode($id);
        $language_name = config('store.language_name', 'english');
        
        $user = DB::table('users')->where('id', $userId)->first();
        
        if ($user) {
            DB::table('users')
                ->where('id', $userId)
                ->update(['email_verification' => 1]);
            
            $message = $language_name == 'french' 
                ? 'Votre e-mail a été vérifié avec succès' 
                : 'Your email has been verified successfully';
            
            return redirect('Logins')->with('success', $message);
        }
        
        return redirect('Logins')->with('error', 'Invalid verification link');
    }
    
    /**
     * Logout user
     * CI: Logins->logout() lines 281-285
     */
    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
    
    /**
     * Display forgot password page
     * CI: Logins->forgotPassword() lines 538-542
     */
    public function forgotPassword()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => 'Forgot Password',
            'language_name' => $language_name,
        ];
        
        return view('logins.forgot_password', $data);
    }
    
    /**
     * Send OTP to email for password reset
     * CI: Logins->sendOtp() lines 544-622
     */
    public function sendOtp(Request $request)
    {
        $email = $request->input('mobile');
        $json = ['status' => 0, 'msg' => ''];
        
        // Check if email exists
        $user = DB::table('users')->where('email', $email)->first();
        
        if ($user) {
            // Generate OTP
            $otp = rand(100000, 999999);
            
            // Get store data
            $store = DB::table('stores')->where('id', config('store.main_store_id', 1))->first();
            $language_name = config('store.language_name', 'english');
            
            // Prepare email content
            if ($language_name == 'french') {
                $message = $otp . ' est le code de réinitialisation du mot de passe.code est confidentiel, veuillez ne pas partager ce code avec qui que ce soit pour assurer la sécurité des comptes';
                $subject = 'réinitialiser le mot de passe';
            } else {
                $message = $otp . ' is reset password code.code is confidential, Please do not share this code with anyone to ensure accounts security';
                $subject = 'Reset Password';
            }
            
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;">
                        <span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                            ' . $message . '
                        </span>
                    </div>';
            
            // Send email (you'll need to implement email sending)
            try {
                \Mail::raw($message, function($mail) use ($email, $subject, $store) {
                    $mail->to($email)
                         ->subject($subject)
                         ->from($store->from_email ?? 'info@printing.coop', $store->name ?? 'Printing Coop');
                });
                
                $json['status'] = 1;
                $json['otp'] = $otp;
                
                if ($language_name == 'french') {
                    $json['msg'] = 'Veuillez vérifier que votre code de mot de passe de réinitialisation de messagerie a été envoyé à votre identifiant de messagerie: ' . $email;
                } else {
                    $json['msg'] = 'Please check your mail reset password code has been sent to your email id: ' . $email;
                }
            } catch (\Exception $e) {
                $json['msg'] = 'Error sending email. Please try again.';
            }
        } else {
            if (config('store.language_name', 'english') == 'french') {
                $json['msg'] = "L'identifiant de messagerie n'existe pas";
            } else {
                $json['msg'] = 'Email id does not exist';
            }
        }
        
        echo json_encode($json);
    }
    
    /**
     * Reset password with OTP
     * CI: MyAccounts->saveChangePassword() lines 99-120
     */
    public function resetPassword(Request $request)
    {
        $email = $request->input('email');
        $newPassword = $request->input('password');
        $inputOtp = $request->input('otp');
        $language_name = config('store.language_name', 'english');
        
        $json = ['status' => 0, 'msg' => ''];
        
        // Check if user exists
        $user = DB::table('users')->where('email', $email)->first();
        
        if ($user) {
            // Update password
            DB::table('users')
                ->where('email', $email)
                ->update([
                    'password' => md5($newPassword),
                    'updated_at' => now(),
                ]);
            
            $json['status'] = 1;
            $json['msg'] = $language_name == 'french' 
                ? 'Votre mot de passe a été mis à jour avec succès.' 
                : 'Your password has been updated successfully.';
        } else {
            $json['msg'] = $language_name == 'french' 
                ? 'Email introuvable' 
                : 'Email not found';
        }
        
        echo json_encode($json);
    }
    
    /**
     * Preferred customer signup
     * CI: Logins->preferred_customer_signup() lines 267-487
     */
    public function preferred_customer_signup(Request $request)
    {
        $language_name = config('store.language_name', 'english');
        
        // Validation rules (CI lines 274-275)
        $rules = $language_name == 'french' ? [
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'email' => 'required|email|max:50',
            'password' => 'required|min:8|max:20',
        ] : [
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'email' => 'required|email|max:50',
            'password' => 'required|min:8|max:20',
        ];
        
        // Custom error messages (CI lines 96-160)
        $messages = $language_name == 'french' ? [
            'fname.required' => 'entrez votre prénom',
            'lname.required' => 'Entrer le nom de famille',
            'email.required' => "Entrez l'identifiant de l'e-mail",
            'email.email' => "Entrez une adresse e-mail valide",
            'password.required' => 'Entrer le mot de passe',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
        ] : [
            'fname.required' => 'Enter first name',
            'lname.required' => 'Enter Last name',
            'email.required' => 'Enter email id',
            'email.email' => 'Enter a valid email address',
            'password.required' => 'Enter Password',
            'password.min' => 'Password must be at least 8 characters',
        ];
        
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];
        
        $validator = validator($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['errors'] = $validator->errors()->toArray();
        } else {
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');
            $password = $request->input('password');
            $email_verification = $request->input('email_verification', 0);
            
            // Check if email already exists (CI lines 292-295)
            $existingUser = DB::table('users')->where('email', $email)->first();
            if ($existingUser && false) { // CI has && false which means this check is disabled
                $response['status'] = 'error';
                $response['msg'] = $language_name == 'french' 
                    ? 'Identifiant de messagerie déjà enregistré' 
                    : 'Email id already registered';
            } else {
                // Prepare user data (CI lines 297-322)
                $postData = [
                    'fname' => $fname,
                    'lname' => $lname,
                    'email' => $email,
                    'password' => md5($password),
                    'email_verification' => $email_verification,
                    'mobile' => $request->input('mobile'),
                    'company_name' => $request->input('company_name'),
                    'responsible_name' => $request->input('responsible_name'),
                    'cp' => $request->input('cp'),
                    'active_area' => $request->input('active_area'),
                    'address' => $request->input('address'),
                    'country' => $request->input('country'),
                    'region' => $request->input('region'),
                    'city' => $request->input('city'),
                    'zip_code' => $request->input('zip_code'),
                    'request' => $request->input('request'),
                    'user_type' => 2, // Preferred customer
                    'store_id' => config('store.main_store_id', 1),
                ];
                
                // Create full name (CI lines 318-322)
                if (empty($postData['lname'])) {
                    $postData['name'] = $postData['fname'];
                } else {
                    $postData['name'] = $postData['fname'] . ' ' . $postData['lname'];
                }
                
                $postData['created'] = date('Y-m-d H:i:s');
                $postData['updated'] = date('Y-m-d H:i:s');
                
                // Save user (CI line 324)
                $insert_id = DB::table('users')->insertGetId($postData);
                
                if ($insert_id) {
                    $response['msg'] = $language_name == 'french' 
                        ? 'Le client préféré de votre compte a été créé avec succès. Veuillez vérifier votre messagerie et vérifier votre identifiant de messagerie.' 
                        : 'Your account preferred customer has been created successfully. Please check your mail and verify email id.';
                    
                    // Send emails (CI lines 329-477)
                    $this->sendPreferredCustomerEmails($insert_id, $postData, $language_name);
                } else {
                    $response['status'] = 'error';
                    $response['msg'] = $language_name == 'french' 
                        ? 'Problème technique, veuillez essayer après un certain temps' 
                        : 'Technical problem please try after some time';
                }
            }
        }
        
        echo json_encode($response);
    }
    
    /**
     * Send emails for preferred customer signup
     * CI: Logins->preferred_customer_signup() lines 329-477
     */
    private function sendPreferredCustomerEmails($insert_id, $postData, $language_name)
    {
        $toEmail = $postData['email'];
        
        // Get store data
        $storeData = $this->getStoreData();
        $store_url = $storeData['url'] ?? url('/');
        $store_phone = $storeData['phone'] ?? '';
        $from_name = $storeData['name'] ?? 'Printing Coop';
        $from_email = $storeData['from_email'] ?? env('FROM_EMAIL', 'info@printing.coop');
        $admin_email1 = $storeData['admin_email1'] ?? '';
        $admin_email2 = $storeData['admin_email2'] ?? '';
        $admin_email3 = $storeData['admin_email3'] ?? '';
        
        $verificationUrl = url('Logins/emailVerification/' . base64_encode($insert_id));
        
        if ($language_name == 'french') {
            // French emails (CI lines 340-376)
            $subject = "vérification de l'E-mail";
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                ' . htmlspecialchars($postData['name']) . ',
                <br>
                Vous avez créé avec succès votre ' . htmlspecialchars($from_name) . ' Compte. Veuillez cliquer sur le lien ci-dessous pour vérifier votre adresse e-mail et terminer votre inscription.
            </span>
        </div>
        <div style="margin: 25px 0px;"><a style="font-size: 14px;text-transform: uppercase;color: #000;font-weight: 600;padding: 10px 30px;border-radius: 3px;border: none;background: #00a9d0;cursor: pointer;text-decoration: none;" href="' . $verificationUrl . '">Visit Website</a>
        </div><div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">' . "si vous n'avez pas créé de " . $from_name . " compte, veuillez ignorer ce message <br> et assurez-vous que votre compte de messagerie est sécurisé. </span></div>";
            
            $body = $this->emailTemplate($subject, $body);
            $this->sendEmail($toEmail, $subject, $body, $from_email, $from_name);
            
            $subject = 'Bienvenue !';
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                ' . htmlspecialchars($postData['name']) . ',
                <br>
                Merci de vous être inscrit avec ' . htmlspecialchars($from_name) . ' Nous espérons que vous apprécierez votre temps avec nous.
            </span>
        </div>';
            
            $body = $this->emailTemplate($subject, $body);
            $this->sendEmail($toEmail, $subject, $body, $from_email, $from_name);
            
            // Send to admin emails
            if (!empty($admin_email1)) {
                $this->sendEmail($admin_email1, $subject, $body, $from_email, $from_name);
            }
            if (!empty($admin_email2)) {
                $this->sendEmail($admin_email2, $subject, $body, $from_email, $from_name);
            }
            if (!empty($admin_email3)) {
                $this->sendEmail($admin_email3, $subject, $body, $from_email, $from_name);
            }
            
            // Account awaiting approval email (CI lines 378-408)
            $subject = "Votre compte $from_name est en attente d'approbation";
            $body = "<div class='top-info' style='margin-top: 25px;text-align: left;'><span style='font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;'>
                Votre compte ".htmlspecialchars($from_name)." est en attente d'approbation<br>
                à Mehdi</br>

                ".htmlspecialchars($from_name)." est une imprimerie coopérative et sociale. Toutes les nouvelles inscriptions
                doivent subir un processus d'examen standard pour approbation. Vous recevrez un autre
                email une fois que nous aurons terminé notre examen, et une fois que vous serez approuvé,
                des instructions supplémentaires pour accéder à votre compte. Si vous avez des questions
                ou des préoccupations, n'hésitez pas à nous contacter au ".htmlspecialchars($store_phone)."<br><br>

                Nos heures d'ouverture sont: du lundi au vendredi de 9h à 18h). Nous sommes fermés le
                samedi et le dimanche.<br><br>
                Merci de l'intérêt que vous portez à ".htmlspecialchars($from_name)."
                </span>
                <div><h1>MERCI!</h1></div>
                </div>";
            
            $body = $this->emailTemplate($subject, $body);
            $this->sendEmail($toEmail, $subject, $body, $from_email, $from_name);
        } else {
            // English emails (CI lines 409-477)
            $subject = 'Email Verification';
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                ' . htmlspecialchars($postData['name']) . ',
                <br>
                You have Successfully created your ' . htmlspecialchars($from_name) . ' account. Please click on the link bellow to verify your email address and complete your registration.
            </span>
        </div>
        <div style="margin: 25px 0px;"><a style="font-size: 14px;text-transform: uppercase;color: #000;font-weight: 600;padding: 10px 30px;border-radius: 3px;border: none;background: #00a9d0;cursor: pointer;text-decoration: none;" href="' . $verificationUrl . '">Visit Website</a>
        </div><div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">if you havent created a  ' . htmlspecialchars($from_name) . ' account, Please ignore this<br> message and make sure that your email account is secure. </span></div>';
            
            $body = $this->emailTemplate($subject, $body);
            $this->sendEmail($toEmail, $subject, $body, $from_email, $from_name);
            
            $subject = 'Welcome !';
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">
                ' . htmlspecialchars($postData['name']) . ',
                <br>
                Thank you for signing up with ' . htmlspecialchars($from_name) . ' We hope you enjoy your time with us.
            </span>
        </div>';
            
            $body = $this->emailTemplate($subject, $body);
            $this->sendEmail($toEmail, $subject, $body, $from_email, $from_name);
            
            // Send to admin emails
            if (!empty($admin_email1)) {
                $this->sendEmail($admin_email1, $subject, $body, $from_email, $from_name);
            }
            if (!empty($admin_email2)) {
                $this->sendEmail($admin_email2, $subject, $body, $from_email, $from_name);
            }
            if (!empty($admin_email3)) {
                $this->sendEmail($admin_email3, $subject, $body, $from_email, $from_name);
            }
            
            // Account awaiting approval email (CI lines 456-477)
            $subject = "Your $from_name Account is Awaiting Approval";
            $body = "<div class='top-info' style='margin-top: 25px;text-align: left;'><span style='font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;'>
                Your ".htmlspecialchars($from_name)." account is pending approval<br>
                <br>

                to Mehdi</br>

                ".htmlspecialchars($from_name)." is a cooperative and social printing. All new sign ups must undergo a standard review process for approval. You will receive another
                email once we have completed our review, and once you're approved, further
                instructions for accessing your account. If you have any questions or
                concerns, please do not hesitate to contact us at ".htmlspecialchars($store_phone)."<br><br>

                Our hours of operation are : Monday - Friday 9AM - 6pmdnight). We are
                closed on Saturday and Sunday.<br><br>
                Thank you for your interest in ".htmlspecialchars($from_name)."
                </span>
                <div><h1>THANK YOU!</h1></div>
                </div>";
            
            $body = $this->emailTemplate($subject, $body);
            $this->sendEmail($toEmail, $subject, $body, $from_email, $from_name);
        }
    }
    
    /**
     * Get store data helper
     */
    private function getStoreData()
    {
        return [
            'url' => config('app.url'),
            'phone' => config('store.phone', '514-544-8043'),
            'name' => config('store.name', 'Printing Coop'),
            'from_email' => config('mail.from.address', env('FROM_EMAIL', 'info@printing.coop')),
            'admin_email1' => env('ADMIN_EMAIL1', ''),
            'admin_email2' => env('ADMIN_EMAIL2', ''),
            'admin_email3' => env('ADMIN_EMAIL3', ''),
        ];
    }
    
    /**
     * Send email helper
     */
    private function sendEmail($toEmail, $subject, $body, $fromEmail = null, $fromName = null)
    {
        try {
            $fromEmail = $fromEmail ?? config('mail.from.address');
            $fromName = $fromName ?? config('mail.from.name');
            
            \Mail::raw($body, function ($message) use ($toEmail, $subject, $fromEmail, $fromName) {
                $message->to($toEmail)
                    ->subject($subject)
                    ->from($fromEmail, $fromName);
            });
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Email send error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Email template helper
     */
    private function emailTemplate($subject, $body)
    {
        return '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            <h2 style="color: #00a9d0;">' . $subject . '</h2>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                ' . $body . '
            </div>
        </div>';
    }
}
