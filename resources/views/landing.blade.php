@extends('layouts.public')

@section('title', $settings['meta_title'])
@section('meta_description', $settings['meta_description'])

@section('content')
    @php
        $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp'] ?? '355692734476');
        $whatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($settings['whatsapp_message'] ?? 'Po interesohem për një ofertë për bateri industriale.');
        $mapUrl = 'https://maps.google.com/?q='.rawurlencode($settings['map_query'] ?? '');
        $banner = site('compliance_banner');
        $contactCards = site('contact_cards');
        $trust = site('trust');
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
                    src="{{ site_image($settings['hero_image'] ?? null) ?? asset('images/hero-illustration.svg') }}"
                    alt="{{ $settings['hero_image_alt'] ?? '' }}"
                    width="{{ $settings['hero_image_width'] ?? 960 }}"
                    height="{{ $settings['hero_image_height'] ?? 440 }}"
                >
            </div>
        </div>
    </section>

    @if ($banner['enabled'] ?? true)
        <div class="promo-strip">
            <p class="promo-strip__compliance">
                {{ $banner['prefix'] ?? '' }}
                <a class="promo-strip__link" href="{{ $banner['first_url'] ?? '#' }}" target="_blank" rel="noreferrer">{{ $banner['first_label'] ?? '' }}</a>
                {{ $banner['middle'] ?? '' }}
                <a class="promo-strip__link" href="{{ $banner['second_url'] ?? '#' }}" target="_blank" rel="noreferrer">{{ $banner['second_label'] ?? '' }}</a>
                {{ $banner['suffix'] ?? '' }}
            </p>
        </div>
    @endif

    <section class="trust-band" aria-label="{{ __('site.key_benefits') }}">
        <div class="trust-band__inner">
            @foreach ($trust['items'] as $item)
                <div class="trust-item">
                    <span class="trust-icon" aria-hidden="true">
                        <x-section-icon :name="$item['icon']" />
                    </span>
                    <span>{{ $item['text'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    <section class="page-section page-section--soft">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $valueProps['eyebrow'] }}</span>
                <h2>{{ $valueProps['title'] }}</h2>
                <p>{{ $valueProps['description'] }}</p>
            </div>

            <div class="value-grid">
                @foreach ($valueProps['items'] as $prop)
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
                <span class="eyebrow">{{ $diagnosticsTeaser['eyebrow'] }}</span>
                <h2>{{ $diagnosticsTeaser['title'] }}</h2>
                <p>{{ $diagnosticsTeaser['description'] }}</p>
            </div>

            <div class="steps-grid">
                @foreach ($diagnosticsTeaser['steps'] as $index => $step)
                    <article class="step-card">
                        <span class="step-card__index">{{ $index + 1 }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['description'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="page-hero__actions">
                <a class="primary-cta" href="{{ site_link($diagnosticsTeaser['cta_target'] ?? 'diagnostics') }}">{{ $diagnosticsTeaser['cta'] }}</a>
                @if (filled($diagnosticsTeaser['secondary_cta'] ?? null))
                    <a class="secondary-cta" href="{{ site_link($diagnosticsTeaser['secondary_cta_target'] ?? 'services') }}">{{ $diagnosticsTeaser['secondary_cta'] }}</a>
                @endif
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
                <a class="link-cta link-cta--green" href="{{ site_link(site('services_page', 'detail_link_target', 'regeneration')) }}">{{ site('services_page', 'detail_link', 'Detajet e shërbimeve') }} <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section id="rigjenerimi" class="page-section page-section--soft">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $regenerationPage['teaser_eyebrow'] }}</span>
                <h2>{{ $regenerationPage['teaser_title'] }}</h2>
                <p>{{ $regenerationPage['teaser_description'] }}</p>
            </div>

            <div class="solution-grid">
                @foreach ($regenerationPage['tools'] as $tool)
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
                <a class="primary-cta" href="{{ site_link($regenerationPage['teaser_cta_target'] ?? 'regeneration') }}">{{ $regenerationPage['teaser_cta'] }}</a>
                <a class="secondary-cta" href="{{ site_link($regenerationPage['teaser_secondary_cta_target'] ?? 'diagnostics') }}">{{ $regenerationPage['teaser_secondary_cta'] }}</a>
            </div>
        </div>
    </section>

    <section id="procesi" class="page-section process-section">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $circularProcess['eyebrow'] }}</span>
                <h2>{{ $circularProcess['title'] }}</h2>
                <p>{{ $circularProcess['description'] }}</p>
            </div>

            <ol class="process-flow">
                @foreach ($circularProcess['steps'] as $step)
                    <li @class(['process-step', 'process-step--not-offered' => ($step['key'] ?? null) === 'activation'])>
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
                @foreach ($circularProcess['outcomes'] as $outcome)
                    @php
                        $defaultOutcomeKey = data_get(
                            config('site.'.app()->getLocale().'.circular_process.outcomes', []),
                            $loop->index.'.key',
                        );
                        $outcomeKey = filled($outcome['key'] ?? null)
                            ? $outcome['key']
                            : ($defaultOutcomeKey ?? ($outcome['title'] ?? 'outcome-'.$loop->iteration));
                    @endphp
                    <article @class([
                        'process-outcome',
                        'process-outcome--not-offered' => ($outcomeKey === 'recycling'),
                        'process-outcome--'.\Illuminate\Support\Str::slug($outcomeKey),
                    ])>
                        <x-section-icon :name="$outcome['icon']" class="process-outcome__icon" />
                        <div>
                            <h3>{{ $outcome['title'] }}</h3>
                            <p>{{ $outcome['description'] }}</p>
                        </div>
                    </article>
                @endforeach

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

            <div class="stats-grid" aria-label="{{ __('site.key_figures') }}">
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

            @php
                $partnerLogos = array_values(array_filter(
                    $partners['items'],
                    static fn (array $partner): bool => filled(site_image($partner['logo'] ?? null)),
                ));
            @endphp

            @if (filled($partnerLogos))
                <div class="partner-marquee" data-partner-marquee>
                    <div
                        class="partner-marquee__viewport"
                        data-partner-marquee-viewport
                        role="region"
                        aria-label="{{ $partners['title'] }}"
                    >
                        <div class="partner-marquee__track" data-partner-marquee-track>
                            <div class="partner-marquee__group" data-partner-marquee-group>
                                @foreach ($partnerLogos as $partner)
                                    @php
                                        $partnerUrl = $partner['url'] ?? null;
                                        $logoBlendClass = match ($partner['logo_background'] ?? 'black') {
                                            'white' => 'partner-card__logo--white',
                                            'black' => 'partner-card__logo--black',
                                            default => 'partner-card__logo--transparent',
                                        };
                                    @endphp
                                    <article class="partner-card partner-card--logo">
                                        @if (filled($partnerUrl))
                                            <a class="partner-card__logo-link" href="{{ $partnerUrl }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $partner['name'] }}">
                                        @endif
                                        <img
                                            class="partner-card__logo {{ $logoBlendClass }}"
                                            src="{{ site_image($partner['logo']) }}"
                                            alt="{{ ($partner['logo_alt'] ?? null) ?: $partner['name'] }}"
                                            data-logo-background="{{ $partner['logo_background'] ?? 'black' }}"
                                            loading="lazy"
                                        >
                                        @if (filled($partnerUrl))
                                            </a>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                    <x-section-icon name="whatsapp" />
                </span>
                <strong>{{ $contactCards['whatsapp_label'] }}</strong>
                <span>{{ $settings['phone'] }}</span>
            </a>
            <a class="contact-card" href="mailto:{{ $settings['email'] }}">
                <span class="contact-ico" aria-hidden="true">
                    <x-section-icon name="mail" />
                </span>
                <strong>{{ $contactCards['email_label'] }}</strong>
                <span>{{ $settings['email'] }}</span>
            </a>
            <a class="contact-card" href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}">
                <span class="contact-ico" aria-hidden="true">
                    <x-section-icon name="phone" />
                </span>
                <strong>{{ $contactCards['phone_label'] }}</strong>
                <span>{{ $settings['phone'] }}</span>
            </a>
        </div>

        <div class="map-wrap">
            <a class="map-link" href="{{ $mapUrl }}" target="_blank" rel="noreferrer">{{ $contactCards['map_label'] }} <span aria-hidden="true">↗</span></a>
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
