@props(['data' => []])

@php
    $steps = $data['lab_steps'] ?? [];
    $siteLogoUrl = site_image(site_logo());
@endphp

@if ($steps !== [])
    <section class="page-section">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $data['lab_eyebrow'] ?? '' }}</span>
                <h2>{{ $data['lab_title'] ?? '' }}</h2>
                <p>{{ $data['lab_description'] ?? '' }}</p>
            </div>

            <div class="lab-flow">
                <div class="lab-flow__head">
                    <p class="lab-flow__headline">{{ $data['lab_panel_title'] ?? '' }}</p>

                    <div class="lab-flow__brands">
                        <span class="lab-flow__brand lab-flow__brand--site">
                            @if ($siteLogoUrl)
                                <img src="{{ $siteLogoUrl }}" alt="Back2Value logo" loading="lazy" decoding="async">
                            @else
                                <span class="site-brand-word">Back<span>2</span>Value</span>
                            @endif
                        </span>

                        @if (filled($data['lab_brand_logo'] ?? null))
                            <span class="lab-flow__brand">
                                <img
                                    src="{{ site_image($data['lab_brand_logo']) }}"
                                    alt="{{ $data['lab_brand_alt'] ?? 'RID Battery · Germany' }}"
                                    loading="lazy"
                                    decoding="async"
                                >
                            </span>
                        @endif
                    </div>
                </div>

                <ol class="lab-flow__grid">
                    @foreach ($steps as $index => $step)
                        <li class="lab-flow__card">
                            <span class="lab-flow__figure">
                                <img
                                    src="{{ site_image($step['image'] ?? null) }}"
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
                    <p>{{ $data['lab_note'] ?? '' }}</p>

                    @if (filled($data['lab_badge_image'] ?? null))
                        <img
                            class="lab-flow__badge"
                            src="{{ site_image($data['lab_badge_image']) }}"
                            alt="{{ $data['lab_badge_alt'] ?? '' }}"
                            width="{{ $data['lab_badge_width'] ?? null }}"
                            height="{{ $data['lab_badge_height'] ?? null }}"
                            loading="lazy"
                            decoding="async"
                        >
                    @endif
                </div>
            </div>
        </div>
    </section>
@endif
