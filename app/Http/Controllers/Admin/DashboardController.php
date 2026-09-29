<?php

namespace App\Http\Controllers\Admin;

use App\Content\ContentSchema;
use App\Content\SiteContentRepository;
use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private SiteContentRepository $repository) {}

    public function __invoke(): View
    {
        $content = $this->repository->all();
        $pages = ContentSchema::pages();
        $sections = ContentSchema::sections();

        $groups = [];
        $pageOfSection = [];

        foreach ($pages as $slug => $page) {
            $groups[$page['group']][$slug] = $page;

            foreach ($page['sections'] as $section) {
                $pageOfSection[$section] = $slug;
            }
        }

        $customised = [];

        foreach (array_keys($sections) as $key) {
            $customised[$key] = SiteContent::query()->where('section', $key)->exists();
        }

        return view('admin.dashboard', [
            'groups' => $groups,
            'sections' => $sections,
            'content' => $content,
            'customised' => $customised,
            'pageOfSection' => $pageOfSection,
            'totalPages' => count($pages),
            'totalSections' => count($sections),
            'totalCustomised' => count(array_filter($customised)),
        ]);
    }
}
