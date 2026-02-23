<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * CategoriesController
 * CI: application/controllers/Categories.php
 */
class CategoriesController extends Controller
{
    public function index()
    {
        $language_name = config('store.language_name', 'english');
        $website_store_id = config('store.website_store_id', 1);
        
        $categories = DB::table('categories')
            ->where('status', 1)
            ->whereRaw("FIND_IN_SET(?, store_id)", [$website_store_id])
            ->orderBy('name', 'asc')
            ->get();
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Catégories' : 'Categories',
            'categories' => $categories,
        ];
        
        return view('categories.index', $data);
    }
}
