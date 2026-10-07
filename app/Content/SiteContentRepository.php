<?php

namespace App\Content;

use App\Models\SiteContent;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Reads editable site content for the active locale, falling back to the
 * shipped defaults in config/site/{locale}.php for any key an administrator
 * has not saved yet.
 *
 * Resolution order per section:
 *   1. the stored payload for the active locale,
 *   2. the stored payload for the fallback locale,
 *   3. the shipped defaults for the active locale.
 */
class SiteContentRepository
{
    /**
     * Request-scoped memoised payloads keyed by locale and section.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $resolved = [];

    /**
     * Shipped defaults for a locale.
     *
     * @return array<string, mixed>
     */
    public function defaults(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        $config = config('site.'.$locale);

        return is_array($config) ? $config : [];
    }

    /**
     * Every section for a locale.
     *
     * @return array<string, mixed>
     */
    public function all(?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        $defaults = $this->defaults($locale);
        $stored = $this->storedPayloads($locale);

        $content = [];

        foreach ($defaults as $section => $payload) {
            $own = $this->stored($stored, $section, $locale);

            $content[$section] = is_array($own)
                ? $this->mergeMissing(is_array($payload) ? $payload : [], $own)
                : $payload;
        }

        // Sections stored for this locale that ship no defaults of their own.
        foreach ($stored as $row) {
            $section = $row['section'] ?? null;

            if (($row['locale'] ?? null) === $locale && is_string($section) && ! array_key_exists($section, $content)) {
                $content[$section] = $row['payload'];
            }
        }

        return $content;
    }

    /**
     * One section for a locale.
     *
     * The requested locale's own shipped defaults win, so a language that
     * ships its own copy is never overwritten by another one. The fallback
     * locale is consulted only for keys this locale has no copy of at all,
     * either in its defaults or in its stored edits.
     *
     * @return array<string, mixed>
     */
    public function section(string $section, array $fallback = [], ?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $key = $locale.'|'.$section;

        if (array_key_exists($key, $this->resolved)) {
            return $this->resolved[$key];
        }

        $default = Arr::get($this->defaults($locale), $section);
        $default = is_array($default) ? $default : null;

        $stored = $this->storedPayloads($locale);
        $own = $this->stored($stored, $section, $locale);

        if (is_array($default)) {
            $payload = is_array($own) ? $this->mergeMissing($default, $own) : $default;
        } else {
            $payload = $this->resolveThroughFallback($section, $locale, $own);
        }

        return $this->resolved[$key] = $payload ?: $fallback;
    }

    /**
     * Build a section from the fallback locale, then layer the requested
     * locale's own edits on top.
     *
     * @param  array<string, mixed>|null  $own
     * @return array<string, mixed>
     */
    private function resolveThroughFallback(string $section, string $locale, ?array $own): array
    {
        $fallbackLocale = (string) config('locales.fallback', 'sq');

        if ($fallbackLocale === $locale) {
            return is_array($own) ? $own : [];
        }

        $default = Arr::get($this->defaults($fallbackLocale), $section);
        $default = is_array($default) ? $default : [];

        $fallbackStored = $this->stored($this->storedPayloads($fallbackLocale), $section, $fallbackLocale);

        $payload = is_array($fallbackStored) ? $this->mergeMissing($default, $fallbackStored) : $default;

        return is_array($own) ? $this->mergeMissing($payload, $own) : $payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function save(string $section, array $payload, ?string $locale = null): void
    {
        $locale ??= app()->getLocale();

        SiteContent::query()->updateOrCreate(
            ['section' => $section, 'locale' => $locale],
            ['payload' => $payload],
        );

        unset($this->resolved[$locale.'|'.$section]);
    }

    public function forget(): void
    {
        $this->resolved = [];
    }

    public function reset(string $section, ?string $locale = null): void
    {
        $locale ??= app()->getLocale();

        SiteContent::query()
            ->where('section', $section)
            ->where('locale', $locale)
            ->delete();

        unset($this->resolved[$locale.'|'.$section]);
    }

    /**
     * Locales a visitor may pick, keyed by code.
     *
     * @return array<string, mixed>
     */
    public function locales(): array
    {
        $available = config('locales.available', []);

        return is_array($available) ? $available : [];
    }

    /**
     * Read a setting that must not vary by language, such as the logo.
     *
     * Any language that has a value wins, so a logo uploaded once is shown on
     * every language instead of only the one that happened to store it.
     */
    public function sharedSetting(string $key, mixed $default = null): mixed
    {
        $section = 'settings';
        $path = $key;

        $stored = SiteContent::query()
            ->whereIn('locale', array_keys($this->locales()))
            ->where('section', $section)
            ->get(['locale', 'payload']);

        foreach ($stored as $row) {
            $value = data_get((array) $row->payload, $path);

            if (filled($value)) {
                return $value;
            }
        }

        foreach (array_keys($this->locales()) as $locale) {
            $value = data_get($this->defaults($locale), $section.'.'.$path);

            if (filled($value)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Whether a locale code is one this site publishes.
     */
    public function isValidLocale(mixed $locale): bool
    {
        return is_string($locale) && array_key_exists($locale, $this->locales());
    }

    public function defaultLocale(): string
    {
        $default = (string) config('locales.default', 'sq');

        return array_key_exists($default, $this->locales())
            ? $default
            : (string) array_key_first($this->locales() ?: ['sq' => []]);
    }

    /**
     * Whether a section has been customised for a locale.
     */
    public function isCustomised(string $section, ?string $locale = null): bool
    {
        return SiteContent::query()
            ->where('section', $section)
            ->where('locale', $locale ?? app()->getLocale())
            ->exists();
    }

    /**
     * Stored rows for a locale, keyed by locale|section.
     *
     * @return Collection<string, array<string, mixed>>
     */
    private function storedPayloads(string $locale): Collection
    {
        return SiteContent::query()
            ->whereIn('locale', array_keys($this->locales()))
            ->get(['section', 'locale', 'payload'])
            ->mapWithKeys(fn (SiteContent $row): array => [
                $row->locale.'|'.$row->section => $row->payload,
            ]);
    }

    /**
     * The stored payload for a section in an exact locale.
     *
     * @param  Collection<string, array<string, mixed>>  $stored
     * @return array<string, mixed>|null
     */
    private function stored(Collection $stored, string $section, string $locale): ?array
    {
        $payload = $stored->get($locale.'|'.$section);

        return is_array($payload) ? $payload : null;
    }

    /**
     * Recursively fill keys that exist in the defaults but are missing from a
     * stored payload, so newly shipped content keys appear without a re-save.
     *
     * @param  array<string, mixed>  $defaults
     * @param  array<string, mixed>  $saved
     * @return array<string, mixed>
     */
    private function mergeMissing(array $defaults, array $saved): array
    {
        foreach ($defaults as $key => $value) {
            if (! array_key_exists($key, $saved)) {
                $saved[$key] = $value;

                continue;
            }

            if (is_array($value) && $this->isAssociative($value) && is_array($saved[$key]) && $this->isAssociative($saved[$key])) {
                $saved[$key] = $this->mergeMissing($value, $saved[$key]);
            }
        }

        return $saved;
    }

    /**
     * @param  array<string, mixed>  $value
     */
    private function isAssociative(array $value): bool
    {
        return ! array_is_list($value);
    }

    /**
     * Sort a repeatable section by its sort_order field.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    public static function sorted(array $items): array
    {
        return (new Collection($items))
            ->sortBy(fn (array $item): int => (int) ($item['sort_order'] ?? 0))
            ->values()
            ->all();
    }
}
