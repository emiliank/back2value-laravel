<?php

namespace Tests\Feature;

use App\Models\SiteContent;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
            ->assertSee('Përmbledhje')
            ->assertSee('<svg class="admin-icon"', false)
            ->assertSee('class="brand-box admin-brand__logo"', false)
            ->assertSee('brand-word">back', false);

        $this->get(route('admin.settings'))->assertOk()->assertSee('Prezantimi dhe ofertat');
        $this->get(route('admin.products'))->assertOk()->assertSee('Produktet dhe imazhet');
        $this->get(route('admin.services'))->assertOk()->assertSee('Menaxhoni shërbimet');
        $this->get(route('admin.about'))->assertOk()->assertSee('Statistikat dhe garancitë');
    }

    public function test_admin_login_uses_the_public_site_wordmark(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('class="brand-box admin-brand__logo"', false)
            ->assertSee('brand-word">back', false)
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
            ->put(route('admin.settings.update'), $this->siteSettings([
                'hero_title' => 'Bateri për çdo biznes',
                'accent_color' => '#15803d',
                'phone' => '+355 68 123 4567',
            ]))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->get('/')
            ->assertOk()
            ->assertSee('Bateri për çdo biznes')
            ->assertSee('+355 68 123 4567')
            ->assertSee('#15803d');
    }

    public function test_admin_can_upload_and_render_a_custom_logo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), array_merge($this->siteSettings(), [
                'logo_image' => UploadedFile::fake()->image('logo.png', 1200, 500),
            ]))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->get('/')
            ->assertOk()
            ->assertSee('Back2Value logo', false)
            ->assertSee('/storage/', false);

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Back2Value logo', false)
            ->assertSee('/storage/', false);
    }

    public function test_settings_reject_invalid_accent_colors(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $this->actingAs($admin)
            ->from(route('admin.settings'))
            ->put(route('admin.settings.update'), $this->siteSettings([
                'accent_color' => 'red; background:url(javascript:alert(1))',
            ]))
            ->assertRedirect(route('admin.settings'))
            ->assertSessionHasErrors('accent_color');
    }

    public function test_admin_can_add_remove_and_upload_product_images(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $products = config('site.products');
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
            ->put(route('admin.products.update'), ['products' => $products])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $storedProducts = SiteContent::query()->where('section', 'products')->firstOrFail()->payload;
        $this->assertCount(3, $storedProducts);
        $this->assertSame('custom-product', $storedProducts[2]['key']);
        $this->assertSame(['Karakteristikë e parë', 'Karakteristikë e dytë'], $storedProducts[2]['features']);
        Storage::disk('public')->assertExists($storedProducts[2]['image']);
        $this->assertSame('Bateri e re për industri', $storedProducts[2]['title']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Bateri e re për industri')
            ->assertDontSee('Bateri Startimi (Flota &amp; Kamionë)');
    }

    public function test_admin_cannot_remove_every_service(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $services = config('site.services');

        foreach ($services as $index => $service) {
            $services[$index]['remove'] = '1';
        }

        $this->actingAs($admin)
            ->from(route('admin.services'))
            ->put(route('admin.services.update'), ['services' => $services])
            ->assertRedirect(route('admin.services'))
            ->assertSessionHasErrors('services');
    }

    public function test_admin_can_update_services_about_content_and_statistics(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);
        $services = config('site.services');
        $about = config('site.about');
        $stats = config('site.stats');
        $trust = config('site.trust');

        $services[0]['title'] = 'Kontroll i baterisë';
        $about['title'] = 'Pse të na zgjidhni?';
        $stats[0]['value'] = '3yr';
        $trust['subtitle'] = 'Origjinale nga Gjermania';

        $this->actingAs($admin)
            ->put(route('admin.services.update'), ['services' => $services])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->put(route('admin.about.update'), [
            'about' => $about,
            'stats' => $stats,
            'trust' => $trust,
        ])
            ->assertSessionHasNoErrors()
            ->assertSessionHas('status');

        $this->get('/')
            ->assertOk()
            ->assertSee('Kontroll i baterisë')
            ->assertSee('Pse të na zgjidhni?')
            ->assertSee('3yr')
            ->assertSee('Origjinale nga Gjermania');
    }

    public function test_admin_cannot_change_site_content_sections_outside_the_allow_list(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $response = $this->actingAs($admin)
            ->put(route('admin.settings.update'), $this->siteSettings([
                'password' => 'attempted-injection',
            ]));

        $response->assertSessionHasNoErrors();
        $settings = SiteContent::query()->where('section', 'settings')->firstOrFail()->payload;
        $this->assertArrayNotHasKey('password', $settings);
    }

    /**
     * @return array<string, string>
     */
    private function siteSettings(array $overrides = []): array
    {
        return array_merge(config('site.settings'), $overrides);
    }
}
