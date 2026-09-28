<?php

namespace Database\Seeders;

use App\Models\Battery;
use App\Models\DiagnosticReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class DiagnosticSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@back2value.com'],
            [
                'name' => 'Back2Value Admin',
                'password' => bcrypt('password'),
            ]
        );

        $newBatteries = Battery::factory()->count(20)->create([
            'status' => 'new',
            'is_available' => true,
            'application_type' => fake()->randomElement(['auto', 'solar', 'backup_power']),
        ]);

        $reactivatedBatteries = Battery::factory()->count(30)->create([
            'status' => 'reactivated',
            'is_available' => true,
            'sale_price' => fn () => fake()->randomFloat(2, 250, 900),
            'application_type' => fake()->randomElement(['auto', 'solar', 'backup_power']),
        ]);

        $endOfLifeBatteries = Battery::factory()->count(10)->create([
            'status' => 'end_of_life',
            'is_available' => false,
            'application_type' => fake()->randomElement(['auto', 'solar', 'backup_power']),
        ]);

        foreach ($newBatteries as $battery) {
            DiagnosticReport::factory()->create([
                'battery_id' => $battery->id,
                'user_id' => $admin->id,
                'expected_capacity' => $battery->capacity_ah,
                'actual_capacity' => (int) round($battery->capacity_ah * fake()->randomFloat(2, 0.82, 0.99)),
                'is_reactivation_eligible' => false,
                'notes' => 'Fresh inventory check. Battery meets manufacturer expectations and is suitable for stock listing.',
                'initial_voltage' => fake()->randomFloat(2, 12.4, 13.0),
                'internal_resistance' => fake()->randomFloat(2, 2.0, 8.0),
                'tested_at' => now()->subDays(fake()->numberBetween(1, 45)),
            ]);
        }

        foreach ($reactivatedBatteries as $battery) {
            $expected = $battery->capacity_ah;
            $actual = (int) round($expected * fake()->randomFloat(2, 0.52, 0.96));

            DiagnosticReport::factory()->create([
                'battery_id' => $battery->id,
                'user_id' => $admin->id,
                'expected_capacity' => $expected,
                'actual_capacity' => $actual,
                'is_reactivation_eligible' => true,
                'notes' => 'Reconditioning cycle completed successfully. Capacity recovered to commercially viable threshold for resale.',
                'initial_voltage' => fake()->randomFloat(2, 11.9, 12.9),
                'internal_resistance' => fake()->randomFloat(2, 3.5, 16.0),
                'tested_at' => now()->subDays(fake()->numberBetween(1, 60)),
            ]);
        }

        foreach ($endOfLifeBatteries as $battery) {
            $expected = $battery->capacity_ah;
            $actual = (int) round($expected * fake()->randomFloat(2, 0.08, 0.42));

            DiagnosticReport::factory()->create([
                'battery_id' => $battery->id,
                'user_id' => $admin->id,
                'expected_capacity' => $expected,
                'actual_capacity' => $actual,
                'is_reactivation_eligible' => false,
                'notes' => 'Battery reached end-of-life state; replacement advised and licensed recycling transport authorized.',
                'initial_voltage' => fake()->randomFloat(2, 10.0, 12.0),
                'internal_resistance' => fake()->randomFloat(2, 18.0, 45.0),
                'tested_at' => now()->subDays(fake()->numberBetween(2, 90)),
            ]);
        }
    }
}
