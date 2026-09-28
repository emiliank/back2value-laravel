@extends('layouts.public')

@section('title', 'Zgjidhjet sipas sektorit | Back2Value')
@section('meta_description', 'Zgjidhje baterish për industri, energji të rinovueshme, automotive dhe sektorin publik: kontrata SLA, raportim teknik, rigjenerim dhe grumbullim në Shqipëri.')

@section('content')
    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']">
        <a class="primary-cta" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
        <a class="secondary-cta" href="{{ route('services.index') }}">Shiko shërbimet</a>
    </x-page-hero>

    <section class="page-section">
        <div class="section-shell">
            <div class="solution-grid">
                @foreach ($page['items'] as $item)
                    <article class="solution-card">
                        <x-section-icon :name="$item['icon']" class="solution-card__icon" />

                        <h3>{{ $item['title'] }}</h3>
                        <p class="solution-card__audience">{{ $item['audience'] }}</p>
                        <p>{{ $item['description'] }}</p>

                        @if (filled($item['benefits'] ?? []))
                            <ul class="feature-list">
                                @foreach ($item['benefits'] as $benefit)
                                    <li>{{ $benefit }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if (filled($item['applications'] ?? []))
                            <div class="solution-apps">
                                @foreach ($item['applications'] as $application)
                                    <a class="solution-app" href="{{ route('products.index', ['application' => $application]) }}">
                                        {{ $applicationFilters[$application]['label'] ?? $application }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-section page-section--green">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">SI PUNOJMË</span>
                <h2>Nga vlerësimi teknik deri në raport</h2>
                <p>Çdo angazhim ndjek të njëjtat hapa: matje, vendim teknik i bazuar në të dhëna, ekzekutim dhe dokumentacion.</p>
            </div>

            <div class="steps-grid">
                <article class="step-card">
                    <span class="step-card__index">1</span>
                    <h3>Inventar &amp; kritikalitet</h3>
                    <p>Regjistrojmë modelin, kapacitetin, datën e instalimit, lokacionin dhe kritikalitetin e çdo banke baterish.</p>
                </article>
                <article class="step-card">
                    <span class="step-card__index">2</span>
                    <h3>Matje &amp; vendim</h3>
                    <p>Testojmë kapacitetin dhe rezistencën e brendshme, pastaj krahasojmë rigjenerimin me zëvendësimin.</p>
                </article>
                <article class="step-card">
                    <span class="step-card__index">3</span>
                    <h3>Ekzekutim &amp; raport</h3>
                    <p>Aplikojmë shërbimin e zgjedhur, testojmë përsëri dhe dorëzojmë raportin zyrtar teknik për auditim.</p>
                </article>
            </div>
        </div>
    </section>

    <x-cta-band
        title="Gjeni zgjidhjen për sektorin tuaj"
        description="Nga flotat e pirunëve deri në bankat e baterive të data center-it — përshtatim shërbimin, kalendarin dhe dokumentacionin sipas kërkesave tuaja."
        primary-label="Rezervo Diagnostikim"
        secondary-label="Katalogu i produkteve"
        :secondary-url="route('products.index')"
    />
@endsection
