<?php

namespace Tests\Unit;

use App\Models\Battery;
use App\Services\BatteryDiagnosticService;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BatteryDiagnosticServiceTest extends TestCase
{
    #[Test]
    public function it_calculates_reactivation_savings(): void
    {
        $service = new BatteryDiagnosticService;

        $result = $service->calculateReactivationSavings(10, 200.0, 120.0);

        $this->assertSame(2000.0, $result['total_replacement_cost']);
        $this->assertSame(1200.0, $result['total_reactivation_cost']);
        $this->assertSame(800.0, $result['total_amount_saved']);
        $this->assertSame(40.0, $result['percentage_saved']);
        $this->assertSame(33.6, $result['estimated_co2_offset_kg']);
    }

    #[Test]
    public function it_marks_non_recoverable_batteries_for_recycling_and_generates_a_trade_in_voucher(): void
    {
        Schema::create('batteries', function ($table): void {
            $table->id();
            $table->string('serial_number')->unique();
            $table->string('brand');
            $table->unsignedInteger('capacity_ah');
            $table->enum('status', ['new', 'reactivated', 'end_of_life']);
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('sale_price', 10, 2);
            $table->unsignedInteger('warranty_months');
            $table->boolean('is_available')->default(true);
            $table->string('stock_status')->default('in_stock');
            $table->timestamps();
        });

        $battery = new Battery([
            'serial_number' => 'BTRY-REC-001',
            'brand' => 'TestBrand',
            'capacity_ah' => 80,
            'status' => 'new',
            'purchase_price' => 150.00,
            'sale_price' => 220.00,
            'warranty_months' => 12,
            'is_available' => true,
        ]);

        $service = new BatteryDiagnosticService;
        $result = $service->processNonRecoverableBattery($battery);

        $this->assertSame('end_of_life', $battery->status);
        $this->assertFalse($battery->is_available);
        $this->assertSame('end_of_life', $result['status']);
        $this->assertSame('licensed_recycling_transport', $result['recycling_status']);
        $this->assertStringStartsWith('B2V-TRADE-IN-', $result['voucher_code']);
        $this->assertSame(15.0, $result['discount_percent']);
    }
}
