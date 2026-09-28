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

        $this->get('/regeneration')
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

    public function test_homepage_promotes_rid_tester_and_rigenerator_as_services(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->get('/')
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

        $this->get('/products')
            ->assertOk()
            ->assertSee(route('regeneration.index'), false);

        $this->get('/services')
            ->assertOk()
            ->assertSee(route('regeneration.index'), false)
            ->assertSee('RID Tester & RID Rigenerator');
    }
}