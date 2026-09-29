<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use Illuminate\View\View;

class ResourceController extends Controller
{
    public function index(SiteContentRepository $content): View
    {
        return view('resources.index', [
            'page' => $content->section('resources'),
            'activeNav' => 'resources',
        ]);
    }
}
