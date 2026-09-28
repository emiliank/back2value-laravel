<?php

namespace Database\Factories;

use App\Models\Battery;
use App\Models\DiagnosticReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DiagnosticReport>
 */
class DiagnosticReportFactory extends Factory
{
    protected $model = DiagnosticReport::class;

    public function definition(): array
    {
        $expected = $this->faker->numberBetween(80, 260);
        $actual = $this->faker->numberBetween(35, $expected);
        $retention = $expected > 0 ? round(($actual / $expected) * 100, 2) : 0;
        $eligible = $retention >= 50;

        return [
            'battery_id' => Battery::query()->inRandomOrder()->first()?->id ?? Battery::factory()->create()->id,
            'user_id' => User::query()->inRandomOrder()->first()?->id ?? User::factory()->create()->id,
            'initial_voltage' => $this->faker->randomFloat(2, 10.5, 13.8),
            'internal_resistance' => $this->faker->randomFloat(2, 2.5, 30),
            'expected_capacity' => $expected,
            'actual_capacity' => $actual,
            'is_reactivation_eligible' => $eligible,
            'notes' => $this->faker->randomElement([
                'Battery passed internal inspection and displayed healthy terminal condition.',
                'Capacity loss is within acceptable reactivation thresholds for this application type.',
                'Cell imbalance noted but recoverable through controlled reconditioning cycle.',
                'Voltage drop suggests aging in one module and requires supervised recovery.',
                'Battery was rejected for reactivation due to severe sulfation and low retained capacity.',
            ]),
            'tested_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
