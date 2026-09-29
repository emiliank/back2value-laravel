<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function index(SiteContentRepository $content): View
    {
        return view('services.index', [
            'page' => $content->section('services_page'),
            'dropOffPoints' => $content->section('drop_off_points'),
            'activeNav' => 'services',
        ]);
    }
}
