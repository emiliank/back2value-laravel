@props(['section'])

@php
    $points = $section['items'] ?? [];
@endphp

<section class="drop-off section-shell">
    <div class="section-intro section-intro--small">
        <span class="eyebrow">{{ $section['eyebrow'] ?? '' }}</span>
        <h2>{{ $section['title'] ?? '' }}</h2>
        <p>{{ $section['description'] ?? '' }}</p>
    </div>

    <div class="drop-off-grid">
        @foreach ($points as $point)
            <article class="drop-off-card">
                <span class="drop-off-type drop-off-type--{{ $point['category'] ?? 'licensed' }}">{{ $point['type'] ?? '' }}</span>
                <h3>{{ $point['title'] ?? '' }}</h3>
                <dl>
                    <div>
                        <dt>Adresa</dt>
                        <dd>{{ $point['address'] ?? '' }}</dd>
                    </div>
                    <div>
                        <dt>Telefon</dt>
                        <dd>
                            <a href="tel:{{ preg_replace('/\D+/', '', $point['phone'] ?? '') }}">{{ $point['phone'] ?? '' }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt>Orari</dt>
                        <dd>{{ $point['hours'] ?? '' }}</dd>
                    </div>
                </dl>
            </article>
        @endforeach
    </div>
</section>
