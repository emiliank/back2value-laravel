<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DiagnosticBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'sector' => ['required', Rule::in(array_keys(config('site.diagnostics_page.sectors')))],
            'battery_type' => ['required', Rule::in(array_keys(config('site.diagnostics_page.battery_types')))],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'service_preference' => ['required', Rule::in(array_keys(config('site.diagnostics_page.service_preferences')))],
            'notes' => ['nullable', 'string', 'max:1500'],
        ];
    }
}
