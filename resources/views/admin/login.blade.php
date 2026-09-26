<!DOCTYPE html>
<html lang="sq">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex, nofollow">
        <title>Hyrja në panel · Back2Value</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="admin-login-body">
        <main class="admin-login-card">
            <a href="{{ route('home') }}" class="admin-login-brand" aria-label="Back2Value">
                @include('admin.partials.brand-logo')
                <span class="admin-brand__caption"><small>ADMIN PANEL</small></span>
            </a>
            <p class="admin-login-eyebrow">MENAXHIMI I FAQES</p>
            <h1>Mirë se u kthyet</h1>
            <p class="admin-login-copy">Hyni për të përditësuar përmbajtjen e faqes Back2Value.</p>

            @if (! $adminConfigured)
                <div class="admin-alert admin-alert--error" role="alert">
                    Administratori nuk është konfiguruar. Shtoni ADMIN_EMAIL dhe një ADMIN_PASSWORD prej të paktën 12 karakteresh në mjedis, pastaj ekzekutoni php artisan db:seed.
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="admin-form admin-login-form">
                @csrf
                <label class="admin-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
                </label>
                <label class="admin-field">
                    <span>Fjalëkalimi</span>
                    <input type="password" name="password" autocomplete="current-password" required>
                </label>
                @error('email')
                    <p class="admin-field-error">{{ $message }}</p>
                @enderror
                @error('password')
                    <p class="admin-field-error">{{ $message }}</p>
                @enderror
                <button class="admin-button admin-button--primary" type="submit">Hyr në panel <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'arrow'])</span></button>
            </form>
            <a class="admin-login-back" href="{{ route('home') }}">← Kthehu te faqja</a>
        </main>
    </body>
</html>
