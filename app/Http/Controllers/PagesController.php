<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * PagesController
 * CI: application/controllers/Pages.php
 */
class PagesController extends Controller
{
    /**
     * Display a page by slug
     * CI: Pages->index() lines 11-34
     */
    public function index($slug = null)
    {
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        
        $pageData = DB::table('pages')
            ->where('slug', $slug)
            ->where('main_store_id', $website_store_id)
            ->first();
        
        if (!$pageData) {
            return redirect('/');
        }
        
        // Set page title and meta tags (CI lines 17-26)
        $page_title = $pageData->title;
        $meta_page_title = $pageData->page_title ?? '';
        $meta_description_content = $pageData->meta_description_content ?? '';
        $meta_keywords_content = $pageData->meta_keywords_content ?? '';
        
        if ($language_name == 'french') {
            $page_title = $pageData->title_french ?? $pageData->title;
            $meta_page_title = $pageData->page_title_french ?? '';
            $meta_description_content = $pageData->meta_description_content_french ?? '';
            $meta_keywords_content = $pageData->meta_keywords_content_french ?? '';
        }
        
        $data = [
            'page_title' => $page_title,
            'meta_page_title' => $meta_page_title,
            'meta_description_content' => $meta_description_content,
            'meta_keywords_content' => $meta_keywords_content,
            'slug' => $pageData->slug,
            'pageData' => $pageData,
        ];
        
        return view('pages.index', $data);
    }
    
    /**
     * Display Contact Us page
     * CI: Pages->contactUs() lines 36-54
     */
    public function contactUs()
    {
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        
        $pageData = DB::table('pages')
            ->where('slug', 'contact-us')
            ->where('main_store_id', $website_store_id)
            ->first();
        
        if (!$pageData) {
            return redirect('/');
        }
        
        $page_title = $pageData->title;
        $meta_page_title = $pageData->page_title ?? '';
        $meta_description_content = $pageData->meta_description_content ?? '';
        $meta_keywords_content = $pageData->meta_keywords_content ?? '';
        
        if ($language_name == 'french') {
            $page_title = $pageData->title_french ?? $pageData->title;
            $meta_page_title = $pageData->page_title_french ?? '';
            $meta_description_content = $pageData->meta_description_content_french ?? '';
            $meta_keywords_content = $pageData->meta_keywords_content_french ?? '';
        }
        
        $data = [
            'page_title' => $page_title,
            'meta_page_title' => $meta_page_title,
            'meta_description_content' => $meta_description_content,
            'meta_keywords_content' => $meta_keywords_content,
            'slug' => $pageData->slug,
            'pageData' => $pageData,
        ];
        
        return view('pages.contact_us', $data);
    }
    
    /**
     * Display Preferred Customer page
     * CI: Pages->prefferedCustomer() lines 56-84
     */
    public function prefferedCustomer()
    {
        // Redirect if already logged in (CI lines 57-59)
        if (session('loginId')) {
            return redirect('MyOrders');
        }
        
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        
        $pageData = DB::table('pages')
            ->where('slug', 'preffered-customer')
            ->where('main_store_id', $website_store_id)
            ->first();
        
        if (!$pageData) {
            return redirect('/');
        }
        
        // Get countries for form (CI lines 64-65)
        $countries = DB::table('countries')->orderBy('name', 'asc')->get();
        $states = [];
        
        $page_title = $pageData->title;
        $meta_page_title = $pageData->page_title ?? '';
        $meta_description_content = $pageData->meta_description_content ?? '';
        $meta_keywords_content = $pageData->meta_keywords_content ?? '';
        
        if ($language_name == 'french') {
            $page_title = $pageData->title_french ?? $pageData->title;
            $meta_page_title = $pageData->page_title_french ?? '';
            $meta_description_content = $pageData->meta_description_content_french ?? '';
            $meta_keywords_content = $pageData->meta_keywords_content_french ?? '';
        }
        
        // Create captcha
        $cap = $this->create_capcha();
        session(['captcha_filename' => $cap['filename']]);
        
        $user_ip = request()->ip();
        if ($user_ip == '::1') {
            $user_ip = '127.0.0.1';
        }
        
        DB::table('captcha')->insert([
            'captcha_time' => $cap['time'],
            'ip_address' => $user_ip,
            'word' => $cap['word']
        ]);
        
        $data = [
            'page_title' => $page_title,
            'meta_page_title' => $meta_page_title,
            'meta_description_content' => $meta_description_content,
            'meta_keywords_content' => $meta_keywords_content,
            'slug' => $pageData->slug,
            'pageData' => $pageData,
            'countries' => $countries,
            'states' => $states,
            'postData' => (object)[],
            'language_name' => $language_name,
            'cap' => (object)$cap,
        ];
        
        return view('pages.preffered_customer', $data);
    }
    
