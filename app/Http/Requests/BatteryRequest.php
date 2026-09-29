<?php

namespace App\Http\Requests;

use App\Models\Battery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BatteryRequest extends FormRequest
{
    /**
     * Spec keys the public catalog renders.
     *
     * @var list<string>
     */
    public const SPEC_KEYS = ['dimensions', 'weight', 'voltage', 'cca', 'design_life', 'cycles'];

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
            'brand' => ['required', 'string', 'max:255'],
            'model' => ['required', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'technology' => ['nullable', 'string', 'max:255'],
            'capacity_ah' => ['required', 'integer', 'min:1', 'max:100000'],
            'voltage' => ['nullable', 'string', 'max:255'],
            'application_type' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['new', 'reactivated', 'end_of_life'])],
            'warranty_months' => ['nullable', 'integer', 'min:0', 'max:600'],
            'purchase_price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_available' => ['nullable', 'boolean'],
            'stock_status' => ['required', Rule::in(Battery::STOCK_STATUSES)],
            'specs' => ['nullable', 'array'],
            'specs.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'brand.required' => 'Zgjidhni ose shkruani markën.',
            'model.required' => 'Shkruani modelin e baterisë.',
            'capacity_ah.required' => 'Shkruani kapacitetin në Ah.',
            'status.required' => 'Zgjidhni gjendjen e baterisë.',
            'stock_status.required' => 'Zgjidhni gjendjen e stokut.',
            'stock_status.in' => 'Gjendja e stokut e zgjedhur është e pavlefshëme.',
        ];
    }

    /**
     * Drop blank spec entries so the JSON column only holds real values.
     */
    protected function prepareForValidation(): void
    {
        $specs = collect((array) $this->input('specs', []))
            ->map(fn ($value): string => is_scalar($value) ? trim((string) $value) : '')
            ->filter()
            ->only(self::SPEC_KEYS)
            ->all();

        $this->merge([
            'specs' => $specs ?: null,
            'is_available' => $this->boolean('is_available'),
        ]);
    }
}
