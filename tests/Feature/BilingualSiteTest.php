<?php

namespace Tests\Feature;

use App\Content\SiteContentRepository;
use App\Models\SiteContent;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BilingualSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'admin.email' => 'admin@example.test',
            'admin.password' => 'very-secure-test-password',
        ]);
    }

    public function test_bare_root_redirects_a_new_visitor_to_their_browser_language(): void
    {
        $this->get('/', ['Accept-Language' => 'sq;q=0.9,en;q=0.5'])
            ->assertRedirect(route('home', ['locale' => 'sq']));
    }

    public function test_bare_root_redirects_an_english_browser_to_english(): void
    {
        $this->get('/', ['Accept-Language' => 'en-GB,en;q=0.9'])
            ->assertRedirect(route('home', ['locale' => 'en']));
    }

    public function test_bare_root_keeps_the_language_the_visitor_chose(): void
    {
        $this->withSession([config('locales.session_key') => 'en'])
            ->get('/')
            ->assertRedirect(route('home', ['locale' => 'en']));
    }

    public function test_each_language_serves_its_own_copy_of_the_home_page(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/sq')->assertOk()
            ->assertSee('Bateri gjermane. Garanci reale. Shërbim vendor.')
            ->assertSee('Rezervo Diagnostikim')
            ->assertDontSee('German batteries. Real warranty. Vendor service.');

        $this->get('/en')->assertOk()
            ->assertSee('German batteries. Real warranty. Vendor service.')
            ->assertSee('Book a Diagnostic')
            ->assertDontSee('Bateri gjermane. Garanci reale. Shërbim vendor.');
    }

    public function test_the_document_language_matches_the_requested_locale(): void
    {
        $this->get('/sq')->assertOk()->assertSee('<html lang="sq">', false);
        $this->get('/en')->assertOk()->assertSee('<html lang="en">', false);
    }

    public function test_every_page_is_reachable_under_both_language_prefixes(): void
    {
        $this->seed(SiteContentSeeder::class);

        $paths = ['/', '/products', '/services', '/solutions', '/sustainability', '/regeneration', '/resources', '/diagnostics'];

        foreach ($paths as $path) {
            $this->get('/sq'.$path)->assertOk();
            $this->get('/en'.$path)->assertOk();
        }
    }

    public function test_pages_state_their_language_alternates_for_search_engines(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/sq/products')->assertOk()
            ->assertSee('hreflang="sq_AL"', false)
            ->assertSee('hreflang="en_GB"', false)
            ->assertSee('hreflang="x-default"', false)
            ->assertSee(route('products.index', ['locale' => 'en']), false);
    }

    public function test_the_language_picker_is_offered_on_public_pages(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/sq')->assertOk()
            ->assertSee('language-switcher', false)
            ->assertSee('<span class="language-switcher__label" lang="sq">SQ</span>', false)
            ->assertSee('<span class="language-switcher__label" lang="en">EN</span>', false)
            // The short code is backed by a readable name for screen readers.
            ->assertSee('aria-label="Shqip"', false)
            ->assertSee('aria-label="English"', false)
            ->assertSee(route('home', ['locale' => 'en']), false);

        $this->get('/en')->assertOk()
            ->assertSee('language-switcher', false)
            ->assertSee('aria-current="true"', false);
    }

    public function test_switching_language_keeps_the_visitor_on_the_same_page(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/en/products?category=RID+Series+OPzS')->assertOk()
            ->assertSee(route('products.index', ['locale' => 'sq', 'category' => 'RID Series OPzS']), false);
    }

    public function test_an_unsupported_language_prefix_is_not_routed(): void
    {
        $this->get('/de/products')->assertNotFound();
    }

    public function test_choosing_a_language_remembers_it_for_later_visits(): void
    {
        $this->get('/en')
            ->assertSessionHas(config('locales.session_key'), 'en');

        $this->get('/')
            ->assertRedirect(route('home', ['locale' => 'en']));
    }

    public function test_content_saved_for_one_language_does_not_leak_into_the_other(): void
    {
        $this->seed(SiteContentSeeder::class);

        SiteContent::query()
            ->where('section', 'settings')
            ->where('locale', 'en')
            ->update(['payload' => array_merge(config('site.en.settings'), [
                'hero_title' => 'German batteries edited by an admin',
            ])]);

        app()->forgetInstance(SiteContentRepository::class);

        $this->get('/en')->assertOk()->assertSee('German batteries edited by an admin');
        $this->get('/sq')->assertOk()->assertSee('Bateri gjermane.', false);
    }

    public function test_an_untranslated_section_falls_back_to_the_fallback_language(): void
    {
        config(['locales.available' => ['sq' => ['native' => 'Shqip'], 'xx' => ['native' => 'Test']]]);

        SiteContent::query()->create([
            'section' => 'settings',
            'locale' => 'sq',
            'payload' => array_merge(config('site.sq.settings'), ['hero_title' => 'Titull nga ruajtja sq']),
        ]);

        $repository = app(SiteContentRepository::class);

        // "xx" ships no defaults, so the Albanian copy is used instead.
        $this->assertSame('Titull nga ruajtja sq', $repository->section('settings', [], 'xx')['hero_title']);
    }

    public function test_administrators_edit_each_language_through_its_own_tab(): void
    {
        $this->seed(SiteContentSeeder::class);

        $admin = User::factory()->create(['email' => 'admin@example.test']);

        $this->actingAs($admin)
            ->get(route('admin.content.edit', ['page' => 'settings', 'locale' => 'en']))
            ->assertOk()
            ->assertSee('admin-locale-tabs', false)
            ->assertSee('German batteries. Real warranty. Vendor service.');

        $this->actingAs($admin)
            ->get(route('admin.content.edit', ['page' => 'settings', 'locale' => 'sq']))
            ->assertOk()
            ->assertSee('Bateri gjermane. Garanci reale. Shërbim vendor.');
    }

    public function test_saving_a_page_writes_only_the_language_being_edited(): void
    {
        $this->seed(SiteContentSeeder::class);

        $admin = User::factory()->create(['email' => 'admin@example.test']);

        $settings = config('site.en.settings');
        $settings['hero_title'] = 'Edited in English';

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'settings', 'locale' => 'en']), ['settings' => $settings])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->assertSame(
            'Edited in English',
            SiteContent::query()->where('section', 'settings')->where('locale', 'en')->value('payload')['hero_title'],
        );

        $this->assertNotSame(
            'Edited in English',
            SiteContent::query()->where('section', 'settings')->where('locale', 'sq')->value('payload')['hero_title'],
        );
    }

    public function test_a_new_locale_row_is_written_with_its_own_language_code(): void
    {
        $this->seed(SiteContentSeeder::class);

        app(SiteContentRepository::class)->save(
            'settings',
            ['hero_title' => 'Titull i ri'],
            'en',
        );

        $this->assertDatabaseHas('site_contents', [
            'section' => 'settings',
            'locale' => 'en',
        ]);

        // The Albanian row keeps its own payload untouched.
        $this->assertNotSame(
            'Titull i ri',
            SiteContent::query()->where('section', 'settings')->where('locale', 'sq')->value('payload')['hero_title'],
        );
    }

    public function test_the_admin_panel_is_reachable_without_a_language_prefix(): void
    {
        $this->seed(AdminUserSeeder::class);

        $this->get('/admin')
            ->assertRedirect(route('admin.login'));

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.test',
            'password' => 'very-secure-test-password',
        ]);

        $this->get('/admin')->assertOk()->assertSee('Përmbledhja');
    }
}
