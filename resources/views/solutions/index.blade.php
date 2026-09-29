@extends('layouts.public')

@section('title', $page['meta_title'])
@section('meta_description', $page['meta_description'])

@section('content')
    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']">
        <a class="primary-cta" href="{{ site_link('diagnostics') }}">{{ $page['hero_cta_primary'] }}</a>
        <a class="secondary-cta" href="{{ site_link($page['hero_cta_secondary_target'] ?? 'services') }}">{{ $page['hero_cta_secondary'] }}</a>
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
                <span class="eyebrow">{{ $page['process_eyebrow'] }}</span>
                <h2>{{ $page['process_title'] }}</h2>
                <p>{{ $page['process_description'] }}</p>
            </div>

            <div class="steps-grid">
                @foreach ($page['process_steps'] as $index => $step)
                    <article class="step-card">
                        <span class="step-card__index">{{ $index + 1 }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <x-cta-band
        :title="$page['cta_title']"
        :description="$page['cta_description']"
        :primary-label="$page['cta_primary']"
        :primary-url="site_link($page['cta_primary_target'] ?? 'diagnostics')"
        :secondary-label="$page['cta_secondary']"
        :secondary-url="site_link($page['cta_secondary_target'] ?? 'products')"
    />
@endsection
