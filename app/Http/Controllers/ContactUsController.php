<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * ContactUsController
 * Maps to Pages controller with contact-us slug
 * CI: Pages controller handles this
 */
class ContactUsController extends Controller
{
    public function index()
    {
        $pagesController = new PagesController();
        return $pagesController->index('contact-us');
    }
}
