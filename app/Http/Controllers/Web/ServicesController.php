<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Content\SiteContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class ServicesController extends Controller
{
    public function index(SiteContentRepository $content): Response
    {
        $page = $content->section('services_page');

        return Inertia::render('Public/Services', [
            'page' => $page,
            'dropOffPoints' => $content->section('drop_off_points'),
            'activeNav' => 'services',
            'title' => ($page['meta_title'] ?? 'Shërbimet Teknike | Back2Value').' | Back2Value',
            'description' => $page['meta_description'] ?? '',
        ]);
    }
}
