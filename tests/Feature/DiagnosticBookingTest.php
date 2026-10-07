<?php

namespace Tests\Feature;

use App\Mail\DiagnosticBookingMail;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DiagnosticBookingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(SiteContentSeeder::class);
    }

    /**
     * @return array<string, string>
     */
    private function validPayload(): array
    {
        return [
            'customer_name' => 'Arta Hoxha',
            'email' => 'arta@example.com',
            'phone' => '+355 69 111 2233',
            'company_name' => 'Albania Telecom shpk',
            'sector' => 'banking',
            'battery_type' => 'ups_backup',
            'preferred_date' => now()->addWeek()->toDateString(),
            'service_preference' => 'onsite',
            'notes' => '24 bateri UPS në dy lokacione.',
        ];
    }

    public function test_diagnostics_page_renders_the_booking_form(): void
    {
        $response = $this->get('/sq/diagnostics');

        $response->assertOk()
            ->assertSee('Rezervo një diagnostikim baterie')
            ->assertSee('Emri dhe mbiemri')
            ->assertSee('Bateri industriale RID-Batterie')
            ->assertSee('Grumbullim nga lokacioni ynë')
            ->assertSee(route('diagnostics.bookings.store'), false)
            ->assertSee('Pika grumbullimi dhe servisi të autorizuara');
    }

    public function test_booking_is_stored_mailed_and_redirects_with_a_status_message(): void
    {
        Mail::fake();

        $response = $this->post(route('diagnostics.bookings.store'), $this->validPayload());

        $response->assertRedirect(route('diagnostics.create'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('diagnostic_bookings', [
            'email' => 'arta@example.com',
            'sector' => 'banking',
            'battery_type' => 'ups_backup',
            'service_preference' => 'onsite',
            'status' => 'pending',
        ]);

        Mail::assertSent(DiagnosticBookingMail::class, function (DiagnosticBookingMail $mail): bool {
            return $mail->hasTo('sales@back2value.com')
                && $mail->booking->customer_name === 'Arta Hoxha';
        });
    }

    public function test_status_message_is_visible_after_the_redirect(): void
    {
        Mail::fake();

        $response = $this->followingRedirects()
            ->post(route('diagnostics.bookings.store'), $this->validPayload());

        $response->assertOk()
            ->assertSee('Kërkesa u dërgua me sukses');
    }

    public function test_booking_requires_the_documented_fields(): void
    {
        Mail::fake();

        $response = $this->post(route('diagnostics.bookings.store'), []);

        $response->assertSessionHasErrors([
            'customer_name',
            'email',
            'phone',
            'sector',
            'battery_type',
            'preferred_date',
            'service_preference',
        ]);

        $this->assertDatabaseCount('diagnostic_bookings', 0);
        Mail::assertNothingSent();
    }

    public function test_booking_rejects_a_past_preferred_date(): void
    {
        Mail::fake();

        $response = $this->post(route('diagnostics.bookings.store'), [
            ...$this->validPayload(),
            'preferred_date' => now()->subDay()->toDateString(),
        ]);

        $response->assertSessionHasErrors('preferred_date');

        $this->assertDatabaseCount('diagnostic_bookings', 0);
    }
}
