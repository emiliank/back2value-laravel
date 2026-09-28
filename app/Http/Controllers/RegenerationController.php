<?php

namespace App\Http\Controllers;

use App\Services\SiteContentService;
use Illuminate\View\View;

class RegenerationController extends Controller
{
    public function index(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        return view('regeneration.index', [
            'settings' => $content['settings'],
            'page' => $content['regeneration_page'],
            'activeNav' => 'regeneration',
        ]);
    }
}