@extends('admin.layout', ['title' => 'Cilësimet & faqja'])

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">FAQJA KRYESORE</span>
                    <h2>Prezantimi dhe ofertat</h2>
                    <p>Tekstet kryesore që shfaqen në hyrje të faqes.</p>
                </div>
            </div>
            <div class="admin-form-grid">
                <label class="admin-field admin-field--wide">
                    <span>Titulli i vogël mbi kryetitull</span>
                    <input type="text" name="hero_badge" value="{{ old('hero_badge', $settings['hero_badge']) }}" maxlength="120" required>
                </label>
                <label class="admin-field">
                    <span>Rreshti i parë i titullit</span>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title']) }}" maxlength="100" required>
                </label>
                <label class="admin-field">
                    <span>Rreshti i theksuar me ngjyrë</span>
                    <input type="text" name="hero_highlight" value="{{ old('hero_highlight', $settings['hero_highlight']) }}" maxlength="100" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Rreshti i tretë</span>
                    <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $settings['hero_subtitle']) }}" maxlength="100" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Përshkrimi kryesor</span>
                    <textarea name="hero_description" rows="3" maxlength="500" required>{{ old('hero_description', $settings['hero_description']) }}</textarea>
                </label>
                <label class="admin-field">
                    <span>Butoni i WhatsApp</span>
                    <input type="text" name="hero_cta" value="{{ old('hero_cta', $settings['hero_cta']) }}" maxlength="100" required>
                </label>
                <label class="admin-field">
                    <span>Butoni i produkteve</span>
                    <input type="text" name="hero_products_cta" value="{{ old('hero_products_cta', $settings['hero_products_cta']) }}" maxlength="100" required>
                </label>
                <label class="admin-field">
                    <span>Titulli i njoftimit</span>
                    <input type="text" name="promo_title" value="{{ old('promo_title', $settings['promo_title']) }}" maxlength="150" required>
                </label>
                <label class="admin-field">
                    <span>Lidhja e njoftimit</span>
                    <input type="text" name="promo_link" value="{{ old('promo_link', $settings['promo_link']) }}" maxlength="150" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Teksti i njoftimit</span>
                    <textarea name="promo_description" rows="2" maxlength="250" required>{{ old('promo_description', $settings['promo_description']) }}</textarea>
                </label>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">SEKSIONET</span>
                    <h2>Titujt dhe përshkrimet</h2>
                </div>
            </div>
            <div class="admin-form-grid">
                <label class="admin-field">
                    <span>Etiketa e produkteve</span>
                    <input type="text" name="products_eyebrow" value="{{ old('products_eyebrow', $settings['products_eyebrow']) }}" maxlength="80" required>
                </label>
                <label class="admin-field">
                    <span>Titulli i produkteve</span>
                    <input type="text" name="products_title" value="{{ old('products_title', $settings['products_title']) }}" maxlength="120" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Përshkrimi i produkteve</span>
                    <textarea name="products_description" rows="2" maxlength="350" required>{{ old('products_description', $settings['products_description']) }}</textarea>
                </label>
                <label class="admin-field">
                    <span>Etiketa e shërbimeve</span>
                    <input type="text" name="services_eyebrow" value="{{ old('services_eyebrow', $settings['services_eyebrow']) }}" maxlength="80" required>
                </label>
                <label class="admin-field">
                    <span>Titulli i shërbimeve</span>
                    <input type="text" name="services_title" value="{{ old('services_title', $settings['services_title']) }}" maxlength="120" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Përshkrimi i shërbimeve</span>
                    <textarea name="services_description" rows="2" maxlength="350" required>{{ old('services_description', $settings['services_description']) }}</textarea>
                </label>
                <label class="admin-field">
                    <span>Etiketa e kontaktit</span>
                    <input type="text" name="contact_eyebrow" value="{{ old('contact_eyebrow', $settings['contact_eyebrow']) }}" maxlength="80" required>
                </label>
                <label class="admin-field">
                    <span>Titulli i kontaktit</span>
                    <input type="text" name="contact_title" value="{{ old('contact_title', $settings['contact_title']) }}" maxlength="120" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Përshkrimi i kontaktit</span>
                    <textarea name="contact_description" rows="2" maxlength="350" required>{{ old('contact_description', $settings['contact_description']) }}</textarea>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Përshkrimi në fund të faqes</span>
                    <textarea name="footer_description" rows="2" maxlength="500" required>{{ old('footer_description', $settings['footer_description']) }}</textarea>
                </label>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">TË DHËNAT E BIZNESIT</span>
                    <h2>Kontaktet, adresa dhe ngjyra</h2>
                    <p>Ndryshimet përditësojnë automatikisht kartat e kontaktit dhe hartën në faqe.</p>
                </div>
            </div>
            <div class="admin-form-grid">
                <label class="admin-field">
                    <span>Numri i telefonit</span>
                    <input type="tel" name="phone" value="{{ old('phone', $settings['phone']) }}" maxlength="30" required>
                </label>
                <label class="admin-field">
                    <span>Numri i WhatsApp (me kodin e shtetit)</span>
                    <input type="tel" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp']) }}" maxlength="20" required>
                </label>
                <label class="admin-field">
                    <span>Email</span>
                    <input type="email" name="email" value="{{ old('email', $settings['email']) }}" maxlength="255" required>
                </label>
                <label class="admin-field">
                    <span>Orari i punës</span>
                    <input type="text" name="hours" value="{{ old('hours', $settings['hours']) }}" maxlength="120" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Adresa</span>
                    <input type="text" name="address" value="{{ old('address', $settings['address']) }}" maxlength="180" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Vendndodhja e kërkimit në Google Maps</span>
                    <input type="text" name="map_query" value="{{ old('map_query', $settings['map_query']) }}" maxlength="180" required>
                </label>
                <label class="admin-field">
                    <span>Ngjyra kryesore</span>
                    <input class="admin-color-input" type="color" name="accent_color" value="{{ old('accent_color', $settings['accent_color']) }}" required>
                    <small>Ngjyra e veprimeve, ikonave dhe theksimeve.</small>
                </label>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">GOOGLE DHE SEO</span>
                    <h2>Titulli dhe përshkrimi në kërkim</h2>
                </div>
            </div>
            <div class="admin-form-grid">
                <label class="admin-field admin-field--wide">
                    <span>Titulli i faqes</span>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $settings['meta_title']) }}" maxlength="120" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Përshkrimi i faqes</span>
                    <textarea name="meta_description" rows="3" maxlength="300" required>{{ old('meta_description', $settings['meta_description']) }}</textarea>
                </label>
            </div>
        </section>

        <div class="admin-save-bar">
            <span>Ndryshimet ruhen dhe shfaqen menjëherë në faqen kryesore.</span>
            <button class="admin-button admin-button--primary" type="submit">Ruaj cilësimet <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'check'])</span></button>
        </div>
    </form>
@endsection
