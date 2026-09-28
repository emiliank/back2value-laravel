<?php

namespace Database\Factories;

use App\Models\Battery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Battery>
 */
class BatteryFactory extends Factory
{
    protected $model = Battery::class;

    public function definition(): array
    {
        $status = $this->faker->randomElement(['new', 'reactivated', 'end_of_life']);
        $capacity = $this->faker->randomElement([40, 60, 80, 100, 120, 150, 180, 220, 260, 320]);
        $brand = $this->faker->randomElement(['RID', 'Trojan', 'Victron', 'HBL', 'EnerSys', 'PowerSafe']);
        $applicationTypes = ['auto', 'solar', 'backup_power'];

        return [
            'serial_number' => 'BAT-'.strtoupper($this->faker->bothify('??###')).'-'.$this->faker->unique()->numerify('####'),
            'brand' => $brand,
            'capacity_ah' => $capacity,
            'application_type' => $this->faker->randomElement($applicationTypes),
            'status' => $status,
            'purchase_price' => $this->faker->randomFloat(2, 180, 900),
            'sale_price' => $this->faker->randomFloat(2, 320, 1400),
            'warranty_months' => $this->faker->numberBetween(12, 36),
            'is_available' => $this->faker->boolean(70),
        ];
    }
}
