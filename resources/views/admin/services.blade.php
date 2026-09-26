@extends('admin.layout', ['title' => 'Shërbimet'])

@section('content')
    <section class="admin-page-intro">
        <div>
            <span class="admin-kicker">SHËRBIMET TEKNIKE</span>
            <h2>Menaxhoni shërbimet</h2>
            <p>Shtoni shërbime dhe përditësoni përshkrimet që shfaqen në faqen kryesore.</p>
        </div>
        <button class="admin-button admin-button--secondary" type="button" data-add-row="service-template" data-list="service-list">
            <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'plus'])</span> Shto shërbim
        </button>
    </section>

    <form method="POST" action="{{ route('admin.services.update') }}" class="admin-form admin-repeat-form">
        @csrf
        @method('PUT')
        <div id="service-list" data-repeat-list>
            @foreach (old('services', $services) as $index => $service)
                @php($serviceKey = $service['key'] ?? $index)
                <article class="admin-panel admin-repeat-card" data-repeat-card>
                    <div class="admin-repeat-card__heading">
                        <div>
                            <span class="admin-kicker">SHËRBIM</span>
                            <h3>{{ $service['title'] ?? 'Shërbim i ri' }}</h3>
                        </div>
                        <button class="admin-button admin-button--remove" type="button" data-remove-row title="Hiq shërbimin">Hiq shërbimin</button>
                    </div>
                    <input type="hidden" name="services[{{ $serviceKey }}][key]" value="{{ $serviceKey }}">
                    <div class="admin-form-grid">
                        <label class="admin-field">
                            <span>Emri i shërbimit</span>
                            <input type="text" name="services[{{ $serviceKey }}][title]" value="{{ $service['title'] ?? '' }}" maxlength="120" required>
                        </label>
                        <label class="admin-field">
                            <span>Renditja në faqe</span>
                            <input type="number" name="services[{{ $serviceKey }}][sort_order]" value="{{ $service['sort_order'] ?? $loop->iteration }}" min="0" max="1000" required>
                        </label>
                        <label class="admin-field admin-field--wide">
                            <span>Përshkrimi</span>
                            <textarea name="services[{{ $serviceKey }}][description]" rows="3" maxlength="1000" required>{{ $service['description'] ?? '' }}</textarea>
                        </label>
                        <label class="admin-field">
                            <span>Ikona</span>
                            <select name="services[{{ $serviceKey }}][icon]" required>
                                <option value="clipboard" @selected(($service['icon'] ?? '') === 'clipboard')>Diagnostikim</option>
                                <option value="refresh" @selected(($service['icon'] ?? '') === 'refresh')>Rigjenerim</option>
                                <option value="calendar" @selected(($service['icon'] ?? '') === 'calendar')>Mirëmbajtje</option>
                                <option value="download" @selected(($service['icon'] ?? '') === 'download')>Dorëzim</option>
                            </select>
                        </label>
                    </div>
                </article>
            @endforeach
        </div>

        <template id="service-template">
            <article class="admin-panel admin-repeat-card" data-repeat-card>
                <div class="admin-repeat-card__heading">
                    <div><span class="admin-kicker">SHËRBIM I RI</span><h3>Shërbim i ri</h3></div>
                    <button class="admin-button admin-button--remove" type="button" data-remove-row title="Hiq shërbimin">Hiq shërbimin</button>
                </div>
                <input type="hidden" name="services[__KEY__][key]" value="__KEY__">
                <div class="admin-form-grid">
                    <label class="admin-field">
                        <span>Emri i shërbimit</span>
                        <input type="text" name="services[__KEY__][title]" maxlength="120" required>
                    </label>
                    <label class="admin-field">
                        <span>Renditja në faqe</span>
                        <input type="number" name="services[__KEY__][sort_order]" value="99" min="0" max="1000" required>
                    </label>
                    <label class="admin-field admin-field--wide">
                        <span>Përshkrimi</span>
                        <textarea name="services[__KEY__][description]" rows="3" maxlength="1000" required></textarea>
                    </label>
                    <label class="admin-field">
                        <span>Ikona</span>
                        <select name="services[__KEY__][icon]" required>
                            <option value="clipboard">Diagnostikim</option>
                            <option value="refresh">Rigjenerim</option>
                            <option value="calendar">Mirëmbajtje</option>
                            <option value="download">Dorëzim</option>
                        </select>
                    </label>
                </div>
            </article>
        </template>

        <div class="admin-save-bar">
            <span>{{ count($services) }} shërbime të publikuara.</span>
            <button class="admin-button admin-button--primary" type="submit">Ruaj shërbimet <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'check'])</span></button>
        </div>
    </form>
@endsection
