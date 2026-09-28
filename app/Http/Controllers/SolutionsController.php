<?php

namespace App\Http\Controllers;

use App\Services\SiteContentService;
use Illuminate\View\View;

class SolutionsController extends Controller
{
    public function index(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        return view('solutions.index', [
            'settings' => $content['settings'],
            'page' => $content['solutions'],
            'applicationFilters' => $content['application_filters'],
            'activeNav' => 'solutions',
        ]);
    }
}
