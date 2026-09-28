<?php

namespace Database\Factories;

use App\Models\DiagnosticBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DiagnosticBooking>
 */
class DiagnosticBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => '+355 69 '.$this->faker->numerify('### ####'),
            'company_name' => $this->faker->boolean(60) ? $this->faker->company() : null,
            'sector' => $this->faker->randomElement(array_keys(config('site.diagnostics_page.sectors'))),
            'battery_type' => $this->faker->randomElement(array_keys(config('site.diagnostics_page.battery_types'))),
            'preferred_date' => $this->faker->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'service_preference' => $this->faker->randomElement(array_keys(config('site.diagnostics_page.service_preferences'))),
            'notes' => $this->faker->sentence(),
            'status' => 'pending',
        ];
    }
}
