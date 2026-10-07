<?php

use App\Content\ContentEditor;
use App\Content\SiteContentRepository;
use App\Support\Locale;

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

if (! function_exists('current_locale')) {
    /**
     * The locale code serving the current request.
     */
    function current_locale(): string
    {
        return app()->getLocale();
    }
}

if (! function_exists('available_locales')) {
    /**
     * Every locale a visitor may switch to, keyed by code.
     *
     * @return array<string, mixed>
     */
    function available_locales(): array
    {
        return app(Locale::class)->available();
    }
}

if (! function_exists('locale_url')) {
    /**
     * The current page in another locale, keeping the path and query string
     * so switching language stays on the same content.
     */
    function locale_url(string $locale): string
    {
        $request = request();

        $path = trim($request->path(), '/');

        // Strip a locale prefix already present so the path is rebuilt cleanly.
        foreach (array_keys(available_locales()) as $code) {
            if ($path === $code || str_starts_with($path, $code.'/')) {
                $path = trim(substr($path, strlen($code)), '/');

                break;
            }
        }

        $url = url($path === '' ? $locale : $locale.'/'.$path);

        $query = $request->getQueryString();

        return filled($query) ? $url.'?'.$query : $url;
    }
}

if (! function_exists('site_logo')) {
    /**
     * Resolve the brand logo, which is identical in every language.
     *
     * The logo is brand identity rather than translated copy, so it is read
     * from whichever language has one stored instead of the active one.
     * Switching language therefore never changes the logo.
     */
    function site_logo(): ?string
    {
        return app(SiteContentRepository::class)->sharedSetting('logo_image');
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
            : asset('storage/'.ltrim($path, '/'));
    }
}
