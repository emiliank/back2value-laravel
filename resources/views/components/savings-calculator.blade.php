@php
    $calculator = site('calculator');
    $leadShare = max(1, min(100, (int) ($calculator['lead_share_percent'] ?? 55))) / 100;
    $co2PerKg = max(0.1, (float) ($calculator['co2_per_kg'] ?? 2));
@endphp

<section id="kalkulator" class="calculator" data-savings-calculator data-lead-share="{{ $leadShare }}" data-co2-per-kg="{{ $co2PerKg }}">
    <div class="section-shell">
        <div class="section-intro section-intro--small">
            <span class="eyebrow">{{ $calculator['eyebrow'] ?? '' }}</span>
            <h2>{{ $calculator['title'] ?? '' }}</h2>
            <p>{{ $calculator['description'] ?? '' }}</p>
        </div>

        <div class="calculator-panel">
            <div class="calculator-fields">
                <label class="field">
                    <span class="field__label">{{ $calculator['count_label'] ?? 'Numri i baterive' }}</span>
                    <input class="field__input" type="number" min="1" max="5000" step="1" value="{{ (int) ($calculator['count_default'] ?? 24) }}" data-battery-count inputmode="numeric">
                </label>

                <label class="field">
                    <span class="field__label">{{ $calculator['weight_label'] ?? 'Pesha mesatare e një baterie (kg)' }}</span>
                    <input class="field__input" type="number" min="1" max="3000" step="1" value="{{ (int) ($calculator['weight_default'] ?? 32) }}" data-battery-weight inputmode="numeric">
                </label>
            </div>

            <div class="calculator-results" aria-live="polite">
                <div class="calculator-result">
                    <strong>{{ $calculator['capacity_value'] ?? '50–100%' }}</strong>
                    <span>{{ $calculator['capacity_label'] ?? '' }}</span>
                </div>
                <div class="calculator-result">
                    <strong>{{ $calculator['cost_value'] ?? '' }}</strong>
                    <span>{{ $calculator['cost_label'] ?? '' }}</span>
                </div>
                <div class="calculator-result">
                    <strong><span data-result-lead>0</span> {{ $calculator['lead_unit'] ?? 'kg' }}</strong>
                    <span>{{ $calculator['lead_label'] ?? '' }}</span>
                </div>
                <div class="calculator-result">
                    <strong><span data-result-co2>0</span> {{ $calculator['co2_unit'] ?? 'kg' }}</strong>
                    <span>{{ $calculator['co2_label'] ?? '' }}</span>
                </div>
            </div>

            <p class="calculator-note">{{ $calculator['note'] ?? '' }}</p>

            <div class="calculator-actions">
                <a class="primary-cta" href="{{ site_link($calculator['primary_target'] ?? 'diagnostics') }}">{{ $calculator['primary_label'] ?? '' }}</a>
                <a class="secondary-cta" href="{{ site_link($calculator['secondary_target'] ?? 'sustainability') }}">{{ $calculator['secondary_label'] ?? '' }}</a>
            </div>
        </div>
    </div>
</section>
