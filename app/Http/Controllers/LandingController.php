<?php

namespace App\Http\Controllers;

use App\Services\SiteContentService;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(SiteContentService $siteContent): View
    {
        return view('landing', $siteContent->all());
    }
}
