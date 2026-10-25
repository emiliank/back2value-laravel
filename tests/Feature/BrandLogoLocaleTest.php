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

        $albanianProps = $this->assertInertiaComponent($this->get('/sq'), 'Public/Home');
        $englishProps = $this->assertInertiaComponent($this->get('/en'), 'Public/Home');

        $this->assertSame($albanianProps['site']['logo'], $englishProps['site']['logo']);
        $this->assertStringEndsWith('/storage/content/logo.png', $albanianProps['site']['logo']);
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

        $props = $this->assertInertiaComponent($this->get('/en'), 'Public/Home');

        $this->assertStringEndsWith('/storage/content/logo.png', $props['site']['logo']);
    }

    public function test_no_custom_logo_is_shared_when_none_is_stored_anywhere(): void
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

        $props = $this->assertInertiaComponent($this->get('/en'), 'Public/Home');

        $this->assertNull($props['site']['logo']);
    }

    public function test_the_logo_is_shared_with_public_and_admin_page_components(): void
    {
        $this->seed(SiteContentSeeder::class);

        SiteContent::query()
            ->where('section', 'settings')
            ->where('locale', 'sq')
            ->update(['payload' => array_merge(config('site.sq.settings'), [
                'logo_image' => 'content/logo.png',
            ])]);

        app()->forgetInstance(SiteContentRepository::class);

        $publicProps = $this->assertInertiaComponent($this->get('/en/regeneration'), 'Public/Regeneration');
        $adminProps = $this->assertInertiaComponent($this->get(route('admin.login')), 'Admin/Login');

        $this->assertStringEndsWith('/storage/content/logo.png', $publicProps['site']['logo']);
        $this->assertSame($publicProps['site']['logo'], $adminProps['site']['logo']);
    }
}
