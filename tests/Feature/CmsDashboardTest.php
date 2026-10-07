<?php

namespace Tests\Feature;

use App\Content\ContentSchema;
use App\Models\SiteContent;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CmsDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'admin.email' => 'admin@example.test',
            'admin.password' => 'very-secure-test-password',
        ]);
        $this->seed(SiteContentSeeder::class);
    }

    public function test_admin_pages_redirect_guests_to_the_login_page(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_open_every_content_management_page(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Mirë se vini në panelin e përmbajtjes')
            ->assertSee('<svg class="admin-icon"', false)
            ->assertSee('class="brand-box admin-brand__logo"', false);

        foreach (ContentSchema::pages() as $slug => $definition) {
            $this->get(route('admin.content.edit', ['page' => $slug]))
                ->assertOk()
                ->assertSee($definition['label']);
        }
    }

    public function test_legacy_admin_urls_redirect_to_the_schema_editor(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.settings'))->assertRedirect(route('admin.content.edit', ['page' => 'settings']));
        $this->get(route('admin.products'))->assertRedirect(route('admin.catalog.index'));
        $this->get(route('admin.services'))->assertRedirect(route('admin.content.edit', ['page' => 'services']));
        $this->get(route('admin.about'))->assertRedirect(route('admin.content.edit', ['page' => 'about']));
    }

    public function test_admin_login_uses_the_public_site_wordmark(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('class="brand-box admin-brand__logo"', false)
            ->assertSee('<svg class="admin-icon"', false);
    }

    public function test_non_admin_users_cannot_open_admin_pages(): void
    {
        $user = User::factory()->create([
            'email' => 'someone@example.test',
        ]);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_only_the_configured_admin_can_sign_in(): void
    {
        $this->seed(AdminUserSeeder::class);

        $this->from(route('admin.login'))
            ->post(route('admin.login.store'), [
                'email' => 'someone@example.test',
                'password' => 'very-secure-test-password',
            ])
            ->assertRedirect(route('admin.login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();

        $this->post(route('admin.login.store'), [
            'email' => 'admin@example.test',
            'password' => 'very-secure-test-password',
        ])
            ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs(User::query()->where('email', 'admin@example.test')->firstOrFail());
    }

    public function test_admin_can_save_site_settings_and_public_homepage_uses_them(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'settings']), [
                'settings' => $this->siteSettings([
                    'hero_title' => 'Bateri për çdo biznes',
                    'accent_color' => '#15803d',
                    'phone' => '+355 68 123 4567',
                ]),
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->get('/sq/')
            ->assertOk()
            ->assertSee('Bateri për çdo biznes')
            ->assertSee('+355 68 123 4567')
            ->assertSee('#15803d');
    }

    public function test_admin_can_save_navigation_changes_when_another_text_field_is_empty(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $navigation = config('site.sq.navigation');
        $navigation['header_items'][0]['label'] = 'Bateritë tona';
        $navigation['header_cta_primary'] = '';

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'navigation']), [
                'navigation' => $navigation,
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $saved = SiteContent::query()->where('section', 'navigation')->where('locale', 'sq')->value('payload');

        $this->assertSame('Bateritë tona', $saved['header_items'][0]['label']);
        $this->assertNull($saved['header_cta_primary']);
        $this->get('/sq/')->assertSee('Bateritë tona');
    }

    public function test_admin_can_save_with_browser_style_dotted_form_keys(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin);

        // A real browser posts every field on the page as name="settings.group.field",
        // and PHP keeps those keys flat instead of nesting them.
        $this->post(route('admin.content.update', ['page' => 'settings']), [
            '_method' => 'PUT',
            ...Arr::dot(['settings' => $this->siteSettings([
                'hero_title' => 'Bateri nga browseri',
                'phone' => '+355 68 000 1111',
            ])]),
        ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->get('/sq/')
            ->assertOk()
            ->assertSee('Bateri nga browseri')
            ->assertSee('+355 68 000 1111');
    }

    public function test_saving_a_page_with_only_unchecked_fields_does_not_error(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.content.update', ['page' => 'settings']), ['_method' => 'PUT'])
            ->assertRedirect(route('admin.content.edit', ['page' => 'settings']));
    }

    public function test_group_sections_submit_bracket_names_not_dotted_names(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        // PHP rewrites "." to "_" in field names, so a dotted name would never
        // arrive intact and the section would be silently skipped.
        $html = $this->actingAs($admin)
            ->get(route('admin.content.edit', ['page' => 'settings']))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('name="settings[hero_title]"', $html);
        $this->assertStringNotContainsString('name="settings.hero_title"', $html);
    }

    public function test_admin_can_save_blank_multi_line_content(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $settings = $this->siteSettings(['products_description' => '']);

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'settings']), ['settings' => $settings])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $saved = SiteContent::query()->where('section', 'settings')->where('locale', 'sq')->value('payload');

        $this->assertNull($saved['products_description']);
    }

    public function test_admin_can_upload_and_render_a_custom_logo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'settings']), [
                'settings' => array_merge($this->siteSettings(), [
                    'logo_image' => UploadedFile::fake()->image('logo.png', 1200, 500),
                ]),
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->get('/sq/')
            ->assertOk()
            ->assertSee('Back2Value logo', false)
            ->assertSee('/storage/', false);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Back2Value logo', false)
            ->assertSee('/storage/', false);
    }

    public function test_admin_can_upload_a_partner_logo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $partners = config('site.sq.partners');
        $partners['items'][0]['logo'] = UploadedFile::fake()->image('rid-partner.png', 480, 240);
        $partners['items'][0]['logo_alt'] = 'RID Batterie logo';
        $partners['items'][0]['url'] = 'https://partner.example.test/about';
        $partners['items'][0]['logo_background'] = 'white';

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'homepage']), ['partners' => $partners])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $saved = SiteContent::query()->where('section', 'partners')->where('locale', 'sq')->value('payload');

        $this->assertSame('RID Batterie logo', $saved['items'][0]['logo_alt']);
        $this->assertSame('https://partner.example.test/about', $saved['items'][0]['url']);
        $this->assertSame('white', $saved['items'][0]['logo_background']);
        Storage::disk('public')->assertExists($saved['items'][0]['logo']);

        $this->get('/sq/')
            ->assertOk()
            ->assertSee('data-partner-marquee', false)
            ->assertSee('RID Batterie logo', false)
            ->assertSee('href="https://partner.example.test/about"', false)
            ->assertSee('partner-card__logo--white', false)
            ->assertSee(Storage::disk('public')->url($saved['items'][0]['logo']), false);
    }

    public function test_partner_logo_defaults_to_black_background_blending(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $partners = config('site.sq.partners');
        $partners['items'][0]['logo'] = UploadedFile::fake()->image('partner.png', 480, 240);
        $partners['items'][0]['logo_background'] = null;

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'homepage']), ['partners' => $partners])
            ->assertSessionHasNoErrors();

        $this->get('/sq/')
            ->assertOk()
            ->assertSee('partner-card__logo--black', false);
    }

    public function test_partner_marquee_shows_only_partners_that_have_a_logo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $partners = config('site.sq.partners');
        $partners['items'][0]['logo'] = UploadedFile::fake()->image('partner.png', 480, 240);
        $partners['items'][1]['logo'] = null;
        $partners['items'][1]['logo_remove'] = '1';
        $withoutLogo = $partners['items'][1]['name'];

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'homepage']), ['partners' => $partners])
            ->assertSessionHasNoErrors();

        $response = $this->get('/sq/')
            ->assertOk()
            ->assertSee('data-partner-marquee', false)
            ->assertSee('data-partner-marquee-group', false)
            ->assertDontSee($withoutLogo);
    }

    public function test_admin_can_remove_a_partner_logo_from_the_english_editor(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $partners = config('site.en.partners');
        $partners['items'][0]['logo'] = UploadedFile::fake()->image('partner.png', 480, 240);

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'homepage', 'locale' => 'en']), ['partners' => $partners])
            ->assertSessionHasNoErrors();

        $saved = SiteContent::query()->where('section', 'partners')->where('locale', 'en')->value('payload');
        $logoPath = $saved['items'][0]['logo'];
        Storage::disk('public')->assertExists($logoPath);

        $browserRows = [];

        foreach ($saved['items'] as $index => $item) {
            $browserRows['item-'.$index] = array_merge($item, [
                'remove' => '0',
                'logo_path' => $item['logo'],
            ]);
        }
        $browserRows['item-0']['logo_remove'] = '1';

        $html = $this->get(route('admin.content.edit', ['page' => 'homepage', 'locale' => 'en']))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('name="partners[items][item-0][logo_remove]"', $html);

        $this->put(route('admin.content.update', ['page' => 'homepage', 'locale' => 'en']), [
            'partners' => array_merge($saved, ['items' => $browserRows]),
        ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $after = SiteContent::query()->where('section', 'partners')->where('locale', 'en')->value('payload');

        $this->assertNull($after['items'][0]['logo']);
        Storage::disk('public')->assertMissing($logoPath);
    }

    public function test_admin_can_remove_a_partner_row_without_adding_another(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $partners = config('site.en.partners');
        $removedName = $partners['items'][0]['name'];
        $remainingRows = [];

        foreach ($partners['items'] as $index => $item) {
            $remainingRows['item-'.$index] = array_merge($item, [
                'remove' => $index === 0 ? '1' : '0',
            ]);
        }

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'homepage', 'locale' => 'en']), [
                'partners' => array_merge($partners, ['items' => $remainingRows]),
            ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $saved = SiteContent::query()->where('section', 'partners')->where('locale', 'en')->value('payload');

        $this->assertCount(count($partners['items']) - 1, $saved['items']);
        $this->assertNotSame($removedName, $saved['items'][0]['name']);
    }

    public function test_partially_updating_partner_rows_preserves_existing_rows(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $partners = config('site.sq.partners');
        $partners['items'][0]['logo'] = UploadedFile::fake()->image('partner-a.png', 480, 240);
        $partners['items'][1]['logo'] = UploadedFile::fake()->image('partner-b.png', 480, 240);
        $partners['items'][2]['logo'] = UploadedFile::fake()->image('partner-c.png', 480, 240);

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'homepage']), ['partners' => $partners])
            ->assertSessionHasNoErrors();

        $saved = SiteContent::query()->where('section', 'partners')->where('locale', 'sq')->value('payload');
        $partial = [
            'eyebrow' => $saved['eyebrow'],
            'title' => $saved['title'],
            'description' => $saved['description'],
            'items' => [
                'item-0' => [
                    'name' => 'Updated RID partner',
                    'country' => $saved['items'][0]['country'],
                    'description' => $saved['items'][0]['description'],
                    'logo' => null,
                    'logo_path' => $saved['items'][0]['logo'],
                    'logo_alt' => $saved['items'][0]['logo_alt'] ?? null,
                    'url' => $saved['items'][0]['url'] ?? null,
                    'logo_background' => $saved['items'][0]['logo_background'] ?? 'black',
                    'remove' => '0',
                ],
            ],
        ];

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'homepage']), ['partners' => $partial])
            ->assertSessionHasNoErrors();

        $after = SiteContent::query()->where('section', 'partners')->where('locale', 'sq')->value('payload');

        $this->assertCount(count($saved['items']), $after['items']);
        $this->assertSame('Updated RID partner', $after['items'][0]['name']);
        $this->assertSame($saved['items'][1]['name'], $after['items'][1]['name']);
        $this->assertSame($saved['items'][2]['name'], $after['items'][2]['name']);
    }

    public function test_admin_cannot_save_a_partner_link_with_a_non_http_scheme(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $partners = config('site.sq.partners');
        $partners['items'][0]['url'] = 'javascript:alert(1)';

        $this->actingAs($admin)
            ->from(route('admin.content.edit', ['page' => 'homepage']))
            ->put(route('admin.content.update', ['page' => 'homepage']), ['partners' => $partners])
            ->assertSessionHasErrors('partners.items.0.url');
    }

    public function test_saving_homepage_preserves_process_outcome_keys(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $process = config('site.sq.circular_process');

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'homepage']), ['circular_process' => $process])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $saved = SiteContent::query()->where('section', 'circular_process')->where('locale', 'sq')->value('payload');

        $this->assertSame('resale', data_get($saved, 'outcomes.0.key'));
        $this->get('/sq/')->assertOk();
    }

    public function test_homepage_renders_saved_process_outcomes_without_keys(): void
    {
        $content = SiteContent::query()->where('section', 'circular_process')->where('locale', 'sq')->firstOrFail();
        $payload = $content->payload;

        foreach ($payload['outcomes'] as &$outcome) {
            unset($outcome['key']);
        }
        unset($outcome);

        $content->payload = $payload;
        $content->save();

        $this->get('/sq/')
            ->assertOk()
            ->assertSee('process-outcome--resale', false)
            ->assertSee('process-outcome--recycling', false);
    }

    public function test_settings_reject_invalid_accent_colors(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.content.edit', ['page' => 'settings']))
            ->put(route('admin.content.update', ['page' => 'settings']), [
                'settings' => $this->siteSettings([
                    'accent_color' => 'red; background:url(javascript:alert(1))',
                ]),
            ])
            ->assertRedirect(route('admin.content.edit', ['page' => 'settings']))
            ->assertSessionHasErrors('settings.accent_color');
    }

    public function test_repeater_rows_render_editable_inputs_with_well_formed_names(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.content.edit', ['page' => 'products']))
            ->assertOk()
            ->assertSee('name="products[item-0][title]"', false)
            ->assertSee('name="products[__KEY__][title]"', false)
            ->assertSee('data-add-row="template-', false);
    }

    public function test_nested_navigation_repeater_uses_php_safe_field_names(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.content.edit', ['page' => 'navigation']))
            ->assertOk()
            ->assertSee('name="navigation[header_items][item-0][label]"', false)
            ->assertDontSee('name="navigation.header_items[item-0][label]"', false);
    }

    public function test_admin_can_add_and_remove_a_product(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $rows = [];

        foreach (config('site.sq.products') as $index => $product) {
            $row = $product;
            $row['features'] = implode("\n", $row['features']);
            $rows['item-'.$index] = $row;
        }

        unset($rows['item-1']);

        $rows['item-9'] = [
            'key' => '',
            'title' => 'Bateri e re nga admini',
            'description' => 'Shtuar nga testi.',
            'features' => "Karakteristikë A\nKarakteristikë B",
            'image_alt' => 'Bateri e re',
            'sort_order' => 9,
        ];

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'products']), ['products' => $rows])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $stored = SiteContent::query()->where('section', 'products')->where('locale', 'sq')->value('payload');

        $this->assertCount(3, $stored);
        $this->assertSame('Bateri e re nga admini', $stored[2]['title']);
        $this->assertSame(['Karakteristikë A', 'Karakteristikë B'], $stored[2]['features']);
        $this->assertArrayNotHasKey('item-9', $stored);
    }

    public function test_admin_can_reset_a_page_to_the_shipped_defaults(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'settings']), [
                'settings' => $this->siteSettings(['hero_title' => 'Bateri për çdo biznes']),
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('site_contents', ['section' => 'settings', 'locale' => 'sq']);

        $this->post(route('admin.content.reset', ['page' => 'settings']))
            ->assertRedirect(route('admin.content.edit', ['page' => 'settings']))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('site_contents', ['section' => 'settings', 'locale' => 'sq']);
        $this->assertDatabaseHas('site_contents', ['section' => 'settings', 'locale' => 'en']);
    }

    public function test_admin_can_add_remove_and_upload_product_images(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $products = config('site.sq.products');
        unset($products[1]);

        foreach ($products as &$product) {
            $product['features'] = implode("\n", $product['features']);
            unset($product['image']);
        }
        unset($product);

        $products['custom-product'] = [
            'key' => 'custom-product',
            'title' => 'Bateri e re për industri',
            'description' => 'Përshkrimi i baterisë së re.',
            'features' => 'Karakteristikë e parë'."\n".'Karakteristikë e dytë',
            'image_alt' => 'Bateri e re për industri',
            'sort_order' => 3,
            'image' => UploadedFile::fake()->image('product.png', 640, 480),
        ];
        $products[0]['sort_order'] = 2;
        $products[2]['sort_order'] = 1;
        $products[2]['remove'] = '1';

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'products']), ['products' => $products])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $storedProducts = SiteContent::query()->where('section', 'products')->where('locale', 'sq')->firstOrFail()->payload;
        $this->assertCount(2, $storedProducts);
        $this->assertSame('custom-product', $storedProducts[1]['key']);
        $this->assertSame(['Karakteristikë e parë', 'Karakteristikë e dytë'], $storedProducts[1]['features']);
        Storage::disk('public')->assertExists($storedProducts[1]['image']);
        $this->assertSame('Bateri e re për industri', $storedProducts[1]['title']);

        $this->get('/sq/')
            ->assertOk()
            ->assertDontSee('Bateri e re për industri')
            ->assertDontSee('Bateri Startimi (Flota &amp; Kamionë)');
    }

    public function test_admin_cannot_remove_every_service(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $services = config('site.sq.services');

        foreach ($services as $index => $service) {
            $services[$index]['remove'] = '1';
        }

        $this->actingAs($admin)
            ->from(route('admin.content.edit', ['page' => 'services']))
            ->put(route('admin.content.update', ['page' => 'services']), ['services' => $services])
            ->assertRedirect(route('admin.content.edit', ['page' => 'services']))
            ->assertSessionHasErrors('services');
    }

    public function test_admin_cannot_remove_every_service_from_the_browser_repeater(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $existing = SiteContent::query()->where('section', 'services')->where('locale', 'sq')->value('payload');

        $this->actingAs($admin)
            ->from(route('admin.content.edit', ['page' => 'services']))
            ->put(route('admin.content.update', ['page' => 'services']), [
                'services' => ['item-0' => ['remove' => '1']],
            ])
            ->assertRedirect(route('admin.content.edit', ['page' => 'services']))
            ->assertSessionHasErrors('services');

        $this->assertSame($existing, SiteContent::query()->where('section', 'services')->where('locale', 'sq')->value('payload'));
    }

    public function test_admin_can_update_services_about_content_and_statistics(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $services = config('site.sq.services');
        $about = config('site.sq.about');
        $stats = config('site.sq.stats');

        $services[0]['title'] = 'Kontroll i baterisë';
        $about['title'] = 'Pse të na zgjidhni?';
        $about['faqs'][0]['question'] = 'Si kontaktoj Back2Value?';
        $stats['items'][0]['value'] = '3yr';

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'services']), ['services' => $services])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->put(route('admin.content.update', ['page' => 'about']), [
            'about' => $about,
            'stats' => $stats,
            'trust' => config('site.sq.trust'),
        ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->get('/sq/')
            ->assertOk()
            ->assertSee('Kontroll i baterisë')
            ->assertSee('Pse të na zgjidhni?')
            ->assertSee('3yr');

        $this->get('/sq/resources')
            ->assertOk()
            ->assertSee('Si kontaktoj Back2Value?');
    }

    public function test_admin_can_edit_the_trust_band_items(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $trust = config('site.sq.trust');
        $trust['items'][0]['text'] = 'Origjinale nga Gjermania';

        $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'about']), ['trust' => $trust])
            ->assertSessionHasNoErrors();

        $this->get('/sq/')->assertOk()->assertSee('Origjinale nga Gjermania');
    }

    public function test_admin_cannot_change_site_content_sections_outside_the_allow_list(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.content.update', ['page' => 'settings']), [
                'settings' => $this->siteSettings(['password' => 'attempted-injection']),
            ]);

        $response->assertSessionHasNoErrors();
        $settings = SiteContent::query()->where('section', 'settings')->firstOrFail()->payload;
        $this->assertArrayNotHasKey('password', $settings);
    }

    public function test_unknown_editor_pages_are_rejected(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.content.edit', ['page' => 'not-a-page']))
            ->assertNotFound();
    }

    /**
     * @return array<string, string>
     */
    private function siteSettings(array $overrides = []): array
    {
        return array_merge(config('site.sq.settings'), $overrides);
    }
}
