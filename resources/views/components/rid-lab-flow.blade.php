@props([
    'data' => [],
    'siteLogo' => null,
])

@php
    $steps = $data['steps'] ?? [];
    $badge = $data['badge'] ?? null;
    $siteLogoUrl = blank($siteLogo) ? null : (
        str_starts_with($siteLogo, 'logos/')
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($siteLogo)
            : asset($siteLogo)
    );
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

                    <div class="lab-flow__brands">
                        <span class="lab-flow__brand lab-flow__brand--site">
                            @if ($siteLogoUrl)
                                <img
                                    src="{{ $siteLogoUrl }}"
                                    alt="Back2Value logo"
                                    loading="lazy"
                                    decoding="async"
                                >
                            @else
                                <span class="site-brand-word">Back<span>2</span>Value</span>
                            @endif
                        </span>

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
            </div>
        </div>
    </section>
@endif
