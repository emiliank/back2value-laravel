@extends('layouts.public')

@section('title', 'Qëndrueshmëria & përputhshmëria | Back2Value')
@section('meta_description', 'Zgjatja e jetës së baterive, riciklim i licencuar dhe dokumentacion për auditimet: si e menaxhojmë përgjegjshëm çdo bankë baterish në Shqipëri.')

@section('content')
    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']">
        <a class="primary-cta" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
        <a class="secondary-cta" href="{{ route('services.index') }}">Shiko shërbimet</a>
    </x-page-hero>

    <section class="about">
        <div class="section-shell">
            <div class="section-intro section-intro--dark">
                <span class="eyebrow">ANGAZHIMET TONA</span>
                <h2>Katër angazhime operative</h2>
                <p>Zbatojmë të njëjtat standarde për çdo klient — nga një bateri e vetme deri në flota të plota institucionale.</p>
            </div>

            <div class="commitment-grid">
                @foreach ($page['commitments'] as $commitment)
                    <article class="commitment-card">
                        <x-section-icon :name="$commitment['icon']" class="commitment-card__icon" />

                        <h3>{{ $commitment['title'] }}</h3>
                        <p>{{ $commitment['description'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="stats-grid" aria-label="Treguesit e qëndrueshmërisë">
                @foreach ($page['metrics'] as $metric)
                    <div class="stat-card">
                        <strong>{{ $metric['value'] }}</strong>
                        <span>{{ $metric['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="page-section">
        <div class="section-shell">
            <div class="compliance-panel">
                <div>
                    <span class="eyebrow">DOKUMENTACION</span>
                    <h2>{{ $page['compliance']['title'] }}</h2>
                    <p>{{ $page['compliance']['description'] }}</p>
                </div>

                <ul class="info-list">
                    @foreach ($page['compliance']['points'] as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <x-savings-calculator />

    <x-cta-band
        title="Ktheni bateritë në cikël pune, jo në mbetje"
        description="Vlerësojmë kapacitetin real të baterive tuaja dhe ju tregojmë qartë kur rigjenerimi është zgjedhja e saktë."
        primary-label="Rezervo Diagnostikim"
        secondary-label="Qendra e burimeve"
        :secondary-url="route('resources.index')"
    />
@endsection
