<?php

namespace Database\Seeders;

use App\Models\SiteContent;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    /**
     * Seed the shipped copy for every published locale, so the database holds
     * the same content the application falls back to.
     */
    public function run(): void
    {
        foreach ((array) config('locales.available', []) as $locale => $meta) {
            foreach ((array) config('site.'.$locale, []) as $section => $payload) {
                SiteContent::query()->firstOrCreate(
                    ['section' => $section, 'locale' => $locale],
                    ['payload' => $payload],
                );
            }
        }
    }
}
