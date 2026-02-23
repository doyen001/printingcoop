<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * PrivacyController - Privacy policy page
 * CI: application/controllers/Privacy.php (20 lines)
 */
class PrivacyController extends Controller
{
    /**
     * Display privacy policy page
     * CI: lines 14-18
     */
    public function index()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Politique de confidentialité' : 'Privacy Policy',
        ];
        
        return view('privacy.index', $data);
    }
}
