<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(SiteContentRepository $content): View
    {
        return view('landing', [
            // Products are kept off the homepage per client specifications and organized in /products
            'products' => [],
            'services' => $content->section('services'),
            'valueProps' => $content->section('value_props'),
            'diagnosticsTeaser' => $content->section('diagnostics_teaser'),
            'regenerationPage' => $content->section('regeneration_page'),
            'partners' => $content->section('partners'),
            'trust' => $content->section('trust'),
            'circularProcess' => $content->section('circular_process'),
            'about' => $content->section('about'),
            'stats' => SiteContentRepository::sorted($content->section('stats')['items'] ?? []),
            'activeNav' => 'home',
        ]);
    }
}
