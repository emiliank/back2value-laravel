@extends('admin.layout', ['title' => 'Produktet & imazhet'])

@section('content')
    <section class="admin-page-intro">
        <div>
            <span class="admin-kicker">KATALOGU NË FAQEN KRYESORE</span>
            <h2>Produktet dhe imazhet</h2>
            <p>Ndryshoni tekstin, veçoritë dhe renditjen. Ngarkoni JPG, PNG ose WebP deri në 5 MB.</p>
        </div>
        <button class="admin-button admin-button--secondary" type="button" data-add-row="product-template" data-list="product-list">
            <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'plus'])</span> Shto produkt
        </button>
    </section>

    <form method="POST" action="{{ route('admin.products.update') }}" enctype="multipart/form-data" class="admin-form admin-repeat-form">
        @csrf
        @method('PUT')
        <div id="product-list" data-repeat-list>
            @foreach (old('products', $products) as $index => $product)
                @php
                    $productKey = $product['key'] ?? $index;
                    $productImage = filled($product['image'] ?? null)
                        ? (str_starts_with($product['image'], 'images/')
                            ? asset($product['image'])
                            : \Illuminate\Support\Facades\Storage::disk('public')->url($product['image']))
                        : null;
                @endphp
                <article class="admin-panel admin-repeat-card" data-repeat-card>
                    <div class="admin-repeat-card__heading">
                        <div>
                            <span class="admin-kicker">PRODUKT</span>
                            <h3>{{ $product['title'] ?? 'Produkt i ri' }}</h3>
                        </div>
                        <button class="admin-button admin-button--remove" type="button" data-remove-row title="Hiq produktin">
                            Hiq produktin
                        </button>
                    </div>
                    <input type="hidden" name="products[{{ $productKey }}][key]" value="{{ $productKey }}">
                    <div class="admin-product-edit">
                        @if ($productImage)
                            <div class="admin-product-preview">
                                <img src="{{ $productImage }}" alt="{{ $product['image_alt'] ?? '' }}" loading="lazy">
                            </div>
                        @endif
                        <div class="admin-form-grid">
                            <label class="admin-field">
                                <span>Emri i produktit</span>
                                <input type="text" name="products[{{ $productKey }}][title]" value="{{ $product['title'] ?? '' }}" maxlength="120" required>
                            </label>
                            <label class="admin-field">
                                <span>Renditja në faqe</span>
                                <input type="number" name="products[{{ $productKey }}][sort_order]" value="{{ $product['sort_order'] ?? $loop->iteration }}" min="0" max="1000" required>
                            </label>
                            <label class="admin-field admin-field--wide">
                                <span>Përshkrimi</span>
                                <textarea name="products[{{ $productKey }}][description]" rows="3" maxlength="1000" required>{{ $product['description'] ?? '' }}</textarea>
                            </label>
                            <label class="admin-field admin-field--wide">
                                <span>Veçoritë (një për rresht)</span>
                                <textarea name="products[{{ $productKey }}][features]" rows="3" maxlength="1500">{{ implode("\n", $product['features'] ?? []) }}</textarea>
                            </label>
                            <label class="admin-field admin-field--wide">
                                <span>Përshkrimi alternativ i imazhit</span>
                                <input type="text" name="products[{{ $productKey }}][image_alt]" value="{{ $product['image_alt'] ?? '' }}" maxlength="180" required>
                            </label>
                            <label class="admin-field admin-field--wide">
                                <span>{{ $productImage ? 'Zëvendëso imazhin' : 'Ngarko imazhin' }}</span>
                                <input type="file" name="products[{{ $productKey }}][image]" accept="image/jpeg,image/png,image/webp">
                                <small>Imazhi aktual mbetet nëse nuk zgjidhni një fotografi të re.</small>
                            </label>
                            @if ($productImage)
                                <label class="admin-field admin-field--checkbox admin-field--wide">
                                    <input type="checkbox" name="products[{{ $productKey }}][remove_image]" value="1">
                                    <span>Hiq imazhin aktual</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <template id="product-template">
            <article class="admin-panel admin-repeat-card" data-repeat-card>
                <div class="admin-repeat-card__heading">
                    <div>
                        <span class="admin-kicker">PRODUKT I RI</span>
                        <h3>Produkt i ri</h3>
                    </div>
                    <button class="admin-button admin-button--remove" type="button" data-remove-row title="Hiq produktin">Hiq produktin</button>
                </div>
                <input type="hidden" name="products[__KEY__][key]" value="__KEY__">
                <div class="admin-form-grid">
                    <label class="admin-field">
                        <span>Emri i produktit</span>
                        <input type="text" name="products[__KEY__][title]" maxlength="120" required>
                    </label>
                    <label class="admin-field">
                        <span>Renditja në faqe</span>
                        <input type="number" name="products[__KEY__][sort_order]" value="99" min="0" max="1000" required>
                    </label>
                    <label class="admin-field admin-field--wide">
                        <span>Përshkrimi</span>
                        <textarea name="products[__KEY__][description]" rows="3" maxlength="1000" required></textarea>
                    </label>
                    <label class="admin-field admin-field--wide">
                        <span>Veçoritë (një për rresht)</span>
                        <textarea name="products[__KEY__][features]" rows="3" maxlength="1500"></textarea>
                    </label>
                    <label class="admin-field admin-field--wide">
                        <span>Përshkrimi alternativ i imazhit</span>
                        <input type="text" name="products[__KEY__][image_alt]" maxlength="180" required>
                    </label>
                    <label class="admin-field admin-field--wide">
                        <span>Ngarko imazhin</span>
                        <input type="file" name="products[__KEY__][image]" accept="image/jpeg,image/png,image/webp">
                    </label>
                </div>
            </article>
        </template>

        <div class="admin-save-bar">
            <span>{{ count($products) }} produkte në katalog.</span>
            <button class="admin-button admin-button--primary" type="submit">Ruaj produktet <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'check'])</span></button>
        </div>
    </form>
@endsection
