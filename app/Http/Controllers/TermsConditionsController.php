<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * TermsConditionsController - Terms & Conditions page
 * CI: application/controllers/TermsConditions.php (20 lines)
 */
class TermsConditionsController extends Controller
{
    /**
     * Display terms and conditions page
     * CI: lines 14-18
     */
    public function index()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Termes et Conditions' : 'Terms & Conditions',
        ];
        
        return view('terms_conditions.index', $data);
    }
}
