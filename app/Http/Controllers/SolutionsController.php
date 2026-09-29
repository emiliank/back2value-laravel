<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use Illuminate\View\View;

class SolutionsController extends Controller
{
    public function index(SiteContentRepository $content): View
    {
        return view('solutions.index', [
            'page' => $content->section('solutions'),
            'applicationFilters' => $content->section('application_filters'),
            'activeNav' => 'solutions',
        ]);
    }
}
