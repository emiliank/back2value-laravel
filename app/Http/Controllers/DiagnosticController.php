<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiagnosticRequest;
use App\Mail\DiagnosticReportMail;
use App\Models\DiagnosticReport;
use App\Models\TradeInRequest;
use App\Services\BatteryDiagnosticService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class DiagnosticController extends Controller
{
    public function store(DiagnosticRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $report = DiagnosticReport::create([
            'battery_id' => $validated['battery_id'] ?? null,
            'user_id' => $validated['user_id'] ?? null,
            'initial_voltage' => $validated['initial_voltage'] ?? null,
            'internal_resistance' => $validated['internal_resistance'] ?? null,
            'expected_capacity' => $validated['expected_capacity'] ?? null,
            'actual_capacity' => $validated['actual_capacity'] ?? null,
            'is_reactivation_eligible' => $validated['is_reactivation_eligible'] ?? false,
            'notes' => $validated['notes'] ?? null,
            'tested_at' => now(),
        ]);

        $evaluation = app(BatteryDiagnosticService::class)->evaluateHealth($report);
        $report->refresh();

        if ($report->is_reactivation_eligible) {
            Mail::to('sales@back2value.com')->send(new DiagnosticReportMail($report));
        }

        if (! empty($validated['trade_in'])) {
            $tradeIn = TradeInRequest::create([
                'customer_company_name' => $validated['trade_in']['customer_company_name'] ?? 'N/A',
                'sector' => $validated['trade_in']['sector'] ?? 'telecom',
                'battery_condition' => $validated['trade_in']['battery_condition'] ?? 'unknown',
                'status' => 'pending',
                'notes' => $validated['trade_in']['notes'] ?? null,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Diagnostic report saved successfully.',
                'data' => [
                    'diagnostic_id' => $report->id,
                    'report' => $report->toArray(),
                    'trade_in' => $tradeIn ?? null,
                ],
            ], 201);
        }

        return redirect()->back()->with('status', 'Diagnostic report saved successfully.');
    }

    public function update(DiagnosticRequest $request, DiagnosticReport $diagnostic): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();

        $diagnostic->fill([
            'battery_id' => $validated['battery_id'] ?? $diagnostic->battery_id,
            'user_id' => $validated['user_id'] ?? $diagnostic->user_id,
            'initial_voltage' => $validated['initial_voltage'] ?? $diagnostic->initial_voltage,
            'internal_resistance' => $validated['internal_resistance'] ?? $diagnostic->internal_resistance,
            'expected_capacity' => $validated['expected_capacity'] ?? $diagnostic->expected_capacity,
            'actual_capacity' => $validated['actual_capacity'] ?? $diagnostic->actual_capacity,
            'is_reactivation_eligible' => $validated['is_reactivation_eligible'] ?? $diagnostic->is_reactivation_eligible,
            'notes' => $validated['notes'] ?? $diagnostic->notes,
        ]);
        $diagnostic->save();

        if (! empty($validated['trade_in'])) {
            $tradeIn = TradeInRequest::firstOrCreate(
                ['customer_company_name' => $validated['trade_in']['customer_company_name'] ?? 'N/A'],
                [
                    'sector' => $validated['trade_in']['sector'] ?? 'telecom',
                    'battery_condition' => $validated['trade_in']['battery_condition'] ?? 'unknown',
                    'status' => 'pending',
                    'notes' => $validated['trade_in']['notes'] ?? null,
                ]
            );

            $tradeIn->update([
                'sector' => $validated['trade_in']['sector'] ?? $tradeIn->sector,
                'battery_condition' => $validated['trade_in']['battery_condition'] ?? $tradeIn->battery_condition,
                'status' => $validated['trade_in']['status'] ?? $tradeIn->status,
                'notes' => $validated['trade_in']['notes'] ?? $tradeIn->notes,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Diagnostic report updated successfully.',
                'data' => $diagnostic->fresh(),
            ]);
        }

        return redirect()->back()->with('status', 'Diagnostic report updated successfully.');
    }
}
