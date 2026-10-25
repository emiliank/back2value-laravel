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

        $this->assertInertiaComponentContains(
            $this->get('/sq/regeneration'),
            'Public/Regeneration',
            'RID Tester & RID Rigenerator',
            'RID BMG-30 Battery Tester',
            'RID BRG Series Battery Regenerator',
            'Blerja dhe regjistrimi',
            'Kontroll me RID Tester',
            'Rigjenerim me RID Rigenerator',
            'Falas brenda 3 vjetësh për çdo bateri të re',
            'jo si produkte për shitje',
        );
    }

    public function test_regeneration_page_renders_rid_lab_flow_section(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->assertInertiaComponentContains(
            $this->get('/sq/regeneration'),
            'Public/Regeneration',
            'Si funksionon laboratori ynë i baterive:',
            'Pranimi',
            'Diagnostikimi i baterisë',
            'Rigjenerimi',
            'Testi i performancës',
            'përqindjen e suksesit të rigjenerimit',
            'images/rid-lab/step-1.png',
            'images/rid-lab/badge-quality.png',
        );
        $this->assertInertiaComponentDoesNotContain($this->get('/sq/regeneration'), 'Public/Regeneration', 'Shkarko grafikun');
    }

    public function test_homepage_promotes_rid_tester_and_rigenerator_as_services(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->assertInertiaComponentContains(
            $this->get('/sq/'),
            'Public/Home',
            'RID Tester & RID Rigenerator',
            'RID BMG-30 Battery Tester',
            'RID BRG Series Battery Regenerator',
            'Mëso më shumë',
            route('regeneration.index'),
        );
    }

    public function test_footer_and_services_page_link_to_regeneration_page(): void
    {
        $this->seed(SiteContentSeeder::class);

        $this->assertInertiaComponentContains(
            $this->get('/sq/products'),
            'Public/Products',
            route('regeneration.index'),
        );

        $this->assertInertiaComponentContains(
            $this->get('/sq/services'),
            'Public/Services',
            route('regeneration.index'),
            'RID Tester & RID Rigenerator',
        );
    }
}
