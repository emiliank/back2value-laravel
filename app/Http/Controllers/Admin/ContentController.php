<?php

namespace App\Http\Controllers\Admin;

use App\Content\ContentEditor;
use App\Content\ContentSchema;
use App\Content\SiteContentRepository;
use App\Http\Controllers\Controller;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function __construct(
        private ContentEditor $editor,
        private SiteContentRepository $repository,
    ) {}

    public function dashboard(): View
    {
        $pages = ContentSchema::pages();
        $sections = ContentSchema::sections();
        $content = $this->repository->all();

        $editable = [];

        foreach ($sections as $key => $section) {
            $slug = collect($pages)->search(fn (array $candidate): bool => in_array($key, $candidate['sections'], true));
            $page = is_string($slug) ? $pages[$slug] : null;
            $stored = SiteContent::query()->where('section', $key)->exists();

            $editable[] = [
                'page' => $page['label'] ?? $section['title'],
                'slug' => $slug,
                'title' => $section['title'],
                'kicker' => $section['kicker'],
                'customised' => $stored,
                'fields' => $section['shape'] === 'list' ? count($content[$key] ?? []) : count($section['groups'] ?? []),
                'shape' => $section['shape'],
            ];
        }

        return view('admin.dashboard', [
            'stats' => [
                'pages' => count($pages),
                'sections' => count($sections),
                'customised' => collect($editable)->where('customised', true)->count(),
            ],
            'editable' => $editable,
            'pages' => $pages,
        ]);
    }

    /**
     * Backwards-compatible alias for the old bespoke settings page.
     */
    public function editSettings(): RedirectResponse
    {
        return redirect()->route('admin.content.edit', ['page' => 'settings']);
    }

    public function edit(Request $request, string $page): View
    {
        $definition = $this->page($page);
        $sections = $definition['sections'];
        $content = $this->editor->current($sections);

        return view('admin.content.edit', [
            'page' => $definition,
            'slug' => $page,
            'sectionDefinitions' => collect($sections)->mapWithKeys(fn (string $key): array => [$key => ContentSchema::section($key)])->all(),
            'content' => $content,
        ]);
    }

    public function update(Request $request, string $page): RedirectResponse
    {
        $definition = $this->page($page);
        $sections = $definition['sections'];

        $saved = $this->editor->save(
            $sections,
            $request->except(['_token', '_method']),
            $request->allFiles(),
        );

        return redirect()
            ->route('admin.content.edit', ['page' => $page])
            ->with('status', $saved === []
                ? 'Nuk u gjetën fusha për t\'u ruajtur.'
                : 'Përmbajtja u ruajt me sukses.');
    }

    public function reset(string $page): RedirectResponse
    {
        $definition = $this->page($page);

        foreach ($definition['sections'] as $section) {
            $this->editor->reset($section);
        }

        return redirect()
            ->route('admin.content.edit', ['page' => $page])
            ->with('status', 'Përmbajtja u kthye te vlerat fillestare.');
    }

    /**
     * @return array<string, mixed>
     */
    private function page(string $page): array
    {
        $definition = ContentSchema::pages()[$page] ?? null;

        abort_if($definition === null, 404);

        return $definition;
    }
}
