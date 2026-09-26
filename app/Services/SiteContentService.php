<?php

namespace App\Services;

use App\Models\SiteContent;

class SiteContentService
{
    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        $content = config('site');

        foreach (SiteContent::query()->get(['section', 'payload']) as $section) {
            if (array_key_exists($section->section, $content) && is_array($section->payload)) {
                $content[$section->section] = $section->payload;
            }
        }

        return $content;
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
    }
}
