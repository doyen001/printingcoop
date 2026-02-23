<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Check mobile/email by AJAX (lines 91-142)
     */
    public function checkMobileByAjax(Request $request)
    {
        $json = ['status' => 0, 'msg' => ''];
        
        if ($request->isMethod('post')) {
            $email = $request->input('ck_moblie_number');
            
            if (User::where('email', $email)->exists()) {
                $json['status'] = 1;
                $json['login'] = 1;
            } else {
                $json['status'] = 1;
                $json['login'] = 2;
                $otp = $this->getOtp();
                $json['otp'] = $otp;
                
                $StoreData = config('store.main_store_data');
                $store_url = $StoreData['url'];
                $from_name = $StoreData['name'];
                $from_email = $StoreData['from_email'];
                $language = config('store.language_name');
                
                if ($language == 'French') {
                    $massage = $otp . " Le code de vérification de l'e-mail pour signup.code est confidentiel, veuillez ne pas partager ce code avec qui que ce soit pour assurer la sécurité des comptes";
                    $subject = "Code de vérification de l'e-mail";
                    $json['msg'] = 'Veuillez vérifier que le code de vérification de votre e-mail a été envoyé à votre identifiant de messagerie';
                } else {
                    $massage = $otp . ' is email verification code for signup.code is confidential, Please do not share this code with anyone to ensure accounts security';
                    $subject = 'Email Verification Code';
                    $json['msg'] = 'Please check your mail email verification code has been sent to your email id';
                }
                
                $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">' . $massage . '</span></div>';
                
                // Send email (implement sendEmail helper)
                // sendEmail($email, $subject, $body, $from_email, $from_name);
            }
        }
        
        return response()->json($json);
    }
    
    /**
     * Regular user signup (lines 144-259)
     */
    public function signup(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect('/');
        }
        
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];
        
        $language = config('store.language_name');
        
        // Validation rules (lines 151-152)
        $rules = $language == 'French' ? $this->getConfigFrench() : $this->getConfig();
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['errors'] = $validator->errors()->toArray();
        } else {
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');
            $password = $request->input('password');
            $email_verification = $request->input('email_verification', 0);
            
            if (User::where('email', $email)->exists()) {
                $response['status'] = 'error';
                $response['msg'] = $language == 'French' 
                    ? "Identifiant de messagerie déjà enregistré" 
                    : 'Email id already registered1';
            } else {
                $postData = [
                    'fname' => $fname,
                    'lname' => $lname,
                    'email' => $email,
                    'password' => md5($password), // Line 177: MD5 hashing
                    'email_verification' => $email_verification,
                    'store_id' => config('store.main_store_id'),
                ];
                
                // Set name (lines 180-184)
                if (empty($postData['lname'])) {
                    $postData['name'] = $postData['fname'];
                } else {
                    $postData['name'] = $postData['fname'] . ' ' . $postData['lname'];
                }
                
                $user = User::create($postData);
                $insert_id = $user->id;
                
                if ($insert_id) {
                    $response['msg'] = $language == 'French' 
                        ? "Votre compte a été créé avec succès. Veuillez vérifier votre messagerie et vérifier votre identifiant de messagerie." 
                        : 'Your account has been created successfully. Please check your mail and verify email id.';
                    
                    $this->sendVerificationEmails($insert_id, $postData, $email, $language);
                } else {
                    $response['status'] = 'error';
                    $response['msg'] = $language == 'French' 
                        ? "Problème technique, veuillez essayer après un certain temps" 
                        : 'Technical problem please try after some time';
                }
            }
        }
        
        return response()->json($response);
    }
    
    /**
     * Preferred customer signup (lines 261-472)
     */
    public function preferredCustomerSignup(Request $request)
    {
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];
        
        $language = config('store.language_name');
        
        // Validation rules (lines 268)
        $rules = $language == 'French' ? $this->getPrefConfigFrench() : $this->getPrefConfig();
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['errors'] = $validator->errors()->toArray();
        } else {
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');
            $password = $request->input('password');
            $email_verification = $request->input('email_verification', 0);
            
            if (User::where('email', $email)->exists()) {
                $response['status'] = 'error';
                $response['msg'] = $language == 'French' 
                    ? 'Identifiant de messagerie déjà enregistré' 
                    : 'Email id already registered';
            } else {
                $postData = [
                    'fname' => $fname,
                    'lname' => $lname,
                    'email' => $email,
                    'password' => md5($password), // Line 295: MD5 hashing
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
                    'user_type' => 2, // Line 308: Preferred customer
                    'store_id' => config('store.main_store_id'),
                ];
                
                // Set name (lines 311-315)
                if (empty($postData['lname'])) {
                    $postData['name'] = $postData['fname'];
                } else {
                    $postData['name'] = $postData['fname'] . ' ' . $postData['lname'];
                }
                
                $user = User::create($postData);
                $insert_id = $user->id;
                
                if ($insert_id) {
                    $response['msg'] = $language == 'French' 
                        ? 'Le client préféré de votre compte a été créé avec succès. Veuillez vérifier votre messagerie et vérifier votre identifiant de messagerie.' 
                        : 'Your account preferred customer has been created successfully. Please check your mail and verify email id.';
                    
                    $this->sendPreferredCustomerEmails($insert_id, $postData, $email, $language);
                } else {
                    $response['status'] = 'error';
                    $response['msg'] = $language == 'French' 
                        ? 'Problème technique, veuillez essayer après un certain temps' 
                        : 'Technical problem please try after some time';
                }
            }
        }
        
        return response()->json($response, JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Send verification emails for regular signup (lines 191-250)
     */
    private function sendVerificationEmails($insert_id, $postData, $email, $language)
    {
        $StoreData = config('store.main_store_data');
        $store_url = $StoreData['url'];
        $from_name = $StoreData['name'];
        $from_email = $StoreData['from_email'];
        
        $url = $store_url . 'Logins/emailVerification/' . base64_encode($insert_id);
        
        if ($language == 'French') {
            $subject = "vérification de l'E-mail";
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">' . $postData['name'] . ',<br>Vous avez créé avec succès votre ' . $from_name . ' Compte. Veuillez cliquer sur le lien ci-dessous pour vérifier votre adresse e-mail et terminer votre inscription.</span></div><div style="margin: 25px 0px;"><a style="font-size: 14px;text-transform: uppercase;color: #000;font-weight: 600;padding: 10px 30px;border-radius: 3px;border: none;background: #00a9d0;cursor: pointer;text-decoration: none;" href="' . $url . '">Visit Website</a></div><div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">si vous n\'avez pas créé de ' . $from_name . ' compte, veuillez ignorer ce message <br> et assurez-vous que votre compte de messagerie est sécurisé. </span></div>';
            
            // sendEmail($email, $subject, $body, $from_email, $from_name);
            
            $subject = 'Bienvenue !';
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">' . $postData['name'] . ',<br>Merci de vous être inscrit avec ' . $from_name . ' Nous espérons que vous apprécierez votre temps avec nous.</span></div>';
            
            // sendEmail($email, $subject, $body, $from_email, $from_name);
        } else {
            $subject = 'Email Verification';
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">' . $postData['name'] . ',<br>You have Successfully created your ' . $from_name . ' account. Please click on the link bellow to verify your email address and complete your registration.</span></div><div style="margin: 25px 0px;"><a style="font-size: 14px;text-transform: uppercase;color: #000;font-weight: 600;padding: 10px 30px;border-radius: 3px;border: none;background: #00a9d0;cursor: pointer;text-decoration: none;" href="' . $url . '">Visit Website</a></div><div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">if you havent created a  ' . $from_name . ' account, Please ignore this<br> message and make sure that your email account is secure. </span></div>';
            
            // sendEmail($email, $subject, $body, $from_email, $from_name);
            
            $subject = 'Welcome !';
            $body = '<div class="top-info" style="margin-top: 25px;text-align: left;"><span style="font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;">' . $postData['name'] . ',<br>Thank you for signing up with ' . $from_name . ' We hope you enjoy your time with us.</span></div>';
            
            // sendEmail($email, $subject, $body, $from_email, $from_name);
        }
    }
    
    /**
     * Send emails for preferred customer signup
     */
    private function sendPreferredCustomerEmails($insert_id, $postData, $email, $language)
    {
        $StoreData = config('store.main_store_data');
        $store_url = $StoreData['url'];
        $store_phone = $StoreData['phone'];
        $from_name = $StoreData['name'];
        $from_email = $StoreData['from_email'];
        $admin_email1 = $StoreData['admin_email1'];
        $admin_email2 = $StoreData['admin_email2'];
        $admin_email3 = $StoreData['admin_email3'];
        
        $url = $store_url . 'Logins/emailVerification/' . base64_encode($insert_id);
        
        // Send verification and welcome emails (same as regular signup)
        $this->sendVerificationEmails($insert_id, $postData, $email, $language);
        
        // Send awaiting approval email (lines 369-396 or 433-462)
        if ($language == 'French') {
            $subject = "Votre compte $from_name est en attente d'approbation";
            $body = "<div class='top-info' style='margin-top: 25px;text-align: left;'><span style='font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;'>Votre compte $from_name est en attente d'approbation<br>à Mehdi</br>$from_name est une imprimerie coopérative et sociale. Toutes les nouvelles inscriptions doivent subir un processus d'examen standard pour approbation. Vous recevrez un autre email une fois que nous aurons terminé notre examen, et une fois que vous serez approuvé, des instructions supplémentaires pour accéder à votre compte. Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter au $store_phone<br><br>Nos heures d'ouverture sont: du lundi au vendredi de 9h à 18h). Nous sommes fermés le samedi et le dimanche.<br><br>Merci de l'intérêt que vous portez à $from_name</span><div><h1>MERCI!</h1></div></div>";
        } else {
            $subject = "Your $from_name Account is Awaiting Approval";
            $body = "<div class='top-info' style='margin-top: 25px;text-align: left;'><span style='font-size: 17px; letter-spacing: 0.5px; line-height: 28px; word-spacing: 0.5px;'>Your $from_name account is pending approval<br><br>to Mehdi</br>$from_name is a cooperative and social printing. All new sign ups must undergo a standard review process for approval. You will receive another email once we have completed our review, and once you're approved, further instructions for accessing your account. If you have any questions or concerns, please do not hesitate to contact us at $store_phone<br><br>Our hours of operation are : Monday - Friday 9AM - 6pmdnight). We are closed on Saturday and Sunday.<br><br>Thank you for your interest in $from_name</span><div><h1>THANK YOU!</h1></div></div>";
        }
        
        // sendEmail($email, $subject, $body, $from_email, $from_name);
    }
    
    /**
     * Validation rules - English (lines 7-46)
     */
    private function getConfig()
    {
        return [
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password',
        ];
    }
    
    /**
     * Validation rules - French (lines 48-90)
     */
    private function getConfigFrench()
    {
        return [
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password',
        ];
    }
    
    /**
     * Preferred customer validation rules - English
     */
    private function getPrefConfig()
    {
        return [
            'fname' => 'required|max:50',
            'lname' => 'required|max:50',
            'email' => 'required|email|max:50|unique:users,email',
            'password' => 'required|min:8|max:20',
            'confirm_password' => 'required|same:password',
            'mobile' => 'nullable|max:50',
            'company_name' => 'nullable|max:250',
            'responsible_name' => 'nullable|max:250',
            'cp' => 'nullable|max:250',
            'active_area' => 'required|max:250',
            'address' => 'nullable|max:250',
            'country' => 'nullable|max:250',
            'region' => 'nullable|max:250',
            'city' => 'nullable|max:250',
            'zip_code' => 'nullable|max:250',
            'request' => 'nullable|max:250',
        ];
    }
    
    /**
     * Preferred customer validation rules - French
     */
    private function getPrefConfigFrench()
    {
        return $this->getPrefConfig();
    }
    
    /**
     * Generate OTP
     */
    private function getOtp()
    {
        return rand(100000, 999999);
    }
}
