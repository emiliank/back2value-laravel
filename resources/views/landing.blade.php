<!DOCTYPE html>
<html lang="sq">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ $settings['meta_description'] }}">
        <meta name="theme-color" content="#101b32">
        <title>{{ $settings['meta_title'] }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="--green: {{ preg_match('/\A#[0-9a-fA-F]{6}\z/', $settings['accent_color']) ? $settings['accent_color'] : '#17b78b' }}">
        @php
            $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp']);
            $whatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode('Po interesohem për një ofertë për bateri industriale.');
            $mapUrl = 'https://maps.google.com/?q='.rawurlencode($settings['map_query']);
        @endphp

        <header class="site-header">
            <div class="site-header__inner">
                <a href="#top" class="brand-box" aria-label="Back2Value, faqja kryesore">
                    <span class="brand-box__inner">
                        <span class="brand-word">back</span>
                        <span class="brand-word">to</span>
                        <span class="brand-word">value</span>
                    </span>
                </a>

                <nav class="main-nav" aria-label="Navigimi kryesor">
                    <a href="#baterite">Bateritë</a>
                    <a href="#sherbimet">Shërbimet</a>
                    <a href="#pse-back2value">Pse Back2Value</a>
                    <a href="#kontakt">Kontakt</a>
                </nav>

                <a class="primary-cta header-cta" href="{{ $whatsappUrl }}">Na Kontaktoni</a>

                <button type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="Hap menunë" class="mobile-toggle">
                    <svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </button>

                <nav id="mobile-menu" data-mobile-menu class="mobile-menu" aria-label="Navigimi për celular" hidden>
                    <a href="#baterite">Bateritë</a>
                    <a href="#sherbimet">Shërbimet</a>
                    <a href="#pse-back2value">Pse Back2Value</a>
                    <a href="#kontakt">Kontakt</a>
                    <a class="primary-cta" href="{{ $whatsappUrl }}">Na Kontaktoni</a>
                </nav>
            </div>
        </header>

        <main>
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
                        <a class="secondary-cta" href="#baterite">{{ $settings['hero_products_cta'] }}</a>
                    </div>
                </div>
            </section>

            <a class="promo-strip" href="#sherbimet">
                <span><strong>{{ $settings['promo_title'] }}</strong> — {{ $settings['promo_description'] }}</span>
                <span class="promo-strip__link">{{ $settings['promo_link'] }} <span aria-hidden="true">→</span></span>
            </a>

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

            <section id="baterite" class="products section-shell">
                <div class="section-intro">
                    <span class="eyebrow">{{ $settings['products_eyebrow'] }}</span>
                    <h2>{{ $settings['products_title'] }}</h2>
                    <p>{{ $settings['products_description'] }}</p>
                </div>

                <div class="product-grid">
                    @foreach ($products as $index => $product)
                        @php
                            $productImage = match (true) {
                                blank($product['image'] ?? null) => null,
                                str_starts_with($product['image'], 'images/') => asset($product['image']),
                                default => \Illuminate\Support\Facades\Storage::disk('public')->url($product['image']),
                            };
                        @endphp
                        <article class="product-card">
                            <div @class(['product-art', 'product-art--empty' => blank($productImage)])>
                                @if ($productImage)
                                    <img src="{{ $productImage }}" alt="{{ $product['image_alt'] }}" width="360" height="260" loading="lazy">
                                @else
                                    <span>RID-Batterie</span>
                                @endif
                            </div>
                            <div class="product-icon {{ ['product-icon--amber', 'product-icon--blue', 'product-icon--green'][$index % 3] }}" aria-hidden="true">
                                @if ($index % 3 === 0)
                                    <svg viewBox="0 0 24 24" fill="none"><path d="m9.7 3.8.8-1h3l.8 1 2 .9 1.3-.3 2.1 2.1-.3 1.3.9 2 .9.8v3l-.9.8-.9 2 .3 1.3-2.1 2.1-1.3-.3-2 .9-.8.9h-3l-.8-.9-2-.9-1.3.3-2.1-2.1.3-1.3-.9-2-.9-.8v-3l.9-.8.9-2-.3-1.3 2.1-2.1 1.3.3 2-.9Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/><circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.7"/></svg>
                                @elseif ($index % 3 === 1)
                                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 20V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v14M2 20h20M8 8h2m4 0h2m-8 4h2m4 0h2m-8 4h2m4 0h2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" fill="none"><path d="m13 2-9 12h7l-1 8 10-13h-7l1-7Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                @endif
                            </div>
                            <h3>{{ $product['title'] }}</h3>
                            <p>{{ $product['description'] }}</p>
                            <ul class="product-features">
                                @foreach ($product['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                            <a href="#kontakt" class="link-cta {{ $index % 3 === 2 ? 'link-cta--green' : '' }}">Kërko Ofertë <span aria-hidden="true">→</span></a>
                        </article>
                    @endforeach
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
                                <div class="service-icon" aria-hidden="true">
                                    @switch($service['icon'])
                                        @case('clipboard')
                                            <svg viewBox="0 0 24 24" fill="none"><path d="M8 3h8M9 3v3m6-3v3M6 6h12v15H6zM9 11h6m-6 4h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            @break
                                        @case('refresh')
                                            <svg viewBox="0 0 24 24" fill="none"><path d="M20 7v5h-5M4 17v-5h5m-3.5-3a7 7 0 0 1 12-2L20 12M4 12l2.5 5a7 7 0 0 0 12-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                            @break
                                        @case('calendar')
                                            <svg viewBox="0 0 24 24" fill="none"><rect x="4" y="5" width="16" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M8 3v4m8-4v4M4 10h16m-12 4h4m-4 3h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                            @break
                                        @default
                                            <svg viewBox="0 0 24 24" fill="none"><path d="M12 3v12m0 0 4-4m-4 4-4-4M5 17v3h14v-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @endswitch
                                </div>
                                <h3>{{ $service['title'] }}</h3>
                                <p>{{ $service['description'] }}</p>
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
                                <span class="about-icon" aria-hidden="true">
                                    @if ($index === 0)
                                        <svg viewBox="0 0 24 24" fill="none"><path d="M12 3 4.5 6v5.2c0 4.6 3.2 8.3 7.5 9.8 4.3-1.5 7.5-5.2 7.5-9.8V6L12 3Z" stroke="currentColor" stroke-width="1.8"/><path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    @else
                                        <svg viewBox="0 0 24 24" fill="none"><path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.4-6.4-1.4 1.4M7 17l-1.4 1.4m12.8 0L17 17M7 7 5.6 5.6M12 8v4l2.5 1.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.8"/></svg>
                                    @endif
                                </span>
                                <div>
                                    <h3>{{ $point['title'] }}</h3>
                                    <p>{{ $point['description'] }}</p>
                                    @if (filled($point['label']))
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
                        @foreach ($stats as $index => $stat)
                            <div class="stat-card">
                                <strong>{{ $stat['value'] }}</strong>
                                <span>{{ $stat['label'] }}</span>
                            </div>
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
        </main>

        <footer class="site-footer">
            <div class="site-footer__inner">
                <a href="#top" class="brand-box brand-box--footer" aria-label="Back2Value, kthehu në krye">
                    <span class="brand-box__inner">
                        <span class="brand-word">back</span>
                        <span class="brand-word">to</span>
                        <span class="brand-word">value</span>
                    </span>
                </a>
                <nav class="footer-nav" aria-label="Lidhje të shpejta">
                    <a href="#baterite">Bateritë</a>
                    <a href="#sherbimet">Shërbimet</a>
                    <a href="#pse-back2value">Pse Ne</a>
                    <a href="#kontakt">Kontakt</a>
                </nav>
                <div class="footer-contact">
                    <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone']) }}">{{ $settings['phone'] }}</a>
                    <a href="mailto:{{ $settings['email'] }}">{{ $settings['email'] }}</a>
                </div>
                <p class="footer-description">{{ $settings['footer_description'] }}</p>
                <div class="footer-bottom">
                    <span>© {{ date('Y') }} Back2Value shpk. Të gjitha të drejtat e rezervuara.</span>
                    <span>Partner zyrtar RID-Batterie në Shqipëri</span>
                </div>
            </div>
        </footer>

        <a href="{{ $whatsappUrl }}" class="floating-wa" aria-label="Na kontaktoni në WhatsApp">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4A8 8 0 1 1 20 11.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M9 8.5c.4 2.5 2 4.1 4.5 5l1.1-1.2 2 .9c-.2 1.2-1.2 2-2.5 2-3.6-.3-6.5-3.2-6.8-6.8 0-1.3.8-2.3 2-2.5l.9 2L9 8.5Z" fill="currentColor"/>
            </svg>
        </a>
    </body>
</html>
