<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * AboutUsController
 * Maps to Pages controller with about-us slug
 * CI: Pages controller handles this
 */
class AboutUsController extends Controller
{
    public function index()
    {
        $pagesController = new PagesController();
        return $pagesController->index('about-us');
    }
}
