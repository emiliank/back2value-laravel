<?php

namespace Tests\Feature;

use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders_back2value_content(): void
    {
        $this->seed(SiteContentSeeder::class);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Bateri gjermane. Garanci reale. Shërbim vendor.');
        $response->assertSee('Back2Value');
    }
}
