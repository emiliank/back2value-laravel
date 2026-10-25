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

        $this->assertInertiaComponentContains(
            $response,
            'Public/Services',
            'Karikim & Rigjenerim i Baterive',
            'falas brenda 3 vjetësh',
        );
    }

    public function test_services_page_renders_details_and_drop_off_points_without_calculator(): void
    {
        $response = $this->get('/sq/services');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Services',
            'Zgjidhje të plota për jetëgjatësinë e baterive',
            'Kontrata Mirëmbajtjeje (SLA)',
            'Menaxhim i inventarit të baterive dhe historikut të testeve',
            'Pika grumbullimi dhe servisi të autorizuara',
            'Pogradec — Qendra Kryesore Teknike',
            'Kërkoni një kontratë SLA për flotën tuaj',
            route('diagnostics.create'),
        );
        $this->assertInertiaComponentDoesNotContain(
            $response,
            'Public/Services',
            'Sa vlen rigjenerimi për bankën tuaj të baterive?',
            'Kalkulator kursimi',
        );
    }

    public function test_services_page_hides_the_savings_calculator_button_in_english(): void
    {
        $response = $this->get('/en/services');

        $this->assertInertiaComponentDoesNotContain($response, 'Public/Services', 'Savings calculator');
    }

    public function test_solutions_page_renders_sector_solutions_with_application_links(): void
    {
        $response = $this->get('/sq/solutions');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Solutions',
            'Për kë punojmë',
            'Ndërmarrje & Industri',
            'Kontrata SLA me reagim emergjent brenda 48 orëve',
            'Energji e Rinovueshme',
            'Sektor Publik',
        );
    }

    public function test_sustainability_page_renders_commitments_metrics_and_compliance(): void
    {
        $response = $this->get('/sq/sustainability');

        $this->assertInertiaComponentContains(
            $response,
            'Public/Sustainability',
            'Menaxhim i përgjegjshëm i baterive',
            'Zgjatja e jetës para zëvendësimit',
            'Riciklim i licencuar',
            '98%',
            'Plumb i riciklueshëm',
            'Përputhshmëria & dokumentacioni',
            'Vërtetime grumbullimi dhe dorëzimi për riciklim të licencuar',
        );
    }

    public function test_resources_page_renders_about_content_in_both_languages(): void
    {
        $albanianResponse = $this->get('/sq/resources');

        $this->assertInertiaComponentContains(
            $albanianResponse,
            'Public/Resources',
            'Pse Back2Value?',
            'Importues Ekskluziv Gjerman',
            'Rreth nesh',
            'A jeni partner i autorizuar i RID-Batterie?',
        );
        $this->assertNotEmpty($albanianResponse->inertiaProps('about.faqs'));
        $this->assertInertiaComponentDoesNotContain($albanianResponse, 'Public/Resources', 'Qendra e burimeve');

        $englishResponse = $this->get('/en/resources');
        $this->assertInertiaComponentContains(
            $englishResponse,
            'Public/Resources',
            'Why Back2Value?',
            'Exclusive German Importer',
            'About us',
            'Is Back2Value an authorised RID-Batterie partner?',
        );
        $this->assertNotEmpty($englishResponse->inertiaProps('about.faqs'));
        $this->assertInertiaComponentDoesNotContain($englishResponse, 'Public/Resources', 'Resource hub');
    }
}
