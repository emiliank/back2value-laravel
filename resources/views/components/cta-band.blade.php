@props([
    'title',
    'description' => null,
    'primaryLabel' => null,
    'primaryUrl' => null,
    'secondaryLabel' => null,
    'secondaryUrl' => null,
])

<section class="cta-band">
    <div class="section-shell">
        <div class="cta-band__inner">
            <div class="cta-band__copy">
                <h2>{{ $title }}</h2>
                @if (filled($description))
                    <p>{{ $description }}</p>
                @endif
            </div>

            <div class="cta-band__actions">
                @if (filled($primaryLabel))
                    <a class="primary-cta" href="{{ $primaryUrl ?? route('diagnostics.create') }}">{{ $primaryLabel }}</a>
                @endif
                @if (filled($secondaryLabel) && filled($secondaryUrl))
                    <a class="secondary-cta" href="{{ $secondaryUrl }}">{{ $secondaryLabel }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
