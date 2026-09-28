<?php

namespace App\Http\Controllers;

use App\Services\SiteContentService;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        return view('resources.index', [
            'settings' => $content['settings'],
            'page' => $content['resources'],
            'activeNav' => 'resources',
        ]);
    }
}
