@extends('layouts.public')

@section('title', 'Shërbimet Teknike | Back2Value')
@section('meta_description', 'Diagnostikim, rigjenerim, kontrata mirëmbajtjeje SLA dhe grumbullim baterish industriale në Shqipëri. Rezervoni një diagnostikim ose kërkoni kontratë servisi.')

@section('content')
    <x-page-hero :eyebrow="$page['eyebrow']" :title="$page['title']" :description="$page['description']">
        <a class="primary-cta" href="{{ route('diagnostics.create') }}">Rezervo Diagnostikim</a>
        <a class="secondary-cta" href="#kalkulator">Kalkulator kursimi</a>
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
                <a class="link-cta link-cta--green" href="{{ route('regeneration.index') }}">RID Tester &amp; RID Rigenerator <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <x-savings-calculator />

    <x-drop-off-points :section="$dropOffPoints" />

    <section class="page-section page-section--soft">
        <div class="section-shell">
            <div class="section-intro section-intro--small">
                <span class="eyebrow">KONTRAKTA MIRËMBAJTJEJE</span>
                <h2>Kërkoni një kontratë SLA për flotën tuaj</h2>
                <p>Plotësoni të dhënat e mëposhtme dhe ekipi i shitjeve përgatit propozimin teknik dhe komercial për institucionin tuaj.</p>
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
                            <span class="field__label">Emri i kompanisë</span>
                            <input class="field__control" type="text" name="company_name" maxlength="255" required>
                        </label>

                        <label class="field">
                            <span class="field__label">Personi i kontaktit</span>
                            <input class="field__control" type="text" name="contact_name" maxlength="255" required>
                        </label>

                        <label class="field">
                            <span class="field__label">Email</span>
                            <input class="field__control" type="email" name="email" maxlength="255" required>
                        </label>

                        <label class="field">
                            <span class="field__label">Telefon</span>
                            <input class="field__control" type="tel" name="phone" maxlength="50" required>
                        </label>

                        <label class="field">
                            <span class="field__label">Sektori</span>
                            <select class="field__control" name="sector" required>
                                <option value="telecom">Telekomunikacion</option>
                                <option value="data_center">Data Center</option>
                                <option value="banks">Banka &amp; Institucione Financiare</option>
                            </select>
                        </label>

                        <label class="field">
                            <span class="field__label">Numri i baterive në flotë</span>
                            <input class="field__control" type="number" name="fleet_size" min="1" step="1" value="50" required>
                        </label>

                        <label class="field field--wide">
                            <span class="field__label">Kërkesat teknike</span>
                            <textarea class="field__control" name="requirements" maxlength="2000" required></textarea>
                            <span class="field__hint">Përshkruani lokacionet, llojin e baterive dhe frekuencën e dëshiruar të inspektimeve.</span>
                        </label>

                        <label class="field field--wide">
                            <span class="field__label">Shënime shtesë (opsionale)</span>
                            <textarea class="field__control" name="message" maxlength="2000"></textarea>
                        </label>

                        <div class="form-actions field--wide">
                            <button class="primary-cta" type="submit">Dërgo kërkesën</button>
                            <span class="field__hint">Përgjigjemi brenda 24 orëve të punës.</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <x-cta-band
        title="Nuk jeni të sigurt nëse bateritë tuaja mund të rigjenerohen?"
        description="Dërgoni një kërkesë për diagnostikim dhe marrim vendimin teknik bazuar në matje reale të kapacitetit dhe rezistencës së brendshme."
        primary-label="Rezervo Diagnostikim"
        secondary-label="Shiko produktet"
        :secondary-url="route('products.index')"
    />
@endsection
