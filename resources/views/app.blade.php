@php
    $pageProps = is_array($page ?? null) ? ($page['props'] ?? []) : [];
    $pageTitle = $pageProps['title'] ?? ($settings['meta_title'] ?? 'Back2Value');
    $pageDescription = $pageProps['description'] ?? ($settings['meta_description'] ?? '');
    $locale = current_locale();
    $isAdmin = request()->routeIs('admin.*');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if ($isAdmin)
            <meta name="robots" content="noindex, nofollow">
        @else
            <meta name="description" content="{{ $pageDescription }}">
            <meta name="theme-color" content="#101b32">
            <meta property="og:site_name" content="{{ $settings['meta_title'] ?? 'Back2Value' }}">
            <meta property="og:type" content="website">
            <meta property="og:title" content="{{ $pageTitle }}">
            <meta property="og:description" content="{{ $pageDescription }}">
            <meta property="og:image" content="{{ asset('images/back2value-logo.png') }}">
            <meta property="og:image:alt" content="{{ $navigation['brand_alt'] ?? 'Logo Back2Value' }}">
            <meta property="og:locale" content="{{ str_replace('-', '_', available_locales()[$locale]['og_locale'] ?? $locale) }}">
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:image" content="{{ asset('images/back2value-logo.png') }}">
            @foreach (available_locales() as $code => $meta)
                <link rel="alternate" hreflang="{{ $meta['og_locale'] ?? $code }}" href="{{ locale_url($code) }}">
            @endforeach
            <link rel="alternate" hreflang="x-default" href="{{ locale_url((string) config('locales.default', 'sq')) }}">
        @endif
        <title>{{ $pageTitle }}</title>
        @unless ($isAdmin)
            {{-- Apply the stored theme before the first paint so the page never flashes light. --}}
            <script>
                (function () {
                    try {
                        var stored = window.localStorage.getItem('back2value-theme');
                        var theme = stored === 'dark' || stored === 'light'
                            ? stored
                            : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');

                        document.documentElement.dataset.theme = theme;

                        if (theme === 'dark') {
                            document.documentElement.classList.add('dark');
                        }
                    } catch (error) {
                        // Storage can be unavailable; the site simply stays on the light theme.
                    }
                })();
            </script>
        @else
            {{-- The CMS keeps its own light palette, so the dark theme never applies here. --}}
            <script>document.documentElement.classList.remove('dark');</script>
        @endunless
        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.jsx'])
        @inertiaHead
    </head>
    <body>
        @inertia
    </body>
</html>
