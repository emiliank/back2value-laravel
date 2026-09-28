<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiagnosticRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hasTradeIn = $this->filled('trade_in') || $this->filled('customer_company_name');
        $hasTesting = $this->filled('testing') || $this->filled('battery_id') || $this->filled('initial_voltage');

        return [
            'request_type' => ['nullable', Rule::in(['testing', 'trade_in', 'both'])],
            'battery_id' => ['nullable', 'integer', 'exists:batteries,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'initial_voltage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'internal_resistance' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'expected_capacity' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'actual_capacity' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'is_reactivation_eligible' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:1500'],

            'trade_in' => ['nullable', 'array'],
            'trade_in.customer_company_name' => [
                $hasTradeIn ? 'required' : 'nullable',
                'string',
                'max:255',
            ],
            'trade_in.sector' => ['nullable', Rule::in(['telecom', 'data_center', 'solar', 'construction', 'agriculture'])],
            'trade_in.battery_condition' => ['nullable', 'string', 'max:255'],
            'trade_in.status' => ['nullable', Rule::in(['pending', 'approved', 'rejected', 'completed'])],
            'trade_in.notes' => ['nullable', 'string', 'max:1500'],

            'testing' => ['nullable', 'array'],
            'testing.battery_id' => ['nullable', 'integer', 'exists:batteries,id'],
            'testing.initial_voltage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'testing.internal_resistance' => ['nullable', 'numeric', 'min:0', 'max:1000'],
            'testing.expected_capacity' => ['nullable', 'integer', 'min:1', 'max:1000'],
            'testing.actual_capacity' => ['nullable', 'integer', 'min:0', 'max:1000'],
            'testing.notes' => ['nullable', 'string', 'max:1500'],
        ];
    }
}
