@php
    $brandLogo = site_image(site('settings', 'logo_image'));
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
