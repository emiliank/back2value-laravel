<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use Illuminate\View\View;

class RegenerationController extends Controller
{
    public function index(SiteContentRepository $content): View
    {
        return view('regeneration.index', [
            'page' => $content->section('regeneration_page'),
            'activeNav' => 'regeneration',
        ]);
    }
}
