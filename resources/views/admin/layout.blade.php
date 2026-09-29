<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Back2Value Admin' }} · Back2Value CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-brand">
                @include('admin.partials.brand-logo')
                <span class="admin-brand__caption">Back2Value<small>CMS</small></span>
            </a>

            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                    <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'home'])</span>
                    Përmbledhja
                </a>

                <span class="admin-nav__label">DATABAZA</span>
                <a href="{{ route('admin.catalog.index') }}" class="{{ request()->routeIs('admin.catalog.*') ? 'is-active' : '' }}">
                    <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'box'])</span>
                    Katalogu i baterive
                </a>

                @foreach (\App\Content\ContentSchema::pages() as $slug => $page)
                    <span class="admin-nav__label">{{ $page['group'] }}</span>
                    <a href="{{ route('admin.content.edit', ['page' => $slug]) }}" class="{{ request()->routeIs('admin.content.edit') && request()->route('page') === $slug ? 'is-active' : '' }}">
                        <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => $page['icon']])</span>
                        {{ $page['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="admin-sidebar__bottom">
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="admin-site-link">
                    <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'external'])</span>
                    Shfaq faqen
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="admin-logout">
                        <span class="admin-nav__icon">@include('admin.partials.icon', ['name' => 'logout'])</span>
                        Dil
                    </button>
                </form>
            </div>
        </aside>

        <main class="admin-main">
            <header class="admin-topbar">
                <div>
                    <p><span>CMS</span> / {{ $title ?? 'Përmbledhja' }}</p>
                    <h1>{{ $title ?? 'Përmbledhja' }}</h1>
                </div>
                <div class="admin-topbar__user">
                    <span class="admin-avatar">{{ Str::of(auth()->user()?->name ?? 'A')->substr(0, 1)->upper() }}</span>
                    <span>{{ auth()->user()?->name }}</span>
                </div>
            </header>

            @if (session('status'))
                <div class="admin-alert admin-alert--success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-alert admin-alert--error">
                    <strong>Plotësoni gabimet më poshtë:</strong>
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
