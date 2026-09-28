@extends('layouts.public')

@section('title', 'RID Tester & RID Rigenerator | Back2Value')
@section('meta_description', 'RID Tester dhe RID Rigenerator si shërbim: test, diagnostikim dhe rigjenerim baterish industriale nga Back2Value. Falas brenda 3 vjetësh për çdo bateri të re.')

@section('content')
    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']">
        <a class="primary-cta" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
        <a class="secondary-cta" href="{{ route('services.index') }}">Shiko shërbimet</a>
    </x-page-hero>

    <section class="page-section">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">DY PAJISJET TONA</span>
                <h2>Test dhe rigjenerim me pajisje profesionale</h2>
                <p>RID Tester dhe RID Rigenerator nuk janë produkte në shitje — janë shërbimet që ofrojmë për klientët tanë.</p>
            </div>

            <div class="solution-grid">
                @foreach ($page['tools'] as $tool)
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
        </div>
    </section>

    <section class="page-section page-section--soft">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $page['process']['eyebrow'] }}</span>
                <h2>{{ $page['process']['title'] }}</h2>
                <p>{{ $page['process']['description'] }}</p>
            </div>

            <div class="steps-grid">
                @foreach ($page['process']['steps'] as $index => $step)
                    <article class="step-card">
                        <span class="step-card__index">{{ $index + 1 }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-section page-section--green">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">OFERTA PËR KLIENTËT</span>
                <h2>{{ $page['offer']['title'] }}</h2>
                <p>{{ $page['offer']['description'] }}</p>
            </div>

            <div class="page-hero__actions">
                <a class="primary-cta" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
                <a class="secondary-cta" href="{{ route('services.index') }}">Shiko shërbimet</a>
            </div>
        </div>
    </section>

    <x-cta-band
        title="Dëshironi ta dini nëse bateria juaj mund të rigjenerohet?"
        description="Rezervoni një test me RID Tester — ekipi teknik kryen diagnostikimin dhe ju jep raportin zyrtar të matjeve."
        primary-label="Rezervo Diagnostikim"
        secondary-label="Shiko produktet"
        :secondary-url="route('products.index')"
    />
@endsection