<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * FaqController - FAQ page
 * CI: application/controllers/Faq.php (20 lines)
 */
class FaqController extends Controller
{
    /**
     * Display FAQ page
     * CI: lines 14-18
     */
    public function index()
    {
        $language_name = config('store.language_name', 'english');
        $main_store_id = config('store.main_store_id', 1);
        
        $faqs = DB::table('faqs')
            ->where('status', 1)
            ->whereRaw("FIND_IN_SET(?, store_id)", [$main_store_id])
            ->orderBy('show_order')
            ->get();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'FAQ' : 'FAQ',
            'faqs' => $faqs,
        ];
        
        return view('faq.index', $data);
    }
}
