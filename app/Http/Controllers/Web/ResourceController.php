<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use App\Content\SiteContentRepository;
use Inertia\Inertia;
use Inertia\Response;

class ResourceController extends Controller
{
    public function index(SiteContentRepository $content): Response
    {
        $about = $content->section('about');
        $certificatesSection = $content->section('certificates');

        return Inertia::render('Public/Resources', [
            'about' => $about,
            'stats' => SiteContentRepository::sorted($content->section('stats')['items'] ?? []),
            'certificates' => SiteContentRepository::sorted($certificatesSection['items'] ?? []),
            'certificatesEyebrow' => $certificatesSection['eyebrow'] ?? null,
            'certificatesTitle' => $certificatesSection['title'] ?? null,
            'certificatesDescription' => $certificatesSection['description'] ?? null,
            'trust' => $content->section('trust'),
            'activeNav' => 'resources',
            'title' => $about['title'] ?? 'Rreth nesh | Back2Value',
            'description' => $about['description'] ?? '',
        ]);
    }
}
