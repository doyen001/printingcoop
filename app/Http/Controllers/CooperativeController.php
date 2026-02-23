<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * CooperativeController - About/Cooperative page
 * CI: application/controllers/Cooperative.php (20 lines)
 */
class CooperativeController extends Controller
{
    /**
     * Display cooperative/about page
     * CI: lines 14-18
     */
    public function index()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'La Coopérative' : 'The Cooperative',
        ];
        
        return view('cooperative.index', $data);
    }
}
