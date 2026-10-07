<?php

namespace Tests\Feature;

use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegenerationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_regeneration_page_renders_tools_process_and_offer(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/sq/regeneration')
            ->assertOk()
            ->assertSee('RID Tester & RID Rigenerator')
            ->assertSee('RID BMG-30 Battery Tester')
            ->assertSee('RID BRG Series Battery Regenerator')
            ->assertSee('Blerja dhe regjistrimi')
            ->assertSee('Kontroll me RID Tester')
            ->assertSee('Rigjenerim me RID Rigenerator')
            ->assertSee('Falas brenda 3 vjetësh për çdo bateri të re')
            ->assertSee('jo si produkte për shitje')
            ->assertSee(route('diagnostics.create'), false)
            ->assertSee(route('services.index'), false);
    }

    public function test_regeneration_page_renders_rid_lab_flow_section(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/sq/regeneration')
            ->assertOk()
            ->assertSee('Si funksionon laboratori ynë i baterive:')
            ->assertSee('1. Pranimi')
            ->assertSee('2. Diagnostikimi i baterisë')
            ->assertSee('3. Rigjenerimi')
            ->assertSee('4. Testi i performancës')
            ->assertSee('përqindjen e suksesit të rigjenerimit')
            ->assertSee('images/rid-lab/step-1.png', false)
            ->assertSee('images/rid-lab/badge-quality.png', false)
            ->assertSee('lab-flow__brand lab-flow__brand--site', false)
            ->assertDontSee('Shkarko grafikun')
            ->assertDontSee('lab-flow__download');
    }

    public function test_homepage_promotes_rid_tester_and_rigenerator_as_services(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/sq/')
            ->assertOk()
            ->assertSee('RID Tester & RID Rigenerator')
            ->assertSee('RID BMG-30 Battery Tester')
            ->assertSee('RID BRG Series Battery Regenerator')
            ->assertSee('Mëso më shumë')
            ->assertSee(route('regeneration.index'), false);
    }

    public function test_footer_and_services_page_link_to_regeneration_page(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/sq/products')
            ->assertOk()
            ->assertSee(route('regeneration.index'), false);

        $this->get('/sq/services')
            ->assertOk()
            ->assertSee(route('regeneration.index'), false)
            ->assertSee('RID Tester & RID Rigenerator');
    }
}
