@props(['settings' => [], 'active' => null, 'whatsappUrl' => null])

@php
    $headerItems = $navigation['header_items'] ?? [];
    $secondaryLabel = $navigation['header_cta_secondary'] ?? 'Rezervo Diagnostikim';
    $secondaryUrl = site_link($navigation['header_cta_secondary_target'] ?? 'diagnostics');
    $primaryLabel = $navigation['header_cta_primary'] ?? 'Na Kontaktoni';
    $brandAlt = $navigation['brand_alt'] ?? 'Back2Value logo';
    $siteLogoUrl = site_image($settings['logo_image'] ?? null);
@endphp

<header class="site-header">
    <div class="site-header__inner">
        <a class="site-brand-mark" href="{{ route('home') }}" aria-label="{{ $brandAlt }}">
            @if ($siteLogoUrl)
                <img class="site-brand-mark__image" src="{{ $siteLogoUrl }}" alt="{{ $brandAlt }}">
            @else
                <span class="site-brand-word">Back<span>2</span>Value</span>
            @endif
        </a>

        <nav class="main-nav" aria-label="Navigimi kryesor">
            @foreach ($headerItems as $item)
                <a href="{{ site_link($item['target'] ?? 'home') }}" @class(['is-active' => $active === ($item['target'] ?? null)])>{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="header-actions">
            <a class="secondary-cta secondary-cta--compact" href="{{ $secondaryUrl }}">{{ $secondaryLabel }}</a>
            <a class="primary-cta header-cta" href="{{ $whatsappUrl }}">{{ $primaryLabel }}</a>
        </div>

        <button type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="Hap menunë" class="mobile-toggle">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>

        <nav id="mobile-menu" data-mobile-menu class="mobile-menu" aria-label="Navigimi për celular" hidden>
            @foreach ($headerItems as $item)
                <a href="{{ site_link($item['target'] ?? 'home') }}" @class(['is-active' => $active === ($item['target'] ?? null)])>{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ $secondaryUrl }}">{{ $secondaryLabel }}</a>
            <a class="primary-cta" href="{{ $whatsappUrl }}">{{ $primaryLabel }}</a>
        </nav>
    </div>
</header>
