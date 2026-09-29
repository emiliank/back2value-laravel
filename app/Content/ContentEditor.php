<?php

namespace App\Content;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Reads, validates and persists the editable content described by
 * {@see ContentSchema}.
 */
class ContentEditor
{
    private const IMAGE_DISK = 'public';

    private const IMAGE_DIRECTORY = 'content';

    /**
     * Path prefixes an administrator may reference: shipped public assets or
     * files previously uploaded through the panel.
     */
    private const ALLOWED_PATH_PREFIXES = ['images/', 'content/'];

    /**
     * @var array<string, mixed>
     */
    private array $sections;

    /**
     * Uploaded files for the request currently being validated, keyed by
     * their dotted input name.
     *
     * @var array<string, mixed>
     */
    private array $uploads = [];

    public function __construct(private SiteContentRepository $repository)
    {
        $this->sections = ContentSchema::sections();
    }

    /**
     * @return array<string, mixed>
     */
    public function sections(): array
    {
        return $this->sections;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function section(string $section): ?array
    {
        return $this->sections[$section] ?? null;
    }

    /**
     * Current payload of every section owned by a page.
     *
     * @param  list<string>  $sectionKeys
     * @return array<string, mixed>
     */
    public function current(array $sectionKeys): array
    {
        $current = [];

        foreach ($sectionKeys as $key) {
            $current[$key] = $this->repository->section($key);
        }

        return $current;
    }

    /**
     * Validate and store every submitted section of a page. Sections the
     * request did not carry are left untouched so partial updates stay safe.
     *
     * @param  array<string, mixed>  $input
     * @param  array<string, mixed>  $files
     * @return array<string, array<string, mixed>>
     */
    public function save(array $sectionKeys, array $input, array $files): array
    {
        abort_if($sectionKeys === [], 404);

        // Browsers may submit name="section.group.field", and PHP keeps those
        // keys flat instead of nesting them. Undot so both shapes are recognised.
        $input = Arr::undot($input);

        $sectionKeys = array_values(array_filter(
            $sectionKeys,
            fn (string $key): bool => array_key_exists($key, $input) || array_key_exists($key, $files),
        ));

        if ($sectionKeys === []) {
            return [];
        }

        $current = $this->current($sectionKeys);

        $input = $this->withoutRemovedRows($input);

        $this->uploads = Arr::dot($files);

        try {
            $validated = validator(
                $input,
                $this->rules($sectionKeys, $current),
                $this->messages($sectionKeys),
                $this->attributes($sectionKeys),
            )->validate();
        } finally {
            $this->uploads = [];
        }

        $payloads = [];

        foreach ($sectionKeys as $key) {
            $payloads[$key] = $this->buildSection($key, $validated, $files, $current[$key]);
        }

        foreach ($payloads as $key => $payload) {
            $this->deleteOrphans($current[$key], $payload);
            $this->repository->save($key, $payload);
        }

        return $payloads;
    }

    /**
     * Drop repeater rows the administrator ticked for removal before the
     * payload is validated, so `min` row rules see the real outcome.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function withoutRemovedRows(array $input): array
    {
        $output = [];

        foreach ($input as $key => $value) {
            if (! is_array($value)) {
                $output[$key] = $value;

                continue;
            }

            if ($this->isRemovedRow($value)) {
                continue;
            }

            $output[$key] = $this->withoutRemovedRows($value);
        }

        return $output;
    }

    /**
     * Determine whether a field received an upload, honouring `*` wildcards
     * used by repeater field names.
     */
    private function hasUpload(string $name): bool
    {
        $pattern = '/^'.str_replace('\*', '[^.]+', preg_quote($name, '/')).'$/';

        foreach (array_keys($this->uploads) as $upload) {
            if (preg_match($pattern, $upload) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<array-key, mixed>  $row
     */
    private function isRemovedRow(array $row): bool
    {
        return array_key_exists('remove', $row) && filter_var($row['remove'], FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Delete a section so the shipped defaults apply again.
     */
    public function reset(string $section): void
    {
        $this->repository->reset($section);
    }

    // -----------------------------------------------------------------
    // Validation
    // -----------------------------------------------------------------

    /**
     * @param  list<string>  $sectionKeys
     * @param  array<string, mixed>  $current
     * @return array<string, array<int, mixed>>
     */
    public function rules(array $sectionKeys, array $current = []): array
    {
        $rules = [];

        foreach ($sectionKeys as $key) {
            $section = $this->section($key);

            if ($section === null) {
                continue;
            }

            if (($section['shape'] ?? 'group') === 'list') {
                $rules[$key] = ['array', 'min:'.$section['min'], 'max:'.$section['max_items']];
                $rules += $this->rowRules($key.'.*', $section['fields'], array_keys($current[$key] ?? []));

                continue;
            }

            foreach ($section['groups'] as $group) {
                $rules += $this->fieldRules($group['fields'], $key.'.', $current[$key] ?? []);
            }
        }

        return $rules;
    }

    /**
     * @param  list<string>  $sectionKeys
     * @return array<string, string>
     */
    public function messages(array $sectionKeys): array
    {
        $messages = [];

        foreach ($sectionKeys as $key) {
            $section = $this->section($key);

            if ($section === null) {
                continue;
            }

            $prefix = ($section['shape'] ?? 'group') === 'list' ? $key.'.*.' : $key.'.';

            foreach ($this->flatten($section) as $path => $field) {
                $messages[$prefix.$path.'.required'] = 'Plotësoni: '.$field['label'].'.';
            }
        }

        return $messages;
    }

    /**
     * @param  list<string>  $sectionKeys
     * @return array<string, string>
     */
    public function attributes(array $sectionKeys): array
    {
        $attributes = [];

        foreach ($sectionKeys as $key) {
            $section = $this->section($key);

            if ($section === null) {
                continue;
            }

            $prefix = ($section['shape'] ?? 'group') === 'list' ? $key.'.*.' : $key.'.';

            foreach ($this->flatten($section) as $path => $field) {
                $attributes[$prefix.$path] = mb_strtolower($field['label']);
            }
        }

        return $attributes;
    }

    /**
     * Flatten a section into dotted field paths.
     *
     * @param  array<string, mixed>  $section
     * @return array<string, array<string, mixed>>
     */
    private function flatten(array $section, string $prefix = ''): array
    {
        $fields = ($section['shape'] ?? 'group') === 'list' ? $section['fields'] : $this->groupFields($section);

        $flat = [];

        foreach ($fields as $field) {
            $path = $prefix === '' ? $field['key'] : $prefix.'.'.$field['key'];

            if ($field['type'] === 'repeater') {
                $flat += $this->flatten(['shape' => 'list', 'fields' => $field['fields']], $path.'.*');

                continue;
            }

            $flat[$path] = $field;
        }

        return $flat;
    }

    /**
     * @param  array<string, mixed>  $section
     * @return array<int, array<string, mixed>>
     */
    private function groupFields(array $section): array
    {
        $fields = [];

        foreach ($section['groups'] as $group) {
            foreach ($group['fields'] as $field) {
                $fields[] = $field;
            }
        }

        return $fields;
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @param  array<string, mixed>  $current
     * @return array<string, array<int, mixed>>
     */
    private function fieldRules(array $fields, string $prefix, array $current = []): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $name = $prefix.$field['key'];

            if ($field['type'] === 'repeater') {
                $rules[$name] = [
                    'array',
                    'min:'.$field['min'],
                    'max:'.$field['max_items'],
                ] + $this->rowRules($name.'.*', $field['fields'], array_keys((array) ($current[$field['key']] ?? [])), $field['keyed']);

                continue;
            }

            $rules += $this->singleRules($field, $name);
        }

        return $rules;
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @param  array<int, string>  $rowKeys
     * @return array<string, array<int, mixed>>
     */
    private function rowRules(string $prefix, array $fields, array $rowKeys = [], bool $keyed = false): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $rules += $this->singleRules($field, $prefix.'.'.$field['key']);
        }

        if ($keyed) {
            $rules[$prefix.'.key'] = ['nullable', 'string', 'max:80', 'alpha_dash'];
        }

        return $rules;
    }

    /**
     * @param  array<string, mixed>  $field
     * @return array<string, array<int, mixed>>
     */
    private function singleRules(array $field, string $name): array
    {
        if ($field['type'] === 'image') {
            if (! $this->hasUpload($name)) {
                return [$name => ['nullable', 'string', 'max:255']];
            }

            return [
                $name => array_filter([$field['required'] ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120']),
                $name.'_path' => ['nullable', 'string', 'max:255'],
                $name.'_remove' => ['nullable', 'boolean'],
            ];
        }

        return [$name => ContentSchema::rules($field)];
    }

    // -----------------------------------------------------------------
    // Payload building
    // -----------------------------------------------------------------

    /**
     * @param  array<string, mixed>  $validated
     * @param  array<string, mixed>  $files
     * @param  array<string, mixed>  $current
     * @return array<string, mixed>
     */
    private function buildSection(string $key, array $validated, array $files, array $current): array
    {
        $section = $this->section($key);

        if ($section === null) {
            return [];
        }

        if (($section['shape'] ?? 'group') === 'list') {
            return $this->buildRepeater($section['fields'], Arr::get($validated, $key, []), $current, $key, $files);
        }

        $payload = [];

        foreach ($section['groups'] as $group) {
            $payload += $this->buildGroup($group['fields'], $key.'.', $validated, $files, $current);
        }

        return $payload;
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @param  array<string, mixed>  $validated
     * @param  array<string, mixed>  $files
     * @param  array<string, mixed>  $current
     * @return array<string, mixed>
     */
    private function buildGroup(array $fields, string $prefix, array $validated, array $files, array $current): array
    {
        $payload = [];

        foreach ($fields as $field) {
            $name = $prefix.$field['key'];
            $existing = $current[$field['key']] ?? null;

            $payload[$field['key']] = match ($field['type']) {
                'repeater' => $this->buildRepeater(
                    $field['fields'],
                    Arr::get($validated, $name, []),
                    is_array($existing) ? $existing : [],
                    $name,
                    $files,
                    $field['keyed'],
                ),
                'list' => ContentSchema::toList($field, Arr::get($validated, $name)),
                'pairs' => ContentSchema::toPairs(Arr::get($validated, $name)),
                'image' => $this->resolveImage($name, $validated, $files, is_string($existing) ? $existing : null),
                default => ContentSchema::sanitise($field['type'], Arr::get($validated, $name)),
            };
        }

        return $payload;
    }

    /**
     * @param  array<int, array<string, mixed>>  $fields
     * @param  array<string, mixed>  $submitted
     * @param  array<string, mixed>  $files
     * @return array<int|string, array<string, mixed>>
     */
    private function buildRepeater(array $fields, array $submitted, array $current, string $name, array $files, bool $keyed = false): array
    {
        $previous = $this->indexRows($current, (bool) ($keyed ? true : false));
        $rows = [];
        $used = [];

        foreach ($submitted as $rowKey => $row) {
            if (! is_array($row) || filter_var($row['remove'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            $key = $this->rowKey((string) $rowKey, $row['key'] ?? null, $used);
            $existing = $previous[$key] ?? [];
            $built = [];

            foreach ($fields as $field) {
                $itemName = $name.'.'.$rowKey.'.'.$field['key'];
                $itemValue = $row[$field['key']] ?? null;

                $built[$field['key']] = match ($field['type']) {
                    'repeater' => $this->buildRepeater(
                        $field['fields'],
                        is_array($itemValue) ? $itemValue : [],
                        $existing[$field['key']] ?? [],
                        $itemName,
                        $files,
                        $field['keyed'],
                    ),
                    'list' => ContentSchema::toList($field, is_string($itemValue) ? $itemValue : null),
                    'pairs' => ContentSchema::toPairs($itemValue),
                    'image' => $this->resolveImage($itemName, $row, $files, is_string($existing[$field['key']] ?? null) ? $existing[$field['key']] : null),
                    default => ContentSchema::sanitise($field['type'], $itemValue),
                };
            }

            if ($keyed) {
                $built['key'] = $key;
                $rows[$key] = $built;
            } else {
                $rows[] = $built;
            }
        }

        return $rows;
    }

    /**
     * @param  array<string, mixed>  $rows
     * @return array<string, array<string, mixed>>
     */
    private function indexRows(array $rows): array
    {
        $indexed = [];

        foreach ($rows as $position => $row) {
            if (is_array($row)) {
                $indexed[(string) ($row['key'] ?? $position)] = $row;
            }
        }

        return $indexed;
    }

    /**
     * @param  array<int, string>  $used
     */
    private function rowKey(string $rowKey, mixed $submittedKey, array &$used): string
    {
        $candidate = Str::slug((string) ($submittedKey ?: $rowKey), '-');

        if ($candidate === '' || in_array($candidate, $used, true)) {
            $base = $candidate !== '' ? $candidate : 'item';
            $candidate = $base;
            $suffix = 2;

            while (in_array($candidate, $used, true)) {
                $candidate = $base.'-'.$suffix++;
            }
        }

        $used[] = $candidate;

        return $candidate;
    }

    // -----------------------------------------------------------------
    // Images
    // -----------------------------------------------------------------

    /**
     * @param  array<string, mixed>  $source
     * @param  array<string, mixed>  $files
     */
    private function resolveImage(string $name, array $source, array $files, ?string $current): ?string
    {
        if (filter_var($source[$name.'_remove'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
            $this->deleteStored($current);

            return null;
        }

        $upload = Arr::get($files, $name);

        if ($upload instanceof UploadedFile) {
            $this->deleteStored($current);

            return $upload->store(self::IMAGE_DIRECTORY, self::IMAGE_DISK);
        }

        return $this->allowedPath($source[$name.'_path'] ?? $current);
    }

    private function allowedPath(mixed $path): ?string
    {
        if (! is_string($path) || $path === '') {
            return null;
        }

        foreach (self::ALLOWED_PATH_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return $path;
            }
        }

        return null;
    }

    private function deleteStored(?string $path): void
    {
        if ($path !== null && str_starts_with($path, self::IMAGE_DIRECTORY.'/')) {
            Storage::disk(self::IMAGE_DISK)->delete($path);
        }
    }

    /**
     * Remove uploads that are no longer referenced after an edit.
     *
     * @param  array<string, mixed>  $before
     * @param  array<string, mixed>  $after
     */
    private function deleteOrphans(array $before, array $after): void
    {
        foreach (array_diff($this->collectUploads($before), $this->collectUploads($after)) as $path) {
            $this->deleteStored($path);
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<int, string>
     */
    private function collectUploads(array $payload): array
    {
        $paths = [];

        array_walk_recursive($payload, function (mixed $value, mixed $key) use (&$paths): void {
            if (is_string($value) && is_string($key) && preg_match('/(^|_)(image|logo|badge_image)$/', $key)) {
                $paths[] = $value;
            }
        });

        return array_values(array_unique($paths));
    }

    /**
     * Resolve a stored content link target to a URL.
     */
    public static function targetUrl(?string $target): string
    {
        return match ($target) {
            'home' => route('home'),
            'products' => route('products.index'),
            'services' => route('services.index'),
            'regeneration' => route('regeneration.index'),
            'solutions' => route('solutions.index'),
            'sustainability' => route('sustainability.index'),
            'resources' => route('resources.index'),
            'diagnostics' => route('diagnostics.create'),
            'calculator' => '#kalkulator',
            'contact' => route('home').'#kontakt',
            default => route('home'),
        };
    }

    /**
     * @param  array<string, string>  $targets
     */
    public static function in(array $targets): array
    {
        return Rule::in($targets === [] ? [''] : array_keys($targets));
    }
}