    /**
     * Display Estimate page
     * CI: Pages->estimate() lines 100-146
     */
    public function estimate()
    {
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        
        // Create captcha (CI lines 103-120)
        $cap = $this->create_capcha();
        session(['captcha_filename' => $cap['filename']]);
        
        // Store captcha in database
        $user_ip = request()->ip();
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        DB::table('captcha')->insert([
            'captcha_time' => $cap['time'],
            'ip_address' => $user_ip,
            'word' => $cap['word']
        ]);
        
        $pageData = DB::table('pages')
            ->where('slug', 'estimate')
            ->where('main_store_id', $website_store_id)
            ->first();
        
        if (!$pageData) {
            return redirect('/');
        }
        
        // Get countries and states for form (CI lines 130-131)
        $countries = DB::table('countries')->orderBy('name', 'asc')->get();
        $states = [];
        
        $page_title = $pageData->title;
        $meta_page_title = $pageData->page_title ?? '';
        $meta_description_content = $pageData->meta_description_content ?? '';
        $meta_keywords_content = $pageData->meta_keywords_content ?? '';
        
        if ($language_name == 'french') {
            $page_title = $pageData->title_french ?? $pageData->title;
            $meta_page_title = $pageData->page_title_french ?? '';
            $meta_description_content = $pageData->meta_description_content_french ?? '';
            $meta_keywords_content = $pageData->meta_keywords_content_french ?? '';
        }
        
        $data = [
            'page_title' => $page_title,
            'meta_page_title' => $meta_page_title,
            'meta_description_content' => $meta_description_content,
            'meta_keywords_content' => $meta_keywords_content,
            'slug' => $pageData->slug,
            'pageData' => $pageData,
            'countries' => $countries,
            'states' => $states,
            'cap' => (object)$cap,
        ];
        
        return view('pages.estimate', $data);
    }
    
    /**
     * Create captcha
     * CI: Pages->create_capcha() lines 154-181
     */
    private function create_capcha()
    {
        $word = substr(str_shuffle("0123456789"), 0, 4);
        $img_path = public_path('assets/captcha/');
        $img_url = asset('assets/captcha/');
        
        // Create captcha directory if it doesn't exist
        if (!file_exists($img_path)) {
            mkdir($img_path, 0777, true);
        }
        
        // Generate captcha image
        $img_width = 150;
        $img_height = 50;
        $font_size = 20;
        
        $image = imagecreate($img_width, $img_height);
        
        // Colors
        $background = imagecolorallocate($image, 255, 255, 255);
        $border = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $grid_color = imagecolorallocate($image, 255, 40, 40);
        
        // Fill background
        imagefilledrectangle($image, 0, 0, $img_width, $img_height, $background);
        
        // Add border
        imagerectangle($image, 0, 0, $img_width - 1, $img_height - 1, $border);
        
        // Add grid lines
        for ($i = 0; $i < $img_width; $i += 10) {
            imageline($image, $i, 0, $i, $img_height, $grid_color);
        }
        for ($i = 0; $i < $img_height; $i += 10) {
            imageline($image, 0, $i, $img_width, $i, $grid_color);
        }
        
        // Add text with better positioning
        $x = 20;
        $y = 15;
        for ($i = 0; $i < strlen($word); $i++) {
            // Use font 5 which is the largest built-in font (9x15 pixels)
            imagestring($image, 5, $x + ($i * 30), $y + rand(-3, 3), $word[$i], $text_color);
        }
        
        // Save image
        $filename = time() . '.jpg';
        $filepath = $img_path . $filename;
        imagejpeg($image, $filepath);
        imagedestroy($image);
        
        $time = time();
        $img_tag = '<img src="' . $img_url . '/' . $filename . '" width="' . $img_width . '" height="' . $img_height . '" style="border:0;" alt=" " />';
        
        return [
            'word' => $word,
            'time' => $time,
            'image' => $img_tag,
            'filename' => $filename,
        ];
    }
    
