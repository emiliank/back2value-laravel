<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Content\SiteContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class SolutionsController extends Controller
{
    public function index(SiteContentRepository $content): Response
    {
        $page = $content->section('solutions');

        return Inertia::render('Public/Solutions', [
            'page' => $page,
            'applicationFilters' => $content->section('application_filters'),
            'activeNav' => 'solutions',
            'title' => $page['meta_title'] ?? 'Zgjidhjet | Back2Value',
            'description' => $page['meta_description'] ?? '',
        ]);
    }
}
