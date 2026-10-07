<?php

namespace App\Support;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\URL;

/**
 * Single source of truth for which language the site is serving.
 *
 * The locale prefix of the current URL wins, then the language the visitor
 * last chose, then the browser preference, and finally the configured
 * default. The result is registered as the default route parameter so every
 * `route()` call keeps producing a language-prefixed URL.
 */
class Locale
{
    public function __construct(private Application $app) {}

    /**
     * Locale code serving the current request.
     */
    public function current(): string
    {
        $available = $this->available();

        if ($available === []) {
            return (string) config('locales.default', 'sq');
        }

        $prefix = $this->app['request']->route()?->parameter('locale');

        if (is_string($prefix) && array_key_exists($prefix, $available)) {
            return $prefix;
        }

        // Admin screens have no prefix: the CMS chrome is Albanian only, and
        // each content tab states the language it edits.
        if ($this->app['request']->is('admin', 'admin/*')) {
            return $this->fallback();
        }

        $remembered = $this->app['session.store']->get($this->sessionKey());

        if (is_string($remembered) && array_key_exists($remembered, $available)) {
            return $remembered;
        }

        return $this->fromBrowser() ?? array_key_first($available);
    }

    /**
     * @return array<string, mixed>
     */
    public function available(): array
    {
        $available = config('locales.available', []);

        return is_array($available) ? $available : [];
    }

    public function isAvailable(string $locale): bool
    {
        return array_key_exists($locale, $this->available());
    }

    public function fallback(): string
    {
        return (string) config('locales.fallback', 'sq');
    }

    public function default(): string
    {
        return (string) config('locales.default', 'sq');
    }

    /**
     * Remember an explicit visitor choice across requests.
     */
    public function remember(string $locale): void
    {
        $this->app['session.store']->put($this->sessionKey(), $locale);
    }

    /**
     * Apply the resolved locale to the container and URL generator.
     */
    public function apply(): string
    {
        $locale = $this->current();

        $this->app->setLocale($locale);
        $this->app->setFallbackLocale($this->fallback());

        URL::defaults(['locale' => $locale]);

        return $locale;
    }

    /**
     * Native label of a locale, e.g. "Shqip".
     */
    public function label(string $locale): string
    {
        $meta = $this->available()[$locale] ?? [];

        return $meta['native'] ?? strtoupper($locale);
    }

    private function sessionKey(): string
    {
        return (string) config('locales.session_key', 'site_locale');
    }

    private function fromBrowser(): ?string
    {
        $supported = array_keys($this->available());

        if ($supported === []) {
            return null;
        }

        $header = (string) $this->app['request']->headers->get('Accept-Language', '');

        foreach (explode(',', $header) as $part) {
            // "en-GB;q=0.9" becomes "en-GB" with a 0.9 weight.
            $tag = strtolower(trim(explode(';', $part)[0]));

            if ($tag === '') {
                continue;
            }

            $primary = explode('-', $tag)[0];

            if (in_array($primary, $supported, true)) {
                return $primary;
            }
        }

        return null;
    }
}
