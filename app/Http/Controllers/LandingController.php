<?php

namespace App\Http\Controllers;

use App\Services\SiteContentService;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        // Products are kept off the homepage per client specifications and organized in /products
        $content['products'] = [];

        return view('landing', $content);
    }
}
