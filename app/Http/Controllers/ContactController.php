<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * ContactController
 * CI: application/controllers/Contact.php
 */
class ContactController extends Controller
{
    public function index()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Nous contacter' : 'Contact Us',
        ];
        
        return view('contact.index', $data);
    }
    
    public function submit(Request $request)
    {
        // TODO: Implement contact form submission
        return response()->json(['status' => 'success']);
    }
}
