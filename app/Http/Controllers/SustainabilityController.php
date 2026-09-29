<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use Illuminate\View\View;

class SustainabilityController extends Controller
{
    public function index(SiteContentRepository $content): View
    {
        return view('sustainability.index', [
            'page' => $content->section('sustainability'),
            'activeNav' => 'sustainability',
        ]);
    }
}
