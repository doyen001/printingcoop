<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * PrefferedCustomerController - Preferred customer page
 * CI: application/controllers/PrefferedCustomer.php (20 lines)
 */
class PrefferedCustomerController extends Controller
{
    /**
     * Display preferred customer page
     * CI: lines 14-18
     */
    public function index()
    {
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Client Préféré' : 'Preferred Customer',
        ];
        
        return view('preffered_customer.index', $data);
    }
}
