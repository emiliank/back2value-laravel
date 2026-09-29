@props([
    'data' => [],
    'showDownload' => true,
])

@php
    $steps = $data['steps'] ?? [];
    $badge = $data['badge'] ?? null;
    $download = $showDownload ? ($data['download'] ?? null) : null;
@endphp

@if ($steps !== [])
    <section class="page-section">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                @isset($data['eyebrow'])
                    <span class="eyebrow">{{ $data['eyebrow'] }}</span>
                @endisset
                <h2>{{ $data['title'] ?? '' }}</h2>
                @isset($data['description'])
                    <p>{{ $data['description'] }}</p>
                @endisset
            </div>

            <div class="lab-flow">
                <div class="lab-flow__head">
                    <p class="lab-flow__headline">{{ $data['panel_title'] ?? '' }}</p>

                    @isset($data['brand'])
                        <span class="lab-flow__brand">
                            <img
                                src="{{ asset($data['brand']['logo']) }}"
                                alt="{{ $data['brand']['alt'] ?? 'RID Battery · Germany' }}"
                                loading="lazy"
                                decoding="async"
                            >
                        </span>
                    @endisset
                </div>

                <ol class="lab-flow__grid">
                    @foreach ($steps as $index => $step)
                        <li class="lab-flow__card">
                            <span class="lab-flow__figure">
                                <img
                                    src="{{ asset($step['image']) }}"
                                    alt="{{ $step['alt'] ?? $step['title'] }}"
                                    width="{{ $step['width'] ?? null }}"
                                    height="{{ $step['height'] ?? null }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </span>

                            <h3 class="lab-flow__ribbon">
                                <span class="lab-flow__ribbon-text">{{ $index + 1 }}. {{ $step['title'] }}</span>
                            </h3>

                            <p class="lab-flow__copy">{{ $step['description'] }}</p>
                        </li>
                    @endforeach
                </ol>

                <div class="lab-flow__note">
                    <p>{{ $data['note'] ?? '' }}</p>

                    @if ($badge)
                        <img
                            class="lab-flow__badge"
                            src="{{ asset($badge['image']) }}"
                            alt="{{ $badge['alt'] ?? '' }}"
                            width="{{ $badge['width'] ?? null }}"
                            height="{{ $badge['height'] ?? null }}"
                            loading="lazy"
                            decoding="async"
                        >
                    @endif
                </div>

                @if ($download)
                    <p class="lab-flow__download">
                        <a href="{{ asset($download['url']) }}" download="{{ basename($download['url']) }}">{{ $download['label'] }}</a>
                        @isset($download['meta'])
                            <span>{{ $download['meta'] }}</span>
                        @endisset
                    </p>
                @endif
            </div>
        </div>
    </section>
@endif
