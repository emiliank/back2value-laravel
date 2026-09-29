@php
    $whatsappNumber = preg_replace('/\D+/', '', $settings['whatsapp'] ?? '355692734476');
    $whatsappMessage = $settings['whatsapp_message'] ?? 'Përshëndetje! Po interesohem për një ofertë për bateri industriale.';
    $whatsappUrl = 'https://wa.me/'.$whatsappNumber.'?text='.rawurlencode($whatsappMessage);
    $accentColor = preg_match('/\A#[0-9a-fA-F]{6}\z/', $settings['accent_color'] ?? '') ? $settings['accent_color'] : '#17b78b';
@endphp
<!DOCTYPE html>
<html lang="sq">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="@yield('meta_description', $settings['meta_description'] ?? '')">
        <meta name="theme-color" content="#101b32">
        <meta property="og:site_name" content="{{ $settings['meta_title'] ?? 'Back2Value' }}">
        <meta property="og:type" content="website">
        <meta property="og:title" content="@yield('title', $settings['meta_title'] ?? 'Back2Value')">
        <meta property="og:description" content="@yield('meta_description', $settings['meta_description'] ?? '')">
        <meta property="og:image" content="{{ asset('images/back2value-logo.png') }}">
        <meta property="og:image:alt" content="{{ $navigation['brand_alt'] ?? 'Logo Back2Value' }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ asset('images/back2value-logo.png') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', $settings['meta_title'] ?? 'Back2Value')</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body style="--green: {{ $accentColor }}">
        <x-public-header :settings="$settings" :active="$activeNav ?? null" :whatsapp-url="$whatsappUrl" />

        <main id="main">
            @yield('content')
        </main>

        <x-public-footer :settings="$settings" :whatsapp-url="$whatsappUrl" />

        <a href="{{ $whatsappUrl }}" class="floating-wa" aria-label="{{ $navigation['floating_whatsapp_label'] ?? 'Na kontaktoni në WhatsApp' }}">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M20 11.5a8 8 0 0 1-11.8 7L4 20l1.5-4A8 8 0 1 1 20 11.5Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M9 8.5c.4 2.5 2 4.1 4.5 5l1.1-1.2 2 .9c-.2 1.2-1.2 2-2.5 2-3.6-.3-6.5-3.2-6.8-6.8 0-1.3.8-2.3 2-2.5l.9 2L9 8.5Z" fill="currentColor"/>
            </svg>
        </a>
    </body>
</html>
