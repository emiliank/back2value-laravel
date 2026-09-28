@extends('layouts.public')

@section('title', $settings['meta_title'])
@section('meta_description', $settings['meta_description'])

@section('content')
    @php
        $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp'] ?? '355692734476');
        $whatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode('Po interesohem për një ofertë për bateri industriale.');
        $mapUrl = 'https://maps.google.com/?q='.rawurlencode($settings['map_query'] ?? '');
    @endphp

    <section id="top" class="hero">
        <div class="hero__content">
            <div class="hero__badge">{{ $settings['hero_badge'] }}</div>
            <h1>
                {{ $settings['hero_title'] }}<br>
                <span>{{ $settings['hero_highlight'] }}</span><br>
                {{ $settings['hero_subtitle'] }}
            </h1>
            <p>{{ $settings['hero_description'] }}</p>

            <div class="hero__actions">
                <a class="primary-cta primary-cta--large" href="{{ $whatsappUrl }}">
                    {{ $settings['hero_cta'] }}
                    <span aria-hidden="true">→</span>
                </a>
                <a class="secondary-cta" href="{{ route('products.index') }}">{{ $settings['hero_products_cta'] }}</a>
            </div>

            <div class="hero__visual">
                <img
                    src="{{ asset('images/hero-illustration.svg') }}"
                    alt="Ilustrim: energji e pastër me bateri industriale, panele solare, turbina erës dhe ekonomi rrethore"
                    width="960"
                    height="440"
                >
            </div>
        </div>
    </section>

    <div class="promo-strip">
        <p class="promo-strip__compliance">
            Back2Value operates in line with
            <a class="promo-strip__link" href="https://akm.gov.al/ova_doc/ligj-nr-10463-date-22-9-2011-per-menaxhimin-e-integruar-te-mbetjeve/" target="_blank" rel="noreferrer">Albanian waste-management legislation</a>
            and the
            <a class="promo-strip__link" href="https://eur-lex.europa.eu/eli/reg/2023/1542/oj/" target="_blank" rel="noreferrer">EU regulatory framework</a>
            for batteries, waste batteries, and circular economy principles.
        </p>
    </div>

    <section class="trust-band" aria-label="Përfitimet kryesore">
        <div class="trust-band__inner">
            <div class="trust-item">
                <span class="trust-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 3 4.5 6v5.2c0 4.6 3.2 8.3 7.5 9.8 4.3-1.5 7.5-5.2 7.5-9.8V6L12 3Z" stroke="currentColor" stroke-width="1.8"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span>{{ $trust['title'] }}</span>
            </div>
            <div class="trust-item">
                <span class="trust-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 20V8l8-4 8 4v12M8 20v-7h8v7M8 9h.01M12 9h.01M16 9h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span>{{ $trust['subtitle'] }}</span>
            </div>
            <div class="trust-item">
                <span class="trust-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 3 4.5 6v5.2c0 4.6 3.2 8.3 7.5 9.8 4.3-1.5 7.5-5.2 7.5-9.8V6L12 3Z" stroke="currentColor" stroke-width="1.8"/><path d="M9 12h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                <span>{{ $trust['guarantee'] }}</span>
            </div>
            <div class="trust-item">
                <span class="trust-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 3v3m0 12v3M3 12h3m12 0h3M5.6 5.6l2.1 2.1m8.6 8.6 2.1 2.1m0-12.8-2.1 2.1m-8.6 8.6-2.1 2.1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/></svg>
                </span>
                <span>{{ $trust['service'] }}</span>
            </div>
        </div>
    </section>

    <section class="page-section page-section--soft">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $value_props['eyebrow'] }}</span>
                <h2>{{ $value_props['title'] }}</h2>
                <p>{{ $value_props['description'] }}</p>
            </div>

            <div class="value-grid">
                @foreach ($value_props['items'] as $prop)
                    <article class="value-prop">
                        <x-section-icon :name="$prop['icon']" class="value-prop__icon" />
                        <span class="value-prop__value">{{ $prop['value'] }}</span>
                        <h3>{{ $prop['title'] }}</h3>
                        <p>{{ $prop['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-section page-section--green">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $diagnostics_teaser['eyebrow'] }}</span>
                <h2>{{ $diagnostics_teaser['title'] }}</h2>
                <p>{{ $diagnostics_teaser['description'] }}</p>
            </div>

            <div class="steps-grid">
                @foreach ($diagnostics_teaser['steps'] as $index => $step)
                    <article class="step-card">
                        <span class="step-card__index">{{ $index + 1 }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['description'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="page-hero__actions">
                <a class="primary-cta" href="{{ route('diagnostics.create') }}">{{ $diagnostics_teaser['cta'] }}</a>
                <a class="secondary-cta" href="{{ route('services.index') }}">Shiko shërbimet</a>
            </div>
        </div>
    </section>

    <section id="sherbimet" class="services">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $settings['services_eyebrow'] }}</span>
                <h2>{{ $settings['services_title'] }}</h2>
                <p>{{ $settings['services_description'] }}</p>
            </div>

            <div class="service-grid">
                @foreach ($services as $service)
                    <article class="service-card">
                        <x-section-icon :name="$service['icon']" class="service-icon" />
                        <h3>{{ $service['title'] }}</h3>
                        <p>{{ $service['description'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="page-hero__actions">
                <a class="link-cta link-cta--green" href="{{ route('services.index') }}">Detajet e shërbimeve <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section id="rigjenerimi" class="page-section page-section--soft">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $regeneration_page['teaser']['eyebrow'] }}</span>
                <h2>{{ $regeneration_page['teaser']['title'] }}</h2>
                <p>{{ $regeneration_page['teaser']['description'] }}</p>
            </div>

            <div class="solution-grid">
                @foreach ($regeneration_page['tools'] as $tool)
                    <article class="solution-card">
                        <x-section-icon :name="$tool['icon']" class="solution-card__icon" />

                        <h3>{{ $tool['title'] }}</h3>
                        <p class="solution-card__audience">{{ $tool['model'] }}</p>
                        <p>{{ $tool['description'] }}</p>

                        <ul class="feature-list">
                            @foreach ($tool['features'] as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>

            <div class="page-hero__actions">
                <a class="primary-cta" href="{{ route('regeneration.index') }}">{{ $regeneration_page['teaser']['cta'] }}</a>
                <a class="secondary-cta" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
            </div>
        </div>
    </section>

    <section id="procesi" class="page-section process-section">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $circular_process['eyebrow'] }}</span>
                <h2>{{ $circular_process['title'] }}</h2>
                <p>{{ $circular_process['description'] }}</p>
            </div>

            <ol class="process-flow">
                @foreach ($circular_process['steps'] as $step)
                    <li class="process-step">
                        <span class="process-step__node">
                            <x-section-icon :name="$step['icon']" class="process-step__icon" />
                        </span>
                        <div class="process-step__body">
                            <h3>{{ $step['title'] }}</h3>
                            <p>{{ $step['description'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="process-branch" aria-hidden="true">
                <span class="process-branch__stem"></span>
                <span class="process-branch__bar"></span>
                <span class="process-branch__drop process-branch__drop--left"></span>
                <span class="process-branch__drop process-branch__drop--right"></span>
            </div>

            <div class="process-outcomes">
                @foreach ($circular_process['outcomes'] as $outcome)
                    <article class="process-outcome process-outcome--{{ $outcome['key'] }}">
                        <x-section-icon :name="$outcome['icon']" class="process-outcome__icon" />
                        <div>
                            <h3>{{ $outcome['title'] }}</h3>
                            <p>{{ $outcome['description'] }}</p>
                        </div>
                    </article>
                @endforeach

                <aside class="process-note">
                    <span class="eyebrow">{{ $circular_process['note']['eyebrow'] }}</span>
                    <strong>{{ $circular_process['note']['title'] }}</strong>
                    <p>{{ $circular_process['note']['description'] }}</p>
                    <a class="secondary-cta" href="{{ route('sustainability.index') }}">{{ $circular_process['note']['cta'] }}</a>
                </aside>
            </div>
        </div>
    </section>

    <section id="pse-back2value" class="about">
        <div class="section-shell">
            <div class="section-intro section-intro--dark">
                <span class="eyebrow">{{ $about['eyebrow'] }}</span>
                <h2>{{ $about['title'] }}</h2>
                <p>{{ $about['description'] }}</p>
            </div>

            <div class="about-grid">
                @foreach ($about['points'] as $index => $point)
                    <article class="about-card">
                        <x-section-icon :name="$index === 0 ? 'shield' : 'refresh'" class="about-icon" />

                        <div>
                            <h3>{{ $point['title'] }}</h3>
                            <p>{{ $point['description'] }}</p>

                            @if (filled($point['label'] ?? null))
                                <div class="manufacturer-note">
                                    <span class="manufacturer-mark">RID</span>
                                    <span>{{ $point['label'] }}</span>
                                </div>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="stats-grid" aria-label="Të dhëna kryesore">
                @foreach ($stats as $stat)
                    <div class="stat-card">
                        <strong>{{ $stat['value'] }}</strong>
                        <span>{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-section page-section--soft">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $partners['eyebrow'] }}</span>
                <h2>{{ $partners['title'] }}</h2>
                <p>{{ $partners['description'] }}</p>
            </div>

            <div class="partner-grid">
                @foreach ($partners['items'] as $partner)
                    <article class="partner-card">
                        <span class="partner-card__country">{{ $partner['country'] }}</span>
                        <strong>{{ $partner['name'] }}</strong>
                        <p>{{ $partner['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="kontakt" class="contact section-shell">
        <div class="section-intro section-intro--small">
            <span class="eyebrow">{{ $settings['contact_eyebrow'] }}</span>
            <h2>{{ $settings['contact_title'] }}</h2>
            <p>{{ $settings['contact_description'] }}</p>
        </div>

        <div class="contact-grid">
            <a class="contact-card contact-card--green" href="{{ $whatsappUrl }}">
                <span class="contact-ico contact-ico--wa" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4A8 8 0 1 1 20 11.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M9 8.5c.4 2.5 2 4.1 4.5 5l1.1-1.2 2 .9c-.2 1.2-1.2 2-2.5 2-3.6-.3-6.5-3.2-6.8-6.8 0-1.3.8-2.3 2-2.5l.9 2L9 8.5Z" fill="currentColor"/></svg>
                </span>
                <strong>WhatsApp</strong>
                <span>{{ $settings['phone'] }}</span>
            </a>
            <a class="contact-card" href="mailto:{{ $settings['email'] }}">
                <span class="contact-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <strong>Email</strong>
                <span>{{ $settings['email'] }}</span>
            </a>
            <a class="contact-card" href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}">
                <span class="contact-ico" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M7 3H4a1 1 0 0 0-1 1 17 17 0 0 0 17 17 1 1 0 0 0 1-1v-3l-5-2-2 3a14 14 0 0 1-5-5l3-2-2-5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <strong>Telefon</strong>
                <span>{{ $settings['phone'] }}</span>
            </a>
        </div>

        <div class="map-wrap">
            <a class="map-link" href="{{ $mapUrl }}" target="_blank" rel="noreferrer">Hap në Maps <span aria-hidden="true">↗</span></a>
            <iframe
                title="Vendndodhja e Back2Value: {{ $settings['address'] }}"
                src="https://www.google.com/maps?q={{ urlencode($settings['map_query']) }}&amp;output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen>
            </iframe>
        </div>
        <p class="contact-hours">{{ $settings['address'] }} · {{ $settings['hours'] }}</p>
    </section>
@endsection
