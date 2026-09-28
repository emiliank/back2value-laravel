<?php

namespace App\Http\Controllers;

use App\Services\SiteContentService;
use Illuminate\View\View;

class SustainabilityController extends Controller
{
    public function index(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        return view('sustainability.index', [
            'settings' => $content['settings'],
            'page' => $content['sustainability'],
            'activeNav' => 'sustainability',
        ]);
    }
}
