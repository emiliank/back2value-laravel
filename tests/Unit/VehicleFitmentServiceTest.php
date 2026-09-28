<?php

namespace Tests\Unit;

use App\Services\VehicleFitmentService;
use Tests\TestCase;

class VehicleFitmentServiceTest extends TestCase
{
    private VehicleFitmentService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new VehicleFitmentService();
    }

    public function test_is_active_detects_any_vehicle_parameter(): void
    {
        $this->assertTrue($this->service->isActive(['v_type' => 'car']));
        $this->assertTrue($this->service->isActive(['vin' => 'WVWZZZ1KZAW000001']));
        $this->assertTrue($this->service->isActive(['v_year' => '2015']));
        $this->assertFalse($this->service->isActive(['q' => 'agm', 'category' => 'x']));
        $this->assertFalse($this->service->isActive([]));
    }

    public function test_decode_vin_rejects_invalid_length(): void
    {
        $result = $this->service->decodeVin('12345');

        $this->assertSame('invalid', $result['state']);
        $this->assertStringContainsString('17 karaktere', $result['message']);
    }

    public function test_decode_vin_rejects_forbidden_characters(): void
    {
        $result = $this->service->decodeVin('WVWZZZ1OZAW000001');

        $this->assertSame('invalid', $result['state']);
        $this->assertStringContainsString('I, O dhe Q', $result['message']);
    }

    public function test_decode_vin_returns_region_and_model_year_candidates(): void
    {
        $result = $this->service->decodeVin('WVWZZZ1KZAW000001');

        $this->assertSame('valid', $result['state']);
        $this->assertSame('WVWZZZ1KZAW000001', $result['vin']);
        $this->assertSame('Evropë', $result['region']);
        $this->assertSame([1980, 2010], $result['year_candidates']);

        $withYear = $this->service->decodeVin('WVWZZZ1KZAW000001', 2010);

        $this->assertSame(2010, $withYear['year']);
        $this->assertTrue($withYear['year_matches_selection']);
    }

    public function test_decode_vin_maps_digit_codes_to_2001_2009(): void
    {
        $result = $this->service->decodeVin('WVWZZZ1KZ5W000001');

        $this->assertSame('valid', $result['state']);
        $this->assertSame([2005], $result['year_candidates']);
    }

    public function test_recommend_prefers_agm_when_start_stop_is_enabled(): void
    {
        $recommendation = $this->service->recommend('car', 'petrol', 'yes', null);

        $this->assertSame('agm', $recommendation['technology']);
        $this->assertSame(40, $recommendation['capacity_min']);
        $this->assertSame(80, $recommendation['capacity_max']);
    }

    public function test_recommend_uses_heavy_duty_for_vehicles_without_start_stop(): void
    {
        $recommendation = $this->service->recommend('truck', 'diesel', 'no', null);

        $this->assertSame('heavy', $recommendation['technology']);
        $this->assertSame(100, $recommendation['capacity_min']);
        $this->assertSame(225, $recommendation['capacity_max']);
    }

    public function test_recommend_narrows_band_when_current_capacity_is_given(): void
    {
        $recommendation = $this->service->recommend('car', 'petrol', 'unknown', 74);

        $this->assertNull($recommendation['technology']);
        $this->assertTrue($recommendation['capacity_given']);
        $this->assertSame(69, $recommendation['capacity_min']);
        $this->assertSame(94, $recommendation['capacity_max']);
    }
}
