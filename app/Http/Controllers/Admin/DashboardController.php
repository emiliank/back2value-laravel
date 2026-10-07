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
        $locale = (string) config('locales.default', 'sq');
        $locales = (array) config('locales.available', []);

        $content = $this->repository->all($locale);
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

        $storedRows = SiteContent::query()
            ->whereIn('locale', array_keys($locales))
            ->get(['section', 'locale']);

        $customised = [];

        foreach (array_keys($sections) as $key) {
            // A section counts as customised once any language overrides it.
            $customised[$key] = $storedRows->contains('section', $key);
        }

        return view('admin.dashboard', [
            'groups' => $groups,
            'sections' => $sections,
            'content' => $content,
            'customised' => $customised,
            'pageOfSection' => $pageOfSection,
            'locales' => $locales,
            'locale' => $locale,
            'totalPages' => count($pages),
            'totalSections' => count($sections),
            'totalCustomised' => count(array_filter($customised)),
        ]);
    }
}
