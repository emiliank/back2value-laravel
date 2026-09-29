<?php

use App\Content\ContentEditor;
use App\Content\SiteContentRepository;
use Illuminate\Support\Facades\Storage;

if (! function_exists('site')) {
    /**
     * Read editable site content with an optional dot path.
     */
    function site(string $section, ?string $key = null, mixed $default = null): mixed
    {
        $repository = app(SiteContentRepository::class);
        $payload = $repository->section($section);

        if ($key === null) {
            return $payload;
        }

        return data_get($payload, $key, $default);
    }
}

if (! function_exists('site_link')) {
    /**
     * Resolve a stored link target to a URL.
     */
    function site_link(?string $target): string
    {
        return ContentEditor::targetUrl($target);
    }
}

if (! function_exists('site_image')) {
    /**
     * Resolve a stored image path (public asset or uploaded file) to a URL.
     */
    function site_image(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        return str_starts_with($path, 'images/')
            ? asset($path)
            : Storage::disk('public')->url($path);
    }
}
