<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeToggleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'admin.email' => 'admin@example.test',
            'admin.password' => 'very-secure-test-password',
        ]);

        $this->seed(SiteContentSeeder::class);
    }

    public function test_public_pages_apply_the_stored_theme_before_the_first_paint(): void
    {
        $response = $this->get('/sq');

        $response->assertOk();
        $response->assertSee('back2value-theme', false);
        $response->assertSee('prefers-color-scheme: dark', false);
        $response->assertSee("classList.add('dark')", false);
        $response->assertSee('<meta name="theme-color" content="#101b32">', false);
    }

    public function test_admin_pages_keep_the_light_theme(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertDontSee('back2value-theme', false);
        $response->assertSee("classList.remove('dark')", false);
    }
}
