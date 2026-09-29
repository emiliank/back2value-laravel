@extends('layouts.public')

@section('title', $page['meta_title'])
@section('meta_description', $page['meta_description'])

@section('content')
    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']" />

    <section class="page-section">
        <div class="section-shell">
            <div class="form-shell">
                <div class="form-card">
                    @if (session('status'))
                        <div class="status-alert" role="status">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 3 4.5 6v5.2c0 4.6 3.2 8.3 7.5 9.8 4.3-1.5 7.5-5.2 7.5-9.8V6L12 3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if (isset($errors) && $errors->any())
                        <div class="error-summary" role="alert">
                            <strong>{{ $page['error_summary'] }}</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('diagnostics.bookings.store') }}" class="form-grid">
                        @csrf

                        <label class="field">
                            <span class="field__label">{{ $page['label_name'] }}</span>
                            <input class="field__control" type="text" name="customer_name" value="{{ old('customer_name') }}" maxlength="255" required>
                            @error('customer_name')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['label_company'] }}</span>
                            <input class="field__control" type="text" name="company_name" value="{{ old('company_name') }}" maxlength="255">
                            @error('company_name')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['label_email'] }}</span>
                            <input class="field__control" type="email" name="email" value="{{ old('email') }}" maxlength="255" required>
                            @error('email')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>
                        <label class="field">
                            <span class="field__label">{{ $page['label_phone'] }}</span>
                            <input class="field__control" type="tel" name="phone" value="{{ old('phone') }}" maxlength="50" required>
                            @error('phone')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>



                        <label class="field">
                            <span class="field__label">{{ $page['label_sector'] }}</span>
                            <select class="field__control" name="sector" required>
                                <option value="">{{ $page['placeholder_select'] }}</option>
                                @foreach ($page['sectors'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('sector') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('sector')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['label_battery_type'] }}</span>
                            <select class="field__control" name="battery_type" required>
                                <option value="">{{ $page['placeholder_select'] }}</option>
                                @foreach ($page['battery_types'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('battery_type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('battery_type')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['label_date'] }}</span>
                            <input class="field__control" type="date" name="preferred_date" value="{{ old('preferred_date') }}" min="{{ now()->toDateString() }}" required>
                            @error('preferred_date')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="field">
                            <span class="field__label">{{ $page['label_service_preference'] }}</span>
                            <select class="field__control" name="service_preference" required>
                                <option value="">{{ $page['placeholder_select'] }}</option>
                                @foreach ($page['service_preferences'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('service_preference') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('service_preference')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="field field--wide">
                            <span class="field__label">{{ $page['label_notes'] }}</span>
                            <textarea class="field__control" name="notes" maxlength="1500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="field__error">{{ $message }}</span>
                            @enderror
                        </label>

                        <div class="form-actions field--wide">
                            <button class="primary-cta" type="submit">{{ $page['submit_label'] }}</button>
                            <span class="field__hint">{{ $page['submit_hint'] }}</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <x-drop-off-points :section="$dropOffPoints" />

    <x-cta-band
        :title="$page['cta_title']"
        :description="$page['cta_description']"
        :primary-label="$page['cta_primary']"
        :primary-url="site_link($page['cta_primary_target'] ?? 'services')"
        :secondary-label="$page['cta_secondary']"
        :secondary-url="site_link($page['cta_secondary_target'] ?? 'resources')"
    />
@endsection
