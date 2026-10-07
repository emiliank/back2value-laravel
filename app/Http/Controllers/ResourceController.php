<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(SiteContentRepository $content): View
    {
        return view('resources.index', [
            'about' => $content->section('about'),
            'stats' => SiteContentRepository::sorted($content->section('stats')['items'] ?? []),
            'trust' => $content->section('trust'),
            'activeNav' => 'resources',
        ]);
    }
}