    /**
     * Refresh captcha via AJAX
     * CI: Pages->load_refresh_capcha() lines 182-212
     */
    public function refreshCaptcha()
    {
        // Delete old captcha file
        $old_filename = session('captcha_filename');
        if ($old_filename) {
            $old_filepath = public_path('assets/captcha/' . $old_filename);
            if (file_exists($old_filepath) && is_file($old_filepath)) {
                unlink($old_filepath);
            }
        }
        
        // Create new captcha
        $cap = $this->create_capcha();
        session(['captcha_filename' => $cap['filename']]);
        
        // Store in database
        $user_ip = request()->ip();
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        DB::table('captcha')->insert([
            'captcha_time' => $cap['time'],
            'ip_address' => $user_ip,
            'word' => $cap['word']
        ]);
        
        return response()->json([
            'captcha' => $cap['image']
        ]);
    }
    
    /**
     * Display FAQ page
     * CI: Pages->faq() lines 109-127
     */
    public function faq()
    {
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        
        $pageData = DB::table('pages')
            ->where('slug', 'faq')
            ->where('main_store_id', $website_store_id)
            ->first();
        
        if (!$pageData) {
            return redirect('/');
        }
        
        $page_title = $pageData->title;
        $meta_page_title = $pageData->page_title ?? '';
        $meta_description_content = $pageData->meta_description_content ?? '';
        $meta_keywords_content = $pageData->meta_keywords_content ?? '';
        
        if ($language_name == 'french') {
            $page_title = $pageData->title_french ?? $pageData->title;
            $meta_page_title = $pageData->page_title_french ?? '';
            $meta_description_content = $pageData->meta_description_content_french ?? '';
            $meta_keywords_content = $pageData->meta_keywords_content_french ?? '';
        }
        
        $data = [
            'page_title' => $page_title,
            'meta_page_title' => $meta_page_title,
            'meta_description_content' => $meta_description_content,
            'meta_keywords_content' => $meta_keywords_content,
            'slug' => $pageData->slug,
            'pageData' => $pageData,
        ];
        
        return view('pages.faq', $data);
    }
    
