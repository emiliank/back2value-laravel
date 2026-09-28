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

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Bateri gjermane. Garanci reale. Shërbim vendor.');
        $response->assertSee('Back2Value');
        $response->assertSee(route('products.index'));
        $response->assertSee('Me anë të RID Tester kontrollohet, testohet dhe diagnostikohet bateria, rigjenerohet');
    }

    public function test_home_page_shows_value_props_diagnostics_teaser_and_partners(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Rikthejmë 50–100% të kapacitetit origjinal')
            ->assertSee('Kapacitet i rikthyer')
            ->assertSee('Rezervo Diagnostikim')
            ->assertSee('RID Battery GmbH')
            ->assertSee('Enterprise Europe Network Albania')
            ->assertSee('Nehemiah Gateway Albania')
            ->assertSee(route('diagnostics.create'), false)
            ->assertSee(route('sustainability.index'), false)
            ->assertSee(route('resources.index'), false)
            ->assertDontSee('€');
    }

    public function test_home_page_shows_regulatory_compliance_links(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Back2Value operates in line with')
            ->assertSee('Albanian waste-management legislation', false)
            ->assertSee('EU regulatory framework', false)
            ->assertSee('for batteries, waste batteries, and circular economy principles.')
            ->assertSee('https://akm.gov.al/ova_doc/ligj-nr-10463-date-22-9-2011-per-menaxhimin-e-integruar-te-mbetjeve/', false)
            ->assertSee('https://eur-lex.europa.eu/eli/reg/2023/1542/oj/', false);
    }

    public function test_home_page_shows_hero_visual_and_social_meta(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('images/hero-illustration.svg', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('images/back2value-logo.png', false)
            ->assertDontSee('€');
    }

    public function test_home_page_shows_circular_process_flow(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('id="procesi"', false)
            ->assertSee('Nga mbledhja te rikthimi në qarkullim')
            ->assertSee('Mbledhja')
            ->assertSee('Testimi')
            ->assertSee('Sortimi')
            ->assertSee('Aktivizimi')
            ->assertSee('Rishitje')
            ->assertSee('Riciklim')
            ->assertSee('Zero mbetje në landfill')
            ->assertSee('process-flow', false)
            ->assertSee(route('sustainability.index'), false)
            ->assertDontSee('€');
    }

    public function test_home_page_does_not_display_products_or_prices(): void
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

        $homeResponse = $this->get('/');

        $homeResponse->assertStatus(200);
        // Products and prices must not be shown on the home page
        $homeResponse->assertDontSee('RID 6 OPzS 600');
        $homeResponse->assertDontSee('€');

        // Products should be displayed on the dedicated products catalog page
        $productsResponse = $this->get('/products');
        $productsResponse->assertStatus(200);
        $productsResponse->assertSee('RID Series OPzS');
        $productsResponse->assertSee('RID 6 OPzS 600');
        $productsResponse->assertDontSee('€');
    }
}
