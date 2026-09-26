<svg class="admin-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
    @switch($name)
        @case('home')
            <path d="m3 10 9-7 9 7"/>
            <path d="M5 9v11h14V9M9 20v-7h6v7"/>
            @break
        @case('settings')
            <path d="M4 6h16M4 12h16M4 18h16"/>
            <circle cx="8" cy="6" r="2" fill="currentColor" stroke="none"/>
            <circle cx="15" cy="12" r="2" fill="currentColor" stroke="none"/>
            <circle cx="10" cy="18" r="2" fill="currentColor" stroke="none"/>
            @break
        @case('box')
            <path d="m12 3 9 5-9 5-9-5 9-5Z"/>
            <path d="M3 8v9l9 5 9-5V8M12 13v9M7.5 5.5l9 5"/>
            @break
        @case('services')
            <path d="M14.7 6.3a5 5 0 0 0-6.6 6.6L3 18l3 3 5.1-5.1a5 5 0 0 0 6.6-6.6L14 12l-2-2 2.7-3.7Z"/>
            @break
        @case('shield')
            <path d="m12 3 8 3v5c0 5-3.4 8.5-8 10-4.6-1.5-8-5-8-10V6l8-3Z"/>
            <path d="m9 12 2 2 4-4"/>
            @break
        @case('external')
            <path d="M14 4h6v6M20 4l-9 9"/>
            <path d="M18 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6"/>
            @break
        @case('logout')
            <path d="M10 17l5-5-5-5M15 12H3"/>
            <path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>
            @break
        @case('arrow')
            <path d="M5 12h14m-6-6 6 6-6 6"/>
            @break
        @case('check')
            <path d="m5 12 4 4L19 6"/>
            @break
        @case('plus')
            <path d="M12 5v14M5 12h14"/>
            @break
        @case('sparkles')
            <path d="m12 3 1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2L12 3Z"/>
            <path d="m19 14 1 2.5 2.5 1-2.5 1L19 21l-1-2.5-2.5-1 2.5-1L19 14Z"/>
            @break
        @case('image')
            <rect x="3" y="4" width="18" height="16" rx="2"/>
            <circle cx="8.5" cy="9" r="1.5"/>
            <path d="m21 15-5-5L5 20"/>
            @break
        @case('chart')
            <path d="M4 20V10m5 10V4m5 16v-7m5 7V7"/>
            @break
    @endswitch
</svg>
