<!DOCTYPE html>
<html lang="sq">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <meta name="theme-color" content="#101b32">
        <title>{{ $title ?? 'Paneli i administrimit' }} · Back2Value</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-body">
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <a class="admin-brand" href="{{ route('admin.dashboard') }}">
                    @include('admin.partials.brand-logo')
                    <span class="admin-brand__caption"><small>ADMIN PANEL</small></span>
                </a>

                <nav class="admin-nav" aria-label="Administrimi i faqes">
                    <span class="admin-nav__label">MENAXHIMI I FAQES</span>
                    <a href="{{ route('admin.dashboard') }}" @class(['is-active' => $activeSection === 'dashboard'])>
                        <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'home'])</span> Përmbledhje
                    </a>
                    <a href="{{ route('admin.settings') }}" @class(['is-active' => $activeSection === 'settings'])>
                        <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'settings'])</span> Cilësimet &amp; faqja
                    </a>
                    <a href="{{ route('admin.products') }}" @class(['is-active' => $activeSection === 'products'])>
                        <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'box'])</span> Produktet &amp; imazhet
                    </a>
                    <a href="{{ route('admin.services') }}" @class(['is-active' => $activeSection === 'services'])>
                        <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'services'])</span> Shërbimet
                    </a>
                    <a href="{{ route('admin.about') }}" @class(['is-active' => $activeSection === 'about'])>
                        <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'shield'])</span> Për Back2Value
                    </a>
                </nav>

                <div class="admin-sidebar__bottom">
                    <a class="admin-site-link" href="{{ route('home') }}" target="_blank" rel="noreferrer">
                        Shiko faqen live <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'external'])</span>
                    </a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button class="admin-logout" type="submit">
                            <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'logout'])</span> Dil nga paneli
                        </button>
                    </form>
                </div>
            </aside>

            <main class="admin-main">
                <header class="admin-topbar">
                    <div>
                        <p>BACK2VALUE <span>/</span> PANELI</p>
                        <h1>{{ $title ?? 'Paneli i administrimit' }}</h1>
                    </div>
                    <div class="admin-topbar__user">
                        <span class="admin-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                </header>

                @if (session('status'))
                    <div class="admin-alert admin-alert--success" role="status">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="admin-alert admin-alert--error" role="alert">
                        <strong>Kontrolloni fushat e shënuara më poshtë.</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </body>
</html>
