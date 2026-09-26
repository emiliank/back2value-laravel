<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SiteContentService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(SiteContentService $siteContent): View
    {
        $content = $siteContent->all();

        return view('admin.dashboard', [
            'activeSection' => 'dashboard',
            'counts' => [
                'products' => count($content['products']),
                'services' => count($content['services']),
                'stats' => count($content['stats']),
                'images' => count(array_filter($content['products'], fn (array $product): bool => filled($product['image'] ?? null))),
            ],
            'settings' => $content['settings'],
        ]);
    }
}
