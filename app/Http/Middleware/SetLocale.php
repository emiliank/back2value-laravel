<?php

namespace App\Http\Middleware;

use App\Support\Locale;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the active locale, remembers an explicit choice and registers it as
 * the default route parameter so every generated URL keeps its language
 * prefix.
 */
class SetLocale
{
    public function __construct(private Locale $locales) {}

    public function handle(Request $request, Closure $next): Response
    {
        $prefix = $request->route()?->parameter('locale');

        // A language in the URL is always a deliberate choice: keep it.
        if (is_string($prefix) && $this->locales->isAvailable($prefix)) {
            $this->locales->remember($prefix);
        }

        $this->locales->apply();

        return $next($request);
    }
}
