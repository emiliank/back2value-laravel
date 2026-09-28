<?php

namespace Tests\Feature;

use App\Models\Battery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BatteryApiIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_battery_index_filters_by_capacity_application_type_status_and_paginates(): void
    {
        Battery::create([
            'serial_number' => 'BATT-A-001',
            'brand' => 'RID',
            'capacity_ah' => 180,
            'status' => 'new',
            'application_type' => 'solar',
            'purchase_price' => 300.00,
            'sale_price' => 520.00,
            'warranty_months' => 24,
            'is_available' => true,
        ]);

        Battery::create([
            'serial_number' => 'BATT-A-002',
            'brand' => 'RID',
            'capacity_ah' => 220,
            'status' => 'reactivated',
            'application_type' => 'auto',
            'purchase_price' => 350.00,
            'sale_price' => 610.00,
            'warranty_months' => 18,
            'is_available' => true,
        ]);

        Battery::create([
            'serial_number' => 'BATT-A-003',
            'brand' => 'RID',
            'capacity_ah' => 220,
            'status' => 'reactivated',
            'application_type' => 'backup_power',
            'purchase_price' => 380.00,
            'sale_price' => 690.00,
            'warranty_months' => 24,
            'is_available' => true,
        ]);

        $response = $this->getJson('/api/batteries?capacity=220&application_type=backup_power&status=reactivated&per_page=15');

        $response->assertOk();
        $response->assertJsonPath('meta.total', 1);
        $response->assertJsonPath('data.0.serial_number', 'BATT-A-003');
    }
}
