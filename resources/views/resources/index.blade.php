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
                                <summary>{{ $page['article_more_label'] }}</summary>

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
                    <span class="eyebrow">{{ $page['planning_eyebrow'] }}</span>
                    <h2>{{ $page['planning_title'] }}</h2>
                    <p>{{ $page['planning_description'] }}</p>
                </div>

                <ul class="info-list">
                    @foreach ($page['planning_points'] as $point)
                        <li>{{ $point }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <x-cta-band
        :title="$page['cta_title']"
        :description="$page['cta_description']"
        :primary-label="$page['cta_primary']"
        :primary-url="site_link($page['cta_primary_target'] ?? 'diagnostics')"
        :secondary-label="$page['cta_secondary']"
        :secondary-url="site_link($page['cta_secondary_target'] ?? 'services')"
    />
@endsection
