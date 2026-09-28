@extends('layouts.public')

@section('title', 'Qendra e burimeve | Back2Value')
@section('meta_description', 'Udhëzues praktikë për jetëgjatësinë e baterive industriale: karikim, mirëmbajtje, kosto, riciklim dhe përgatitja e dokumentacionit për auditime.')

@section('content')
    @php
        $whatsappUrl = filled($settings['whatsapp'] ?? null)
            ? 'https://wa.me/'.preg_replace('/\D+/', '', $settings['whatsapp']).'?text='.rawurlencode('Pyetje teknike për bateritë tona.')
            : route('home').'#kontakt';
    @endphp

    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']">
        <a class="primary-cta" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
        <a class="secondary-cta" href="{{ route('services.index') }}">Shërbimet teknike</a>
    </x-page-hero>

    <section class="page-section">
        <div class="section-shell">
            <div class="article-grid">
                @foreach ($page['articles'] as $article)
                    <article class="article-card">
                        <div class="article-meta">
                            <span class="article-meta__category">{{ $article['category'] }}</span>
                            <span aria-hidden="true">·</span>
                            <span>{{ $article['reading_time'] }}</span>
                        </div>

                        <h3>{{ $article['title'] }}</h3>
                        <p>{{ $article['excerpt'] }}</p>

                        @if (filled($article['paragraphs'] ?? []))
                            <details class="article-more">
                                <summary>Lexo udhëzuesin e plotë</summary>

                                @foreach ($article['paragraphs'] as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </details>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-section page-section--soft">
        <div class="section-shell">
            <div class="compliance-panel">
                <div>
                    <span class="eyebrow">PLANIFIKIM</span>
                    <h2>Nga leximi në plan mirëmbajtjeje</h2>
                    <p>
                        Materialet janë përgatitur nga ekipi teknik i Back2Value për klientët që duan të ulin kostot dhe të
                        provojnë mirëmbajtjen para audituesve. Nëse doni një plan konkret për bankën tuaj të baterive,
                        nisni me një diagnostikim.
                    </p>
                </div>

                <ul class="info-list">
                    <li>Inventar i plotë i baterive me lokacion dhe kritikalitet</li>
                    <li>Frekuencë inspektimesh sipas rëndësisë së sistemit</li>
                    <li>Matje të kapacitetit dhe rezistencës së brendshme në çdo vizitë</li>
                    <li>Raport zyrtar teknik për çdo cikël mirëmbajtjeje</li>
                </ul>
            </div>
        </div>
    </section>

    <x-cta-band
        title="Keni një pyetje teknike për bateritë tuaja?"
        description="Na shkruani ose rezervoni një diagnostikim — përgjigjemi me të dhëna reale nga matjet, jo me supozime."
        primary-label="Rezervo Diagnostikim"
        secondary-label="Kontakto në WhatsApp"
        :secondary-url="$whatsappUrl"
    />
@endsection
