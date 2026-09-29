<?php

namespace App\Content;

use App\Models\SiteContent;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

/**
 * Reads editable site content, falling back to the shipped defaults in
 * config/site.php for any key an administrator has not saved yet.
 */
class SiteContentRepository
{
    /**
     * Request-scoped memoised payloads keyed by section.
     *
     * @var array<string, array<string, mixed>>
     */
    private array $resolved = [];

    /**
     * @var array<string, mixed>
     */
    public function defaults(): array
    {
        return config('site', []);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $defaults = $this->defaults();
        $stored = SiteContent::query()->pluck('payload', 'section');

        $content = [];

        foreach ($defaults as $section => $payload) {
            $saved = $stored->get($section);

            $content[$section] = is_array($saved)
                ? $this->mergeMissing($payload, $saved)
                : $payload;
        }

        foreach ($stored as $section => $payload) {
            if (! array_key_exists($section, $content) && is_array($payload)) {
                $content[$section] = $payload;
            }
        }

        return $content;
    }

    /**
     * @return array<string, mixed>
     */
    public function section(string $section, array $fallback = []): array
    {
        if (array_key_exists($section, $this->resolved)) {
            return $this->resolved[$section];
        }

        $default = Arr::get($this->defaults(), $section, []);
        $saved = SiteContent::query()->where('section', $section)->value('payload');

        $payload = is_array($saved)
            ? $this->mergeMissing(is_array($default) ? $default : [], $saved)
            : (is_array($default) ? $default : []);

        return $this->resolved[$section] = $payload ?: $fallback;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function save(string $section, array $payload): void
    {
        SiteContent::query()->updateOrCreate(
            ['section' => $section],
            ['payload' => $payload],
        );

        unset($this->resolved[$section]);
    }

    public function forget(): void
    {
        $this->resolved = [];
    }

    public function reset(string $section): void
    {
        SiteContent::query()->where('section', $section)->delete();

        unset($this->resolved[$section]);
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
