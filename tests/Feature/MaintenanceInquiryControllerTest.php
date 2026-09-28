<?php

namespace Tests\Feature;

use App\Mail\MaintenanceInquiryMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MaintenanceInquiryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_b2b_maintenance_inquiry_sends_sales_email_and_logs_the_request(): void
    {
        Mail::fake();
        Log::spy();

        $response = $this->postJson('/api/maintenance-inquiries', [
            'company_name' => 'Telecom Albania',
            'contact_name' => 'Sofia Marku',
            'email' => 'sofia@telecomalbania.al',
            'phone' => '+355 69 123 4567',
            'sector' => 'telecom',
            'fleet_size' => 250,
            'requirements' => 'Need a maintenance contract for 250 telecom batteries across 15 sites.',
            'message' => 'Please call us to schedule an assessment.',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('message', 'B2B maintenance inquiry received and sales has been notified.');

        Mail::assertSent(MaintenanceInquiryMail::class, function ($mail): bool {
            return $mail->hasTo('sales@back2value.com');
        });

        Log::shouldHaveReceived('info')->withArgs(function ($message, $context = []) {
            return $message === 'B2B maintenance inquiry received.'
                && ($context['company_name'] ?? null) === 'Telecom Albania';
        });
    }
}
