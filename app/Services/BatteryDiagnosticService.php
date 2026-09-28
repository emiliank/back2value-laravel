<?php

namespace App\Services;

use App\Models\Battery;
use App\Models\DiagnosticReport;

class BatteryDiagnosticService
{
    /**
     * @return array{
     *     retention_percentage: float,
     *     is_reactivation_eligible: bool,
     *     estimated_restored_capacity: int,
     *     status: string,
     *     message: string
     * }
     */
    public function evaluateHealth(DiagnosticReport $report): array
    {
        $expected = max((int) $report->expected_capacity, 1);
        $actual = max((int) $report->actual_capacity, 0);
        $retention = $expected > 0 ? ($actual / $expected) * 100 : 0.0;

        $eligible = $retention >= 50;

        $estimatedRestoredCapacity = $eligible
            ? (int) round((50 + ($retention * 0.5)))
            : 0;

        $estimatedRestoredCapacity = max(50, min(100, $estimatedRestoredCapacity));

        if ($eligible) {
            $report->is_reactivation_eligible = true;
            $report->save();

            $status = 'eligible';
            $message = 'Battery meets the minimum retention threshold for reactivation.';
        } else {
            $report->is_reactivation_eligible = false;
            $report->save();

            $status = 'not_eligible';
            $message = 'Battery retention is too low for reactivation.';
        }

        return [
            'retention_percentage' => round($retention, 2),
            'is_reactivation_eligible' => $eligible,
            'estimated_restored_capacity' => $estimatedRestoredCapacity,
            'status' => $status,
            'message' => $message,
        ];
    }

    /**
     * @return array{
     *     battery_id: int,
     *     status: string,
     *     recycling_status: string,
     *     voucher_code: string,
     *     discount_percent: float,
     *     message: string
     * }
     */
    public function processNonRecoverableBattery(Battery $battery): array
    {
        $battery->status = 'end_of_life';
        $battery->is_available = false;
        $battery->save();

        $voucherCode = sprintf(
            'B2V-TRADE-IN-%s-%s',
            strtoupper(substr(hash('sha256', (string) $battery->id.':'.microtime(true)), 0, 6)),
            $battery->id
        );

        return [
            'battery_id' => (int) $battery->id,
            'status' => 'end_of_life',
            'recycling_status' => 'licensed_recycling_transport',
            'voucher_code' => $voucherCode,
            'discount_percent' => 15.0,
            'message' => 'Battery marked for licensed recycling transport and a replacement trade-in voucher was generated.',
        ];
    }

    /**
     * @return array{
     *     total_replacement_cost: float,
     *     total_reactivation_cost: float,
     *     total_amount_saved: float,
     *     percentage_saved: float,
     *     estimated_co2_offset_kg: float
     * }
     */
    public function calculateReactivationSavings(int $quantity, float $newPrice, float $reactivationPrice): array
    {
        $totalReplacementCost = $quantity * $newPrice;
        $totalReactivationCost = $quantity * $reactivationPrice;
        $totalAmountSaved = max(0, $totalReplacementCost - $totalReactivationCost);

        $percentageSaved = $totalReplacementCost > 0
            ? ($totalAmountSaved / $totalReplacementCost) * 100
            : 0.0;

        $estimatedCo2OffsetKg = $totalAmountSaved > 0
            ? ($totalAmountSaved / 100) * 4.2
            : 0.0;

        return [
            'total_replacement_cost' => round($totalReplacementCost, 2),
            'total_reactivation_cost' => round($totalReactivationCost, 2),
            'total_amount_saved' => round($totalAmountSaved, 2),
            'percentage_saved' => round($percentageSaved, 2),
            'estimated_co2_offset_kg' => round($estimatedCo2OffsetKg, 2),
        ];
    }
}
