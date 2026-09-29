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

        $response = $this->get('/products');

        $response->assertOk()
            ->assertSee('Solar')
            ->assertSee('RID 220Ah')
            ->assertDontSee('€');
    }

    public function test_products_page_displays_pdf_categories_and_never_shows_prices(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/products');

        $response->assertOk()
            ->assertSee('RID Series OPzS')
            ->assertSee('RID Series OPzV')
            ->assertSee('RID Xtreme / HR Series')
            ->assertSee('RID ST Series (Commercial Vehicles)')
            ->assertSee('RID Motive Power (Forklifts)')
            ->assertSee('Hoppecke Series OPzV')
            ->assertSee('Hoppecke Series OGi')
            ->assertSee('Hoppecke Series GroE')
            ->assertSee('Hoppecke Series FNC (NiCd)')
            ->assertSee('Socomec UPS')
            ->assertSee('RID Power Cell (Storage)')
            ->assertSee('Batteries Diagnostics')
            ->assertSee('Çmimi me kërkesë')
            // Verify no prices or currency symbols are exposed
            ->assertDontSee('€')
            ->assertDontSee('320.00')
            ->assertDontSee('780.00')
            ->assertDontSee('21000.00');
    }

    public function test_products_page_does_not_sell_rid_tester_or_regenerator(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/products');

        $response->assertOk()
            ->assertSee('Batteries Diagnostics')
            ->assertSee('RID BATTERY LOGGER')
            ->assertDontSee('RID BMG-30 BATTERY TESTER')
            ->assertDontSee('RID BRG-20 BATTERY REGENERATOR')
            ->assertDontSee('RID BRG-35 BATTERY REGENERATOR')
            ->assertDontSee('RID BRG-60 BATTERY REGENERATOR');
    }

    public function test_products_can_be_filtered_by_category(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/products?category='.urlencode('RID Series OPzS'));

        $response->assertOk()
            ->assertSee('RID 4 OPzS 200')
            ->assertDontSee('Socomec Emergency UPS System')
            ->assertDontSee('€');
    }

    public function test_products_can_be_searched_by_keyword(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/products?q=Forklifts');

        $response->assertOk()
            ->assertSee('RID Motive Power (Forklifts)')
            ->assertSee('RID 5 PzS - 250 L')
            ->assertDontSee('Socomec Emergency UPS System')
            ->assertDontSee('€');
    }

    public function test_products_can_be_filtered_by_application(): void
    {
        $this->seed(BatterySeeder::class);

        $industrial = $this->get('/products?application=industrial');
        $industrial->assertOk()
            ->assertSee('RID 5 PzS - 250 L')
            ->assertDontSee('Socomec Masterys Emergency UPS')
            ->assertDontSee('€');

        $backupPower = $this->get('/products?application=backup_power');
        $backupPower->assertOk()
            ->assertSee('Socomec Masterys Emergency UPS')
            ->assertDontSee('RID 5 PzS - 250 L')
            ->assertDontSee('€');
    }

    public function test_products_page_renders_the_application_filter_pills(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/products');

        $response->assertOk()
            ->assertSee(config('site.catalog_page.applications_label'))
            ->assertSee('Industriale & Pirunë')
            ->assertSee('Backup Power & UPS')
            ->assertSee('Solar & Energji e Rinovueshme')
            ->assertSee('Auto & Automjete')
            ->assertSee(route('products.index', ['application' => 'solar']), false);
    }

    public function test_products_page_shows_the_detailed_vehicle_finder_form(): void
    {
        $response = $this->get('/products');

        $response->assertOk()
            ->assertSee(config('site.catalog_page.finder_title'))
            ->assertSee('name="v_type"', false)
            ->assertSee('name="v_make"', false)
            ->assertSee('name="v_model"', false)
            ->assertSee('name="v_year"', false)
            ->assertSee('name="v_fuel"', false)
            ->assertSee('name="v_startstop"', false)
            ->assertSee('name="v_capacity"', false)
            ->assertSee('name="vin"', false)
            ->assertSee('name="v_notes"', false)
            ->assertSee(config('site.catalog_page.finder_submit'), false)
            ->assertDontSee('€');
    }

    public function test_vehicle_finder_recommends_agm_for_start_stop_car(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/products?v_type=car&v_make=Volkswagen&v_model=Golf&v_year=2015&v_fuel=petrol&v_startstop=yes');

        $response->assertOk()
            ->assertSee('Kërkim mjeti', false)
            ->assertSee('Bateri AGM me Start-Stop', false)
            ->assertSee('Përshtatje e plotë', false)
            ->assertSee('RID ST AGM 12-60', false)
            ->assertDontSee('RID ST2 12-45', false)
            ->assertDontSee('Socomec Masterys Emergency UPS', false)
            ->assertDontSee('€');
    }

    public function test_vehicle_finder_recommends_heavy_duty_for_diesel_truck(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/products?v_type=truck&v_fuel=diesel&v_startstop=no');

        $response->assertOk()
            ->assertSee('Bateri Heavy-Duty (standard)', false)
            ->assertSee('RID ST3 12-150', false)
            ->assertDontSee('RID ST AGM 12-95', false)
            ->assertDontSee('RID ST EFB 12-105', false)
            ->assertDontSee('€');
    }

    public function test_vehicle_finder_shows_vin_validation_error(): void
    {
        $response = $this->get('/products?vin=12345');

        $response->assertOk()
            ->assertSee('VIN-i duhet të ketë saktësisht 17 karaktere.', false)
            ->assertDontSee(config('site.catalog_page.vin_valid_text'), false);
    }

    public function test_vehicle_finder_decodes_valid_vin_region_and_model_year(): void
    {
        $response = $this->get('/products?vin=WVWZZZ1KZAW000001&v_year=2010');

        $response->assertOk()
            ->assertSee(config('site.catalog_page.vin_valid_text'), false)
            ->assertSee('Evropë', false)
            ->assertSee(config('site.catalog_page.vin_match_text'), false)
            ->assertSee(config('site.catalog_page.vin_confirm_label'), false);
    }

    public function test_products_page_shows_catalog_imagery(): void
    {
        $this->seed(BatterySeeder::class);

        $response = $this->get('/products');

        $response->assertOk()
            ->assertSee('images/battery-start.jpg', false)
            ->assertSee('images/battery-ups.jpg', false)
            ->assertSee('images/battery-pzs.jpg', false)
            ->assertSee('images/battery-generic.svg', false)
            ->assertSee('property="og:image"', false)
            ->assertDontSee('€');
    }
}
