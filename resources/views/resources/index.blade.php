@extends('layouts.public')

@section('title', $about['title'])
@section('meta_description', $about['description'])

@section('content')
    <x-page-hero :eyebrow="$about['eyebrow']" :title="$about['title']" :description="$about['description']" />

    <section class="about">
        <div class="section-shell">
            <div class="about-grid">
                @foreach ($about['points'] as $point)
                    <article class="about-card">
                        <x-section-icon :name="$point['icon']" class="about-icon" />

                        <div>
                            <h2>{{ $point['title'] }}</h2>
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

    @if (! empty($about['faqs']))
        <section class="page-section page-section--soft faq-section">
            <div class="section-shell">
                <div class="section-intro section-intro--small">
                    <h2>{{ __('site.frequently_asked_questions') }}</h2>
                </div>

                <div class="faq-list">
                    @foreach ($about['faqs'] as $faq)
                        <details class="faq-item">
                            <summary>{{ $faq['question'] }}</summary>
                            <p>{{ $faq['answer'] }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </section>
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
@endsection
