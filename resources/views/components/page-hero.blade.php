@props(['eyebrow', 'title', 'description'])

<section class="page-hero">
    <div class="section-shell">
        <nav class="breadcrumb" aria-label="Rruga e faqes">
            <a href="{{ route('home') }}">{{ $navigation['breadcrumb_home'] ?? 'Kryefaqja' }}</a>
            <span aria-hidden="true">/</span>
            <span>{{ $eyebrow }}</span>
        </nav>

        <span class="eyebrow">{{ $eyebrow }}</span>
        <h1>{{ $title }}</h1>
        <p>{{ $description }}</p>

        @if (! $slot->isEmpty())
            <div class="page-hero__actions">{{ $slot }}</div>
        @endif
    </div>
</section>
