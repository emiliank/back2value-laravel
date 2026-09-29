@extends('layouts.public')

@section('title', $page['meta_title'])
@section('meta_description', $page['meta_description'])

@section('content')
    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']">
        <a class="primary-cta" href="{{ site_link('diagnostics') }}">{{ $page['hero_cta_primary'] }}</a>
        <a class="secondary-cta" href="{{ site_link($page['hero_cta_secondary_target'] ?? 'services') }}">{{ $page['hero_cta_secondary'] }}</a>
    </x-page-hero>

    <section class="about">
        <div class="section-shell">
            <div class="section-intro section-intro--dark">
                <span class="eyebrow">{{ $page['commitments_eyebrow'] }}</span>
                <h2>{{ $page['commitments_title'] }}</h2>
                <p>{{ $page['commitments_description'] }}</p>
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
                    <span class="eyebrow">{{ $page['compliance_eyebrow'] }}</span>
                    <h2>{{ $page['compliance_title'] }}</h2>
                    <p>{{ $page['compliance_description'] }}</p>
                </div>

                <ul class="info-list">
                    @foreach ($page['compliance_points'] as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <x-savings-calculator />

    <x-cta-band
        :title="$page['cta_title']"
        :description="$page['cta_description']"
        :primary-label="$page['cta_primary']"
        :primary-url="site_link($page['cta_primary_target'] ?? 'diagnostics')"
        :secondary-label="$page['cta_secondary']"
        :secondary-url="site_link($page['cta_secondary_target'] ?? 'resources')"
    />
@endsection
