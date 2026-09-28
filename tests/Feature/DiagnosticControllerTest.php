<?php

namespace Tests\Feature;

use App\Mail\DiagnosticReportMail;
use App\Models\Battery;
use App\Models\DiagnosticReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DiagnosticControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_diagnostic_report_and_sends_notification_when_battery_is_reactivation_eligible(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $battery = Battery::factory()->create([
            'brand' => 'RID',
            'capacity_ah' => 220,
            'application_type' => 'solar',
            'status' => 'reactivated',
            'is_available' => true,
        ]);

        $response = $this->postJson('/diagnostics', [
            'battery_id' => $battery->id,
            'user_id' => $user->id,
            'initial_voltage' => 12.8,
            'internal_resistance' => 5.4,
            'expected_capacity' => 220,
            'actual_capacity' => 150,
            'notes' => 'Battery remains in good reactivation range after testing.',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.report.battery_id', $battery->id)
            ->assertJsonPath('data.report.actual_capacity', 150)
            ->assertJsonPath('data.report.is_reactivation_eligible', true);

        $this->assertDatabaseHas('diagnostic_reports', [
            'battery_id' => $battery->id,
            'user_id' => $user->id,
            'expected_capacity' => 220,
            'actual_capacity' => 150,
            'is_reactivation_eligible' => true,
        ]);

        $report = DiagnosticReport::query()->first();
        $this->assertNotNull($report);
        $this->assertTrue((bool) $report->is_reactivation_eligible);
        $this->assertEqualsWithDelta(68.18, ((float) $report->actual_capacity / (float) $report->expected_capacity) * 100, 0.02);

        Mail::assertSent(DiagnosticReportMail::class, function ($mail) use ($report): bool {
            return $mail->hasTo('sales@back2value.com')
                && $mail->report->id === $report->id
                && $mail->report->is_reactivation_eligible === true;
        });
    }
}
