@php
    $brandSettings = app(\App\Services\SiteContentService::class)->all()['settings'] ?? [];
    $brandLogo = blank($brandSettings['logo_image'] ?? null) ? null : (
        str_starts_with($brandSettings['logo_image'], 'logos/')
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($brandSettings['logo_image'])
            : asset($brandSettings['logo_image'])
    );
@endphp

<span class="brand-box admin-brand__logo{{ $brandLogo ? ' brand-box--image' : '' }}" aria-hidden="true">
    @if ($brandLogo)
        <img class="site-logo admin-brand__logo__image" src="{{ $brandLogo }}" alt="Back2Value logo">
    @else
        <span class="brand-box__inner">
            <span class="brand-word">back</span>
            <span class="brand-word">to</span>
            <span class="brand-word">value</span>
        </span>
    @endif
</span>
