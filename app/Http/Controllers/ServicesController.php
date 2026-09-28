<?php

namespace App\Http\Controllers;

use App\Services\SiteContentService;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function index(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        return view('services.index', [
            'settings' => $content['settings'],
            'page' => $content['services_page'],
            'dropOffPoints' => $content['drop_off_points'],
            'activeNav' => 'services',
        ]);
    }
}
