@props(['name' => 'shield'])

<span {{ $attributes->merge(['class' => 'icon-badge']) }} aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none">
        @switch($name)
            @case('battery')
                <rect x="2.5" y="7" width="16" height="10" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                <path d="M21.5 10.5v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M6.5 12h5m-2.5-2.5v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                @break
            @case('coins')
                <ellipse cx="12" cy="6.5" rx="7" ry="3" stroke="currentColor" stroke-width="1.8"/>
                <path d="M5 6.5v5c0 1.7 3.1 3 7 3s7-1.3 7-3v-5" stroke="currentColor" stroke-width="1.8"/>
                <path d="M5 11.5v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6" stroke="currentColor" stroke-width="1.8"/>
                @break
            @case('leaf')
                <path d="M20 4c-9 0-14 4.6-14 10a6 6 0 0 0 6 6c5.6 0 8-5 8-16Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M6.5 19.5C7 13 10.5 8.8 16 6.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                @break
            @case('recycle')
                <path d="M4 12a8 8 0 0 1 13.6-5.7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M20 12a8 8 0 0 1-13.6 5.7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M17.6 2.8v3.6h-3.6M6.4 21.2v-3.6h3.6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                @break
            @case('document')
                <path d="M7 3h7l5 5v13H7z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M14 3v5h5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M10 13h6m-6 4h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                @break
            @case('academic')
                <path d="m3 9 9-4 9 4-9 4-9-4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M7 11.5V16c0 1.4 2.2 2.5 5 2.5s5-1.1 5-2.5v-4.5" stroke="currentColor" stroke-width="1.8"/>
                @break
            @case('factory')
                <path d="M3 20h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M4 20V9l5 3.5V9l5 3.5V9l5 3.5V20" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M7 16.5h.01M12 16.5h.01M17 16.5h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                @break
            @case('sun')
                <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.8"/>
                <path d="M12 3v2.4m0 13.2V21M3 12h2.4m13.2 0H21M5.6 5.6l1.7 1.7m9.4 9.4 1.7 1.7m0-12.8-1.7 1.7M7.3 16.7l-1.7 1.7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                @break
            @case('car')
                <path d="M4 16v-3.6L6.4 8h11.2L20 12.4V16" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M3 16h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M7.6 12.4h8.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <circle cx="7.5" cy="17.8" r="1.7" stroke="currentColor" stroke-width="1.8"/>
                <circle cx="16.5" cy="17.8" r="1.7" stroke="currentColor" stroke-width="1.8"/>
                @break
            @case('landmark')
                <path d="M3 10 12 4.5 21 10" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M6 10v8m4-8v8m4-8v8m4-8v8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="M3 21h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                @break
            @case('download')
                <path d="M12 4v11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                <path d="m8 11 4 4 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4 19h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                @break
            @case('truck')
                <path d="M2 6h12v10H2z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="M14 9h3.6L20 12v4h-6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <circle cx="6" cy="17.5" r="2" stroke="currentColor" stroke-width="1.8"/>
                <circle cx="17" cy="17.5" r="2" stroke="currentColor" stroke-width="1.8"/>
                @break
            @case('pulse')
                <path d="M3 12h4l2-5 3 10 2-5h7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                @break
            @case('layers')
                <path d="M12 3 3 7.5l9 4.5 9-4.5L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="m3 12.5 9 4.5 9-4.5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="m3 17 9 4.5 9-4.5" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                @break
            @case('zap')
                <path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                @break
            @default
                <path d="M12 3 4.5 6v5.2c0 4.6 3.2 8.3 7.5 9.8 4.3-1.5 7.5-5.2 7.5-9.8V6L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        @endswitch
    </svg>
</span>
