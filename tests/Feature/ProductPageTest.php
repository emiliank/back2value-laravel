<?php

namespace Tests\Feature;

use App\Models\Battery;
use Database\Seeders\BatterySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_page_groups_batteries_by_category_without_prices(): void
    {
        Battery::factory()->create([
            'brand' => 'RID',
            'capacity_ah' => 220,
            'application_type' => 'solar',
            'status' => 'new',
            'is_available' => true,
            'sale_price' => 780.00,
            'warranty_months' => 24,
        ]);

        $response = $this->get('/sq/products');

        $response->assertOk()
            ->assertSee('Solar')
            ->assertSee('RID 220Ah')
            ->assertDontSee('€');
    }

    public function test_products_page_displays_pdf_categories_and_never_shows_prices(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/sq/products');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Products',
            'RID Series OPzS',
            'RID Series OPzV',
            'RID Xtreme / HR Series',
            'RID ST Series (Commercial Vehicles)',
            'RID Motive Power (Forklifts)',
            'Hoppecke Series OPzV',
            'Hoppecke Series OGi',
            'Hoppecke Series GroE',
            'Hoppecke Series FNC (NiCd)',
            'Socomec UPS',
            'RID Power Cell (Storage)',
            'Çmimi me kërkesë',
        );
        $this->assertInertiaComponentDoesNotContain(
            $response,
            'Public/Products',
            'Batteries Diagnostics',
            '€',
            '320.00',
            '780.00',
            '21000.00',
        );
    }

    public function test_products_page_does_not_sell_rid_tester_or_regenerator(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/sq/products');

        $response->assertOk()
            ->assertDontSee('Batteries Diagnostics')
            ->assertDontSee('RID BATTERY LOGGER')
            ->assertDontSee('RID BMG-30 BATTERY TESTER')
            ->assertDontSee('RID BRG-20 BATTERY REGENERATOR')
            ->assertDontSee('RID BRG-35 BATTERY REGENERATOR')
            ->assertDontSee('RID BRG-60 BATTERY REGENERATOR');
    }

    public function test_products_can_be_filtered_by_category(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/sq/products?category='.urlencode('RID Series OPzS'));

        $response->assertOk()
            ->assertSee('RID 4 OPzS 200')
            ->assertDontSee('Socomec Emergency UPS System')
            ->assertDontSee('€');
    }

    public function test_products_can_be_searched_by_keyword(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/sq/products?q=Forklifts');

        $response->assertOk()
            ->assertSee('RID Motive Power (Forklifts)')
            ->assertSee('RID 5 PzS - 250 L')
            ->assertDontSee('Socomec Emergency UPS System')
            ->assertDontSee('€');
    }

    public function test_products_can_be_filtered_by_application(): void
    {
        $this->seed(BatterySeeder::class);

        $industrial = $this->get('/sq/products?application=industrial');
        $industrial->assertOk()
            ->assertSee('RID 5 PzS - 250 L')
            ->assertDontSee('Socomec Masterys Emergency UPS')
            ->assertDontSee('€');

        $backupPower = $this->get('/sq/products?application=backup_power');
        $backupPower->assertOk()
            ->assertSee('Socomec Masterys Emergency UPS')
            ->assertDontSee('RID 5 PzS - 250 L')
            ->assertDontSee('€');
    }

    public function test_products_page_renders_the_application_filter_pills(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/sq/products');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Products',
            config('site.sq.catalog_page.applications_label'),
            'Industriale & Pirunë',
            'Backup Power & UPS',
            'Solar & Energji e Rinovueshme',
            'Auto & Automjete',
        );
    }

    public function test_products_page_shows_the_detailed_vehicle_finder_form(): void
    {
        $response = $this->get('/sq/products');

        $props = $this->assertInertiaComponent($response, 'Public/Products');

        $this->assertSame(config('site.sq.catalog_page.finder_title'), $props['page']['finder_title']);
        $this->assertSame(config('site.sq.catalog_page.finder_submit'), $props['page']['finder_submit']);
        $this->assertArrayHasKey('types', $props['vehicleOptions']);
        $this->assertArrayHasKey('makes', $props['vehicleOptions']);
        $this->assertArrayHasKey('years', $props['vehicleOptions']);
        $this->assertInertiaComponentDoesNotContain($response, 'Public/Products', '€');
    }

    public function test_vehicle_finder_recommends_agm_for_start_stop_car(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/sq/products?v_type=car&v_make=Volkswagen&v_model=Golf&v_year=2015&v_fuel=petrol&v_startstop=yes');

        $props = $this->assertInertiaComponent($response, 'Public/Products');

        $this->assertSame('agm', $props['vehicleProfile']['recommendation']['technology']);
        $this->assertSame('exact', $props['vehicleProfile']['match_level']);
        $this->assertSame('Bateri AGM me Start-Stop', $props['vehicleProfile']['recommendation']['label']);
        $this->assertInertiaComponentContains($response, 'Public/Products', 'RID ST AGM 12-60', 'Përshtatje e plotë');
        $this->assertInertiaComponentDoesNotContain($response, 'Public/Products', 'RID ST2 12-45', 'Socomec Masterys Emergency UPS', '€');
    }

    public function test_vehicle_finder_recommends_heavy_duty_for_diesel_truck(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/sq/products?v_type=truck&v_fuel=diesel&v_startstop=no');

        $response->assertOk()
            ->assertSee('Bateri Heavy-Duty (standard)', false)
            ->assertSee('RID ST3 12-150', false)
            ->assertDontSee('RID ST AGM 12-95', false)
            ->assertDontSee('RID ST EFB 12-105', false)
            ->assertDontSee('€');
    }

    public function test_vehicle_finder_shows_vin_validation_error(): void
    {
        $response = $this->get('/sq/products?vin=12345');

        $props = $this->assertInertiaComponent($response, 'Public/Products');

        $this->assertSame('invalid', $props['vehicleProfile']['vin_info']['state']);
        $this->assertSame('VIN-i duhet të ketë saktësisht 17 karaktere.', $props['vehicleProfile']['vin_info']['message']);
    }

    public function test_vehicle_finder_decodes_valid_vin_region_and_model_year(): void
    {
        $response = $this->get('/sq/products?vin=WVWZZZ1KZAW000001&v_year=2010');

        $props = $this->assertInertiaComponent($response, 'Public/Products');

        $this->assertSame('valid', $props['vehicleProfile']['vin_info']['state']);
        $this->assertSame('Evropë', $props['vehicleProfile']['vin_info']['region']);
        $this->assertSame(config('site.sq.catalog_page.vin_valid_text'), $props['page']['vin_valid_text']);
        $this->assertSame(config('site.sq.catalog_page.vin_match_text'), $props['page']['vin_match_text']);
        $this->assertSame(config('site.sq.catalog_page.vin_confirm_label'), $props['page']['vin_confirm_label']);
    }

    public function test_products_page_shows_catalog_imagery(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/sq/products');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Products',
            'images/battery-start.jpg',
            'images/battery-ups.jpg',
            'images/battery-pzs.jpg',
        );
        $this->assertInertiaComponentDoesNotContain(
            $response,
            'Public/Products',
            'images/battery-generic.svg',
            'Battery Diagnostic Service',
            '€',
        );
        $response->assertSee('property="og:image"', false);
    }
}
