<?php

namespace App\Http\Controllers;

use App\Content\SiteContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class RegenerationController extends Controller
{
    public function index(SiteContentRepository $content): Response
    {
        $page = $content->section('regeneration_page');

        return Inertia::render('Public/Regeneration', [
            'page' => $page,
            'activeNav' => 'regeneration',
            'title' => $page['meta_title'] ?? 'Rigjenerimi | Back2Value',
            'description' => $page['meta_description'] ?? '',
        ]);
    }
}