    /**
     * Save Contact Us form submission
     * CI: Pages->saveContactUs() lines 129-179
     */
    public function saveContactUs(Request $request)
    {
        if (!$request->isMethod('post')) {
            return redirect('/');
        }
        
        $language_name = config('store.language_name', 'english');
        $main_store_id = config('store.main_store_id', 1);
        $main_store_data = config('store.main_store_data');
        
        $response = [
            'status' => 'success',
            'msg' => '',
            'errors' => [],
        ];

        // $response = Http::asForm()->post(
        //     'https://www.google.com/recaptcha/api/siteverify',
        //     [
        //         'secret' => config('services.recaptcha.secret_key'),
        //         'response' => $request->input('g-recaptcha-response'),
        //         'remoteip' => $request->ip(),
        //     ]
        // );
        
        // Validate form (CI lines 141-147)
        $validator = \Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|max:20',
            'comment' => 'required',
            'g-recaptcha-response' => 'required'
        ]);
        
        if ($validator->fails()) {
            $response['status'] = 'error';
            $response['errors'] = $validator->errors()->toArray();
        } else {
            // Save contact us data (CI lines 153-173)

            $captcha = $request->input('g-recaptcha-response');

            $verify = Http::asForm()->post(
                'https://www.google.com/recaptcha/api/siteverify',
                [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $captcha,
                    'remoteip' => $request->ip(),
                ]
            );

            $captchaResult = $verify->json();

            if (!isset($captchaResult['success']) || $captchaResult['success'] !== true) {
                $response['status'] = 'error';
                $response['msg'] = 'Captcha verification failed. Please try again.';
                
                echo json_encode($response);
                exit();
            }

            $data = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'comment' => $request->input('comment'),
                'store_id' => $main_store_id,
                'created' => date('Y-m-d H:i:s'),
            ];
            
            DB::table('contact_us')->insert($data);
            
            $from_name = $main_store_data['name'] ?? 'Store';
            $response['msg'] = "Thank you for contacting $from_name we have received your query our representative will get back to you within 24 hours";
            
            // Send email to admin (CI lines 163-168)
            $subject = 'Contact Us';
            $body = '<p>Recieved contact us request from: <br><br> Name:' . ucfirst($data['name']) . '<br><br>
                     Email: ' . $data['email'] . '<br><br> Phone: ' . $data['phone'] . '<br><br>Message: ' . $data['comment'] . ' </p>';
            
            // Send email to admin (CI lines 163-168)
            $adminEmail = env('ADMIN_EMAIL', 'info@printing.coop');
            $fromEmail = env('FROM_EMAIL', 'info@printing.coop');
            $fromName = $main_store_data['name'] ?? 'Printing Coop';
            
            // Apply email template (CI line 271)
            $body = emailTemplate($subject, $body);
            
            sendEmail($adminEmail, $subject, $body, $fromEmail, $fromName);
            
            if ($language_name == 'french') {
                $response['msg'] = "Merci d'avoir contacté $from_name nous avons reçu votre demande notre représentant vous répondra dans les 24 heures";
            }
        }
        
        echo json_encode($response);
        exit();
    }
    
    /**
     * Send email helper function
     * CI: constants.php line 959-980
     */
    private function sendEmail($toEmail, $subject, $body, $from = null, $fromName = null, $files = [])
    {
        try {
            $from = $from ?? config('mail.from.address');
            $fromName = $fromName ?? config('mail.from.name');
            
            \Mail::raw($body, function ($message) use ($toEmail, $subject, $from, $fromName, $files) {
                $message->to($toEmail)
                    ->subject($subject)
                    ->from($from, $fromName);
                    
                // Attach files if provided
                foreach ($files as $file) {
                    $message->attach($file);
                }
            });
            
            return true;
        } catch (\Exception $e) {
            \Log::error('Email send error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Email template helper function
     * CI: constants.php line 933-958
     */
    private function emailTemplate($subject, $body)
    {
        $main_store_id = config('store.main_store_id', 1);
        
        // Get logo images (CI line 936-943)
        $logoImages = $this->getLogoImages($main_store_id);
        $logoHtml = '';
        if (!empty($logoImages)) {
            foreach ($logoImages as $logo) {
                $logoHtml .= '<img src="' . asset('uploads/email_templates/' . $logo) . '" alt="Logo" style="max-width: 200px; margin-bottom: 20px;">';
            }
        }
        
        return '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
            ' . $logoHtml . '
            <h2 style="color: #00a9d0;">' . $subject . '</h2>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                ' . $body . '
            </div>
        </div>';
    }
    
    /**
     * Get logo images helper function
     * CI: constants.php line 944-958
     */
    private function getLogoImages($main_store_id)
    {
        $logos = DB::table('email_templates')
            ->where('store_id', $main_store_id)
            ->where('template_name', 'logo')
            ->pluck('image_name')
            ->toArray();
            
        return $logos;
    }
    
    /**
     * Display Privacy Policy page
     */
    public function privacyPolicy()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Politique de confidentialité' : 'Privacy Policy',
            'language_name' => $language_name,
        ];
        
        return view('pages.privacy_policy', $data);
    }
    
    /**
     * Display Terms of Use page
     */
    public function termsOfUse()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? "Conditions d'utilisation" : 'Terms of Use',
            'language_name' => $language_name,
        ];
        
        return view('pages.terms_of_use', $data);
    }
    
    /**
     * Display Interest-Based Advertising page
     */
    public function interestBasedAdvertising()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Publicité ciblée' : 'Interest-Based Advertising',
            'language_name' => $language_name,
        ];
        
        return view('pages.interest_based_advertising', $data);
    }
    
    /**
     * Display Estimate Submitted page
     * CI: Pages->estimate_submitted() lines 148-152
     */
    public function estimateSubmitted()
    {
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        
        $pageData = DB::table('pages')
            ->where('slug', 'estimate_submitted')
            ->where('main_store_id', $website_store_id)
            ->first();
        
        if (!$pageData) {
            return redirect('/');
        }
        
        $page_title = $pageData->title;
        $meta_page_title = $pageData->page_title ?? '';
        $meta_description_content = $pageData->meta_description_content ?? '';
        $meta_keywords_content = $pageData->meta_keywords_content ?? '';
        
        if ($language_name == 'french') {
            $page_title = $pageData->title_french ?? $pageData->title;
            $meta_page_title = $pageData->page_title_french ?? '';
            $meta_description_content = $pageData->meta_description_content_french ?? '';
            $meta_keywords_content = $pageData->meta_keywords_content_french ?? '';
        }
        
        $data = [
            'page_title' => $page_title,
            'meta_page_title' => $meta_page_title,
            'meta_description_content' => $meta_description_content,
            'meta_keywords_content' => $meta_keywords_content,
            'slug' => $pageData->slug,
            'pageData' => $pageData,
            'language_name' => $language_name,
        ];
        
        return view('pages.estimate_submitted', $data);
    }
    
    /**
     * Display sitemap page
     */
    public function sitemap()
    {
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        $main_store_id = config('store.main_store_id', 1);
        
        // Get main navigation pages
        $pages = DB::table('pages')
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get();
        
        // Get categories
        $categories = [];
        if (in_array($website_store_id, [1, 3])) {
            $categories['categories'] = DB::table('categories')
                ->where('status', 1)
                ->orderBy('id', 'asc')
                ->get();
            
            foreach ($categories['categories'] as &$category) {
                $category->sub_categories = DB::table('sub_categories')
                    ->where('category_id', $category->id)
                    ->where('status', 1)
                    ->orderBy('id', 'asc')
                    ->get();
            }
        }
        
        // Get tags for sections
        $proudly_display_your_brand_tags = DB::table('tags')
            ->where('status', 1)
            ->where('proudly_display_your_brand', 1)
            ->orderBy('id', 'asc')
            ->get();
            
        $montreal_book_printing_tags = DB::table('tags')
            ->where('status', 1)
            ->where('montreal_book_printing', 1)
            ->orderBy('id', 'asc')
            ->get();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Plan du site' : 'Sitemap',
            'meta_page_title' => $language_name == 'french' ? 'Plan du site' : 'Sitemap',
            'meta_description_content' => $language_name == 'french' 
                ? 'Plan du site de Printing - Navigation complète de toutes les pages et catégories' 
                : 'Printing Sitemap - Complete navigation of all pages and categories',
            'meta_keywords_content' => $language_name == 'french' 
                ? 'plan du site, navigation, pages, catégories, printing' 
                : 'sitemap, navigation, pages, categories, printing',
            'language_name' => $language_name,
            'pages' => $pages,
            'categories' => $categories,
            'proudly_display_your_brand_tags' => $proudly_display_your_brand_tags,
            'montreal_book_printing_tags' => $montreal_book_printing_tags,
        ];
        
        return view('pages.sitemap', $data);
    }
}
