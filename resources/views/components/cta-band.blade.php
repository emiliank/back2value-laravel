@props([
    'title',
    'description',
    'primaryLabel' => 'Rezervo Diagnostikim',
    'primaryUrl' => null,
    'secondaryLabel' => null,
    'secondaryUrl' => null,
])

<section class="cta-band">
    <div class="section-shell">
        <div class="cta-band__inner">
            <div class="cta-band__copy">
                <h2>{{ $title }}</h2>
                <p>{{ $description }}</p>
            </div>

            <div class="cta-band__actions">
                <a class="primary-cta" href="{{ $primaryUrl ?? route('diagnostics.create') }}">{{ $primaryLabel }}</a>
                @if ($secondaryLabel && $secondaryUrl)
                    <a class="secondary-cta" href="{{ $secondaryUrl }}">{{ $secondaryLabel }}</a>
                @endif
            </div>
        </div>
    </div>
</section>
