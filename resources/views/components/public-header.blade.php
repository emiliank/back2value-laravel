@props(['settings', 'active' => null, 'whatsappUrl' => null])

@php
    $navItems = [
        'products' => ['label' => 'Bateritë', 'url' => route('products.index')],
        'services' => ['label' => 'Shërbimet', 'url' => route('services.index')],
        'solutions' => ['label' => 'Zgjidhjet', 'url' => route('solutions.index')],
        'sustainability' => ['label' => 'Qëndrueshmëria', 'url' => route('sustainability.index')],
        'resources' => ['label' => 'Burimet', 'url' => route('resources.index')],
        'contact' => ['label' => 'Kontakt', 'url' => route('home').'#kontakt'],
    ];

    $siteLogoUrl = blank($settings['logo_image'] ?? null) ? null : (
        str_starts_with($settings['logo_image'], 'logos/')
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($settings['logo_image'])
            : asset($settings['logo_image'])
    );
@endphp

<header class="site-header">
    <div class="site-header__inner">
        <a class="site-brand-mark" href="{{ route('home') }}" aria-label="Back2Value home">
            @if ($siteLogoUrl)
                <img class="site-brand-mark__image" src="{{ $siteLogoUrl }}" alt="Back2Value logo">
            @else
                <span class="site-brand-word">Back<span>2</span>Value</span>
            @endif
        </a>

        <nav class="main-nav" aria-label="Navigimi kryesor">
            @foreach ($navItems as $key => $item)
                <a href="{{ $item['url'] }}" @class(['is-active' => $active === $key])>{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="header-actions">
            <a class="secondary-cta secondary-cta--compact" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
            <a class="primary-cta header-cta" href="{{ $whatsappUrl }}">Na Kontaktoni</a>
        </div>

        <button type="button" data-mobile-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="Hap menunë" class="mobile-toggle">
            <svg aria-hidden="true" viewBox="0 0 24 24" fill="none">
                <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
        </button>

        <nav id="mobile-menu" data-mobile-menu class="mobile-menu" aria-label="Navigimi për celular" hidden>
            @foreach ($navItems as $key => $item)
                <a href="{{ $item['url'] }}" @class(['is-active' => $active === $key])>{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
            <a class="primary-cta" href="{{ $whatsappUrl }}">Na Kontaktoni</a>
        </nav>
    </div>
</header>
