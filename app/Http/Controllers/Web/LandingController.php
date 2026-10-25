<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Content\SiteContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class LandingController extends Controller
{
    public function index(SiteContentRepository $content): Response
    {
        return Inertia::render('Public/Home', [
            'services' => $content->section('services'),
            'valueProps' => $content->section('value_props'),
            'productCategories' => $content->section('product_categories'),
            'diagnosticsTeaser' => $content->section('diagnostics_teaser'),
            'regenerationPage' => $content->section('regeneration_page'),
            'partners' => $content->section('partners'),
            'trust' => $content->section('trust'),
            'circularProcess' => $content->section('circular_process'),
            'about' => $content->section('about'),
            'complianceBanner' => $content->section('compliance_banner'),
            'contactCards' => $content->section('contact_cards'),
            'stats' => SiteContentRepository::sorted($content->section('stats')['items'] ?? []),
            'activeNav' => 'home',
            'title' => $content->section('settings')['meta_title'] ?? 'Back2Value',
            'description' => $content->section('settings')['meta_description'] ?? '',
        ]);
    }
}
