<?php

namespace Tests\Feature;

use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SiteContentSeeder::class);
    }

    public function test_services_page_publishes_regeneration_service_rid_tester(): void
    {
        $response = $this->get('/sq/services');

        $response->assertOk()
            ->assertSee('Karikim & Rigjenerim i Baterive')
            ->assertSee('falas brenda 3 vjetësh');
    }

    public function test_services_page_renders_details_and_drop_off_points_without_calculator(): void
    {
        $response = $this->get('/sq/services');

        $response->assertOk()
            ->assertSee('Zgjidhje të plota për jetëgjatësinë e baterive')
            ->assertSee('Kontrata Mirëmbajtjeje (SLA)')
            ->assertSee('Menaxhim i inventarit të baterive dhe historikut të testeve')
            ->assertDontSee('Sa vlen rigjenerimi për bankën tuaj të baterive?')
            ->assertDontSee('data-savings-calculator', false)
            ->assertSee('Pika grumbullimi dhe servisi të autorizuara')
            ->assertSee('Pogradec — Qendra Kryesore Teknike')
            ->assertSee('Kërkoni një kontratë SLA për flotën tuaj')
            ->assertSee(route('api.maintenance-inquiries.store'), false)
            ->assertSee(route('diagnostics.create'), false);
    }

    public function test_solutions_page_renders_sector_solutions_with_application_links(): void
    {
        $response = $this->get('/sq/solutions');

        $response->assertOk()
            ->assertSee('Për kë punojmë')
            ->assertSee('Ndërmarrje & Industri')
            ->assertSee('Kontrata SLA me reagim emergjent brenda 48 orëve')
            ->assertSee('Energji e Rinovueshme')
            ->assertSee('Sektor Publik')
            ->assertSee(route('products.index', ['application' => 'industrial']), false)
            ->assertSee(route('products.index', ['application' => 'solar']), false);
    }

    public function test_sustainability_page_renders_commitments_metrics_and_compliance(): void
    {
        $response = $this->get('/sq/sustainability');

        $response->assertOk()
            ->assertSee('Menaxhim i përgjegjshëm i baterive')
            ->assertSee('Zgjatja e jetës para zëvendësimit')
            ->assertSee('Riciklim i licencuar')
            ->assertSee('98%')
            ->assertSee('Plumb i riciklueshëm')
            ->assertSee('Përputhshmëria & dokumentacioni')
            ->assertSee('Vërtetime grumbullimi dhe dorëzimi për riciklim të licencuar')
            ->assertDontSee('data-savings-calculator', false);
    }

    public function test_resources_page_renders_about_content_in_both_languages(): void
    {
        $this->get('/sq/resources')
            ->assertOk()
            ->assertSee('Pse Back2Value?')
            ->assertSee('Importues Ekskluziv Gjerman')
            ->assertSee('Rreth nesh')
            ->assertSee('Pyetje të shpeshta')
            ->assertSee('A jeni partner i autorizuar i RID-Batterie?')
            ->assertDontSee('Qendra e burimeve');

        $this->get('/en/resources')
            ->assertOk()
            ->assertSee('Why Back2Value?')
            ->assertSee('Exclusive German Importer')
            ->assertSee('About us')
            ->assertSee('Frequently asked questions')
            ->assertSee('Is Back2Value an authorised RID-Batterie partner?')
            ->assertDontSee('Resource hub');
    }
}
