@extends('layouts.public')

@section('title', ($page['meta_title'] ?? 'Shërbimet Teknike | Back2Value').' | Back2Value')
@section('meta_description', $page['meta_description'] ?? '')

@section('content')
    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']">
        <a class="primary-cta" href="{{ site_link($page['hero_cta_primary_target'] ?? 'diagnostics') }}">{{ $page['hero_cta_primary'] ?? 'Rezervo Diagnostikim' }}</a>
        <a class="secondary-cta" href="{{ site_link($page['hero_cta_secondary_target'] ?? 'calculator') }}">{{ $page['hero_cta_secondary'] ?? 'Kalkulator kursimi' }}</a>
    </x-page-hero>

    <section class="page-section">
        <div class="section-shell">
            <div class="service-detail-grid">
                @foreach ($page['items'] as $item)
                    <article class="service-detail">
                        <x-section-icon :name="$item['icon']" class="service-detail__icon" />

                        <div>
                            <h3>{{ $item['title'] }}</h3>
                            <p>{{ $item['description'] }}</p>

                            @if (filled($item['features'] ?? []))
                                <ul class="feature-list">
                                    @foreach ($item['features'] as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="page-hero__actions">
                <a class="link-cta link-cta--green" href="{{ site_link($page['detail_link_target'] ?? 'regeneration') }}">{{ $page['detail_link'] ?? 'RID Tester & RID Rigenerator' }} <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <x-savings-calculator />

    <x-drop-off-points :section="$dropOffPoints" />

    <section class="page-section page-section--soft">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">{{ $page['maintenance_eyebrow'] }}</span>
                <h2>{{ $page['maintenance_title'] }}</h2>
                <p>{{ $page['maintenance_description'] }}</p>
            </div>

            <div class="form-shell">
                <div class="form-card">
                    <div class="status-alert" data-inquiry-feedback hidden></div>

                    <form
                        method="POST"
                        action="{{ route('api.maintenance-inquiries.store') }}"
                        data-maintenance-inquiry
                        class="form-grid"
                    >
                        <label class="field">
                            <span class="field__label">{{ $page['form_company_label'] }}</span>
                            <input class="field__control" type="text" name="company_name" maxlength="255" required>
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['form_contact_label'] }}</span>
                            <input class="field__control" type="text" name="contact_name" maxlength="255" required>
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['form_email_label'] }}</span>
                            <input class="field__control" type="email" name="email" maxlength="255" required>
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['form_phone_label'] }}</span>
                            <input class="field__control" type="tel" name="phone" maxlength="50" required>
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['form_sector_label'] }}</span>
                            <select class="field__control" name="sector" required>
                                @foreach ($page['form_sectors'] as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="field">
                            <span class="field__label">Numri i baterive në flotë</span>
                            <input class="field__control" type="number" name="fleet_size" min="1" step="1" value="{{ $page['form_fleet_default'] }}" required>
                        </label>

                        <label class="field field--wide">
                            <span class="field__label">{{ $page['form_requirements_label'] }}</span>
                            <textarea class="field__control" name="requirements" maxlength="2000" required></textarea>
                            <span class="field__hint">{{ $page['form_requirements_hint'] }}</span>
                        </label>

                        <label class="field field--wide">
                            <span class="field__label">{{ $page['form_message_label'] }}</span>
                            <textarea class="field__control" name="message" maxlength="2000"></textarea>
                        </label>

                        <div class="form-actions field--wide">
                            <button class="primary-cta" type="submit">{{ $page['form_submit'] }}</button>
                            <span class="field__hint">{{ $page['form_hint'] }}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <x-cta-band
        :title="$page['cta_title']"
        :description="$page['cta_description']"
        :primary-label="$page['cta_primary']"
        :secondary-label="$page['cta_secondary']"
        :secondary-url="site_link($page['cta_secondary_target'] ?? 'products')"
    />
@endsection
