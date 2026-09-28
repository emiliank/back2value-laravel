@props(['settings', 'whatsappUrl' => null])

@php
    $siteLogoUrl = blank($settings['logo_image'] ?? null) ? null : (
        str_starts_with($settings['logo_image'], 'logos/')
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($settings['logo_image'])
            : asset($settings['logo_image'])
    );

    $footerNav = [
        ['label' => 'Bateritë', 'url' => route('products.index')],
        ['label' => 'Shërbimet', 'url' => route('services.index')],
        ['label' => 'Rigjenerim', 'url' => route('regeneration.index')],
        ['label' => 'Zgjidhjet', 'url' => route('solutions.index')],
        ['label' => 'Qëndrueshmëria', 'url' => route('sustainability.index')],
        ['label' => 'Burimet', 'url' => route('resources.index')],
        ['label' => 'Diagnostikim', 'url' => route('diagnostics.create')],
    ];
@endphp

<footer class="site-footer">
    <div class="site-footer__inner">
        <a class="site-footer__brand" href="{{ route('home') }}" aria-label="Back2Value home">
            @if ($siteLogoUrl)
                <img class="site-footer__logo" src="{{ $siteLogoUrl }}" alt="Back2Value logo">
            @else
                <span class="site-footer__word">Back<span>2</span>Value</span>
            @endif
        </a>

        <nav class="footer-nav" aria-label="Lidhje të shpejta">
            @foreach ($footerNav as $item)
                <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="footer-contact">
            <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone'] ?? '') }}">{{ $settings['phone'] ?? '' }}</a>
            <a href="mailto:{{ $settings['email'] ?? '' }}">{{ $settings['email'] ?? '' }}</a>
            <a href="{{ $whatsappUrl }}">WhatsApp</a>
        </div>

        <p class="footer-description">{{ $settings['footer_description'] ?? '' }}</p>

        <div class="footer-bottom">
            <span>© {{ date('Y') }} Back2Value shpk. Të gjitha të drejtat e rezervuara.</span>
            <span>Partner zyrtar RID-Batterie në Shqipëri</span>
        </div>
    </div>
</footer>
