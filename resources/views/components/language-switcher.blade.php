@props(['class' => ''])

@php
    $current = current_locale();
    $locales = available_locales();
@endphp

@if (count($locales) > 1)
    <div class="language-switcher {{ $class }}" role="group" aria-label="{{ __('site.language') }}">
        @foreach ($locales as $code => $meta)
            <a
                href="{{ locale_url($code) }}"
                hreflang="{{ $meta['og_locale'] ?? $code }}"
                title="{{ $meta['native'] ?? strtoupper($code) }}"
                aria-label="{{ $meta['native'] ?? strtoupper($code) }}"
                lang="{{ $code }}"
                @class(['language-switcher__option', 'is-active' => $code === $current])
                @if ($code === $current) aria-current="true" @endif
            >
                <span class="language-switcher__flag" aria-hidden="true">{{ $meta['flag'] ?? '' }}</span>
                <span class="language-switcher__label" lang="{{ $code }}">{{ $meta['code'] ?? strtoupper($code) }}</span>
            </a>
        @endforeach
    </div>
@endif
