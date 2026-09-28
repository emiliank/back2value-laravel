<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MaintenanceInquiryMail;
use App\Models\MaintenanceAgreement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MaintenanceInquiryController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'sector' => ['required', 'in:telecom,data_center,banks'],
            'fleet_size' => ['required', 'integer', 'min:1'],
            'requirements' => ['required', 'string', 'max:2000'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $inquiry = MaintenanceAgreement::create([
            'customer_company_name' => $validated['company_name'],
            'sector' => $validated['sector'],
            'battery_condition' => 'maintenance_contract',
            'status' => 'pending',
            'notes' => $validated['requirements'].PHP_EOL.($validated['message'] ?? ''),
        ]);

        Mail::to('sales@back2value.com')->send(new MaintenanceInquiryMail(
            companyName: $validated['company_name'],
            contactName: $validated['contact_name'],
            email: $validated['email'],
            phone: $validated['phone'],
            sector: $validated['sector'],
            fleetSize: (int) $validated['fleet_size'],
            requirements: $validated['requirements'],
            message: $validated['message'] ?? null,
        ));

        Log::info('B2B maintenance inquiry received.', [
            'inquiry_id' => $inquiry->id,
            'company_name' => $validated['company_name'],
            'sector' => $validated['sector'],
            'email' => $validated['email'],
            'status' => $inquiry->status,
        ]);

        return response()->json([
            'message' => 'B2B maintenance inquiry received and sales has been notified.',
            'data' => [
                'id' => $inquiry->id,
                'company_name' => $inquiry->customer_company_name,
                'sector' => $inquiry->sector,
                'status' => $inquiry->status,
            ],
        ], 201);
    }
}
