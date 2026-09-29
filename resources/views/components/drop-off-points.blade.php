@props(['section' => null])

@php
    $dropOff = $section ?? site('drop_off_points');
    $points = $dropOff['items'] ?? [];
@endphp

<section class="drop-off section-shell">
    <div class="section-intro section-intro--small">
        <span class="eyebrow">{{ $dropOff['eyebrow'] ?? '' }}</span>
        <h2>{{ $dropOff['title'] ?? '' }}</h2>
        <p>{{ $dropOff['description'] ?? '' }}</p>
    </div>

    <div class="drop-off-grid">
        @foreach ($points as $point)
            <article class="drop-off-card">
                <span class="drop-off-type drop-off-type--{{ $point['category'] ?? 'licensed' }}">{{ $point['type'] ?? '' }}</span>
                <h3>{{ $point['title'] ?? '' }}</h3>
                <dl>
                    <div>
                        <dt>{{ $dropOff['address_label'] ?? 'Adresa' }}</dt>
                        <dd>{{ $point['address'] ?? '' }}</dd>
                    </div>
                    <div>
                        <dt>{{ $dropOff['phone_label'] ?? 'Telefon' }}</dt>
                        <dd>
                            <a href="tel:{{ preg_replace('/\D+/', '', $point['phone'] ?? '') }}">{{ $point['phone'] ?? '' }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt>{{ $dropOff['hours_label'] ?? 'Orari' }}</dt>
                        <dd>{{ $point['hours'] ?? '' }}</dd>
                    </div>
                </dl>
            </article>
        @endforeach
    </div>
</section>
