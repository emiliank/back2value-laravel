<?php

namespace Tests\Feature;

use App\Models\Battery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['admin.email' => 'admin@example.test']);
    }

    private function admin(): User
    {
        $user = User::factory()->create(['email' => 'admin@example.test']);

        $this->actingAs($user);

        return $user;
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'brand' => 'RID',
            'model' => 'OPzV 1200',
            'serial_number' => 'BAT-0001',
            'category' => 'RID Series OPzV',
            'technology' => 'OPzV',
            'capacity_ah' => 1200,
            'voltage' => '2V',
            'application_type' => 'solar',
            'status' => 'new',
            'purchase_price' => 400,
            'sale_price' => 750,
            'warranty_months' => 24,
            'description' => 'Bateri për aplikime diellore.',
            'is_available' => '1',
            'stock_status' => 'in_stock',
            'specs' => [
                'dimensions' => '147 x 208 x 400',
                'weight' => '52 kg',
                'voltage' => '',
                'cca' => '',
                'design_life' => '12 vjet',
                'cycles' => '',
            ],
        ], $overrides);
    }

    public function test_guests_are_redirected_to_the_login_page(): void
    {
        $this->get(route('admin.catalog.index'))->assertRedirect(route('admin.login'));
    }

    public function test_index_lists_batteries_with_search_and_filters(): void
    {
        $this->admin();

        Battery::factory()->create(['model' => 'GroE 500', 'brand' => 'Hoppecke', 'status' => 'new']);
        Battery::factory()->create(['model' => 'FNC 100', 'brand' => 'Hoppecke', 'status' => 'new']);
        Battery::factory()->create(['model' => 'Socomec 40', 'brand' => 'Socomec', 'category' => 'Socomec UPS', 'status' => 'reactivated']);

        $this->get(route('admin.catalog.index'))
            ->assertOk()
            ->assertSee('GroE 500')
            ->assertSee('FNC 100')
            ->assertSee('Socomec 40');

        $this->get(route('admin.catalog.index', ['q' => 'GroE']))
            ->assertOk()
            ->assertSee('GroE 500')
            ->assertDontSee('FNC 100');

        $this->get(route('admin.catalog.index', ['brand' => 'Socomec']))
            ->assertOk()
            ->assertSee('Socomec 40')
            ->assertDontSee('GroE 500');

        $this->get(route('admin.catalog.index', ['category' => 'Socomec UPS']))
            ->assertOk()
            ->assertSee('Socomec 40')
            ->assertDontSee('GroE 500');

        $this->get(route('admin.catalog.index', ['status' => 'reactivated']))
            ->assertOk()
            ->assertSee('Socomec 40')
            ->assertDontSee('GroE 500');
    }

    public function test_index_shows_totals_and_a_create_link(): void
    {
        $this->admin();

        Battery::factory()->count(3)->create(['is_available' => true]);
        Battery::factory()->create(['is_available' => false]);

        $this->get(route('admin.catalog.index'))
            ->assertOk()
            ->assertSee(route('admin.catalog.create'))
            ->assertSee('4')
            ->assertSee('3');
    }

    public function test_create_form_renders(): void
    {
        $this->admin();

        $this->get(route('admin.catalog.create'))
            ->assertOk()
            ->assertSee('Bateri i ri')
            ->assertSee('name="brand"', false)
            ->assertSee('name="specs[dimensions]"', false);
    }

    public function test_admin_can_create_a_battery(): void
    {
        $this->admin();

        $this->post(route('admin.catalog.store'), $this->validPayload())
            ->assertRedirect(route('admin.catalog.index'))
            ->assertSessionHas('status');

        $battery = Battery::query()->where('model', 'OPzV 1200')->sole();

        $this->assertSame('RID', $battery->brand);
        $this->assertSame(1200, $battery->capacity_ah);
        $this->assertSame('new', $battery->status);
        $this->assertTrue($battery->is_available);
        $this->assertSame('52 kg', $battery->specs['weight']);
        $this->assertArrayNotHasKey('voltage', $battery->specs);
    }

    public function test_creating_a_battery_requires_a_brand_model_capacity_and_status(): void
    {
        $this->admin();

        $this->post(route('admin.catalog.store'), [])
            ->assertSessionHasErrors(['brand', 'model', 'capacity_ah', 'status']);

        $this->assertDatabaseCount('batteries', 0);
    }

    public function test_unavailable_flag_is_persisted_when_unchecked(): void
    {
        $this->admin();

        $this->post(route('admin.catalog.store'), $this->validPayload(['is_available' => null]))
            ->assertRedirect(route('admin.catalog.index'));

        $this->assertFalse(Battery::query()->sole()->is_available);
    }

    public function test_admin_can_edit_a_battery(): void
    {
        $this->admin();

        $battery = Battery::factory()->create(['model' => 'Old model']);

        $this->get(route('admin.catalog.edit', ['battery' => $battery]))
            ->assertOk()
            ->assertSee('Old model');

        $this->put(route('admin.catalog.update', ['battery' => $battery]), $this->validPayload([
            'model' => 'GroE 500',
            'status' => 'reactivated',
            'is_available' => null,
        ]))->assertRedirect(route('admin.catalog.index'));

        $battery->refresh();

        $this->assertSame('GroE 500', $battery->model);
        $this->assertSame('reactivated', $battery->status);
        $this->assertFalse($battery->is_available);
    }

    public function test_admin_can_delete_a_battery(): void
    {
        $this->admin();

        $battery = Battery::factory()->create();

        $this->delete(route('admin.catalog.destroy', ['battery' => $battery]))
            ->assertRedirect(route('admin.catalog.index'));

        $this->assertDatabaseMissing('batteries', ['id' => $battery->id]);
    }

    public function test_edited_batteries_appear_on_the_public_catalog_page(): void
    {
        $this->admin();

        $battery = Battery::factory()->create([
            'model' => 'Prizë e vjetër',
            'is_available' => true,
            'sale_price' => 500,
        ]);

        $this->put(route('admin.catalog.update', ['battery' => $battery]), $this->validPayload([
            'model' => 'OPzV 1200 E Re',
            'is_available' => '1',
        ]))->assertRedirect(route('admin.catalog.index'));

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('OPzV 1200 E Re')
            ->assertDontSee('Prizë e vjetër');
    }

    public function test_legacy_admin_products_url_points_at_the_catalog(): void
    {
        $this->admin();

        $this->get(route('admin.products'))
            ->assertRedirect(route('admin.catalog.index'));
    }

    public function test_admin_can_set_a_stock_status_on_a_battery(): void
    {
        $this->admin();

        $battery = Battery::factory()->create(['model' => 'OPzS 1500', 'is_available' => true]);

        $this->put(route('admin.catalog.update', ['battery' => $battery]), $this->validPayload([
            'model' => 'OPzS 1500',
            'stock_status' => 'low_stock',
        ]))->assertRedirect(route('admin.catalog.index'));

        $this->assertSame('low_stock', $battery->fresh()->stock_status);
    }

    public function test_stock_status_must_be_one_of_the_supported_values(): void
    {
        $this->admin();

        $this->post(route('admin.catalog.store'), $this->validPayload([
            'stock_status' => 'shipped_by_unicorn',
        ]))->assertSessionHasErrors('stock_status');

        $this->assertDatabaseCount('batteries', 0);
    }

    public function test_stock_status_is_required(): void
    {
        $this->admin();

        $this->post(route('admin.catalog.store'), $this->validPayload([
            'stock_status' => '',
        ]))->assertSessionHasErrors('stock_status');

        $this->assertDatabaseCount('batteries', 0);
    }

    public function test_out_of_stock_products_stay_visible_with_a_preorder_badge(): void
    {
        Battery::factory()->outOfStock()->create([
            'model' => 'Ndryshim Solar',
            'category' => 'Solar',
            'is_available' => true,
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Ndryshim Solar')
            ->assertSee('Jashtë stokut');
    }

    public function test_in_stock_products_are_not_labelled_as_preorder(): void
    {
        Battery::factory()->create([
            'model' => 'Në Stok Normal',
            'category' => 'Auto',
            'is_available' => true,
            'stock_status' => 'in_stock',
        ]);

        $this->get(route('products.index'))
            ->assertOk()
            ->assertSee('Në stok')
            ->assertDontSee('Ndryshim Solar');
    }

    public function test_needing_preorder_scope_covers_preorder_and_out_of_stock(): void
    {
        $inStock = Battery::factory()->create(['stock_status' => 'in_stock']);
        $lowStock = Battery::factory()->create(['stock_status' => 'low_stock']);
        $onPreorder = Battery::factory()->onPreorder()->create();
        $outOfStock = Battery::factory()->outOfStock()->create();

        $ids = Battery::query()->needingPreorder()->pluck('id')->all();

        $this->assertEqualsCanonicalizing([$onPreorder->id, $outOfStock->id], $ids);
        $this->assertFalse($inStock->needsPreorder());
        $this->assertFalse($lowStock->needsPreorder());
        $this->assertTrue($onPreorder->needsPreorder());
        $this->assertTrue($outOfStock->needsPreorder());
    }
}
