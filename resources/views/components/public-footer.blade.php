@props(['settings' => [], 'whatsappUrl' => null])

@php
    $footerItems = $navigation['footer_items'] ?? [];
    $brandAlt = $navigation['brand_alt'] ?? 'Back2Value logo';
    $siteLogoUrl = site_image($settings['logo_image'] ?? null);
    $mapUrl = 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($settings['map_query'] ?? $settings['address'] ?? '');
@endphp

<footer class="site-footer">
    <div class="site-footer__inner">
        <a class="site-footer__brand" href="{{ route('home') }}" aria-label="{{ $brandAlt }}">
            @if ($siteLogoUrl)
                <img class="site-footer__logo" src="{{ $siteLogoUrl }}" alt="{{ $brandAlt }}">
            @else
                <span class="site-footer__word">Back<span>2</span>Value</span>
            @endif
        </a>

        <nav class="footer-nav" aria-label="Lidhje të shpejta">
            @foreach ($footerItems as $item)
                <a href="{{ site_link($item['target'] ?? 'home') }}">{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="footer-contact">
            <a href="tel:{{ preg_replace('/\s+/', '', $settings['phone'] ?? '') }}">{{ $settings['phone'] ?? '' }}</a>
            <a href="mailto:{{ $settings['email'] ?? '' }}">{{ $settings['email'] ?? '' }}</a>
            <a href="{{ $whatsappUrl }}">{{ $navigation['footer_whatsapp_label'] ?? 'WhatsApp' }}</a>
            <a href="{{ $mapUrl }}" target="_blank" rel="noopener">{{ site('contact_cards.map_label', 'Hap në Maps') }}</a>
        </div>

        <p class="footer-description">{{ $settings['footer_description'] ?? '' }}</p>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} {{ $navigation['footer_copyright'] ?? 'Back2Value shpk. Të gjitha të drejtat e rezervuara.' }}</span>
            @if (filled($navigation['footer_partner_line'] ?? null))
                <span>{{ $navigation['footer_partner_line'] }}</span>
            @endif
        </div>
    </div>
</footer>
