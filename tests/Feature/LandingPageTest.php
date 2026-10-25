<?php

namespace Tests\Feature;

use App\Models\Battery;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_back2value_content_and_links_to_products(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/sq/');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Home',
            'Bateri gjermane. Garanci reale. Shërbim vendor.',
            'Back2Value',
            route('products.index'),
            'Me anë të RID Tester kontrollohet, testohet dhe diagnostikohet bateria, rigjenerohet',
        );
    }

    public function test_home_page_shows_value_props_diagnostics_teaser_and_partners(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/sq/');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Home',
            'Rikthejmë 50–100% të kapacitetit origjinal',
            'Kapacitet i rikthyer',
            'Rezervo Diagnostikim',
            'Të mbështetur nga partnerë ndërkombëtarë',
            route('diagnostics.create'),
            route('sustainability.index'),
            route('resources.index'),
        );
        $this->assertInertiaComponentDoesNotContain($response, 'Public/Home', '€');
    }

    public function test_home_page_shows_three_battery_category_cards_linked_to_catalog_filters(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->assertInertiaComponentContains(
            $this->get('/sq/'),
            'Public/Home',
            'Zgjidhni kategorinë tuaj',
            'Auto & Automjete',
            'Industriale & Pirunë',
            'Backup Power & UPS',
            'images/battery-start.jpg',
            'images/battery-pzs.jpg',
            'images/battery-ups.jpg',
        );
    }

    public function test_home_page_shows_regulatory_compliance_links(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/sq/');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Home',
            'Back2Value operates in line with',
            'Albanian waste-management legislation',
            'EU regulatory framework',
            'for batteries, waste batteries, and circular economy principles.',
            'https://akm.gov.al/ova_doc/ligj-nr-10463-date-22-9-2011-per-menaxhimin-e-integruar-te-mbetjeve/',
            'https://eur-lex.europa.eu/eli/reg/2023/1542/oj/',
        );
    }

    public function test_home_page_shows_hero_visual_and_social_meta(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/sq/');

        $this->assertInertiaComponentContains($response, 'Public/Home', 'images/hero-illustration.svg');
        $response->assertSee('property="og:image"', false)
            ->assertSee('images/back2value-logo.png', false);
        $this->assertInertiaComponentDoesNotContain($response, 'Public/Home', '€');
    }

    public function test_home_page_shows_circular_process_flow(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/sq/');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Home',
            'Nga mbledhja te rikthimi në qarkullim',
            'Mbledhja',
            'Testimi',
            'Sortimi',
            'Aktivizimi',
            'Rishitje',
            'Riciklim',
        );
        $this->assertInertiaComponentDoesNotContain($response, 'Public/Home', '€');
    }

    public function test_home_page_does_not_display_catalog_products_or_prices(): void
    {
        $this->seed(SiteContentSeeder::class);

        Battery::create([
            'serial_number' => 'RID-180-001',
            'brand' => 'RID',
            'model' => 'RID 6 OPzS 600',
            'category' => 'RID Series OPzS',
            'capacity_ah' => 180,
            'status' => 'reactivated',
            'purchase_price' => 250.00,
            'sale_price' => 420.00,
            'warranty_months' => 24,
            'is_available' => true,
        ]);

        $homeResponse = $this->get('/sq/');

        $this->assertInertiaComponentDoesNotContain($homeResponse, 'Public/Home', 'RID 6 OPzS 600', '€');

        $productsResponse = $this->get('/sq/products');
        $this->assertInertiaComponentContains(
            $productsResponse,
            'Public/Products',
            'RID Series OPzS',
            'RID 6 OPzS 600',
        );
        $this->assertInertiaComponentDoesNotContain($productsResponse, 'Public/Products', '€');
    }
}
