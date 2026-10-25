<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Content\SiteContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class SustainabilityController extends Controller
{
    public function index(SiteContentRepository $content): Response
    {
        $page = $content->section('sustainability');

        return Inertia::render('Public/Sustainability', [
            'page' => $page,
            'activeNav' => 'sustainability',
            'title' => $page['meta_title'] ?? 'Qëndrueshmëria | Back2Value',
            'description' => $page['meta_description'] ?? '',
        ]);
    }
}
