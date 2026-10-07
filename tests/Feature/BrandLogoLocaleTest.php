<?php

namespace Tests\Feature;

use App\Content\SiteContentRepository;
use App\Models\SiteContent;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandLogoLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_logo_is_identical_in_every_language(): void
    {
        $this->seed(SiteContentSeeder::class);

        // The logo was uploaded once, under Albanian only.
        SiteContent::query()
            ->where('section', 'settings')
            ->where('locale', 'sq')
            ->update(['payload' => array_merge(config('site.sq.settings'), [
                'logo_image' => 'content/logo.png',
            ])]);

        app()->forgetInstance(SiteContentRepository::class);

        $expected = '/storage/content/logo.png';

        $this->get('/sq')->assertOk()->assertSee($expected, false);
        $this->get('/en')->assertOk()->assertSee($expected, false);
    }

    public function test_removing_the_logo_in_one_language_keeps_it_on_the_other(): void
    {
        $this->seed(SiteContentSeeder::class);

        SiteContent::query()
            ->where('section', 'settings')
            ->where('locale', 'sq')
            ->update(['payload' => array_merge(config('site.sq.settings'), [
                'logo_image' => 'content/logo.png',
            ])]);

        app()->forgetInstance(SiteContentRepository::class);

        $this->get('/en')->assertOk()->assertSee('/storage/content/logo.png', false);
    }

    public function test_the_wordmark_is_used_when_no_logo_is_stored_anywhere(): void
    {
        $this->seed(SiteContentSeeder::class);

        foreach (['sq', 'en'] as $locale) {
            SiteContent::query()
                ->where('section', 'settings')
                ->where('locale', $locale)
                ->update(['payload' => array_merge(config("site.{$locale}.settings"), [
                    'logo_image' => null,
                ])]);
        }

        app()->forgetInstance(SiteContentRepository::class);

        $this->get('/en')->assertOk()->assertSee('site-brand-word', false);
    }

    public function test_the_logo_appears_on_the_footer_and_admin_panel_too(): void
    {
        $this->seed(SiteContentSeeder::class);

        SiteContent::query()
            ->where('section', 'settings')
            ->where('locale', 'sq')
            ->update(['payload' => array_merge(config('site.sq.settings'), [
                'logo_image' => 'content/logo.png',
            ])]);

        app()->forgetInstance(SiteContentRepository::class);

        $this->get('/en/regeneration')->assertOk()->assertSee('/storage/content/logo.png', false);
    }
}
