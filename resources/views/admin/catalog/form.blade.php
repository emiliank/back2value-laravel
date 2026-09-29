@extends('admin.layout', ['title' => $title])

@section('content')
    <section class="admin-page-intro">
        <div>
            <span class="admin-kicker">KATALOGU</span>
            <h2>{{ $title }}</h2>
            <p>Ndryshimet shfaqen menjëherë në faqjen publike <a href="{{ route('products.index') }}" target="_blank" rel="noopener">/products</a>.</p>
        </div>
        <div class="admin-inline-form">
            <a href="{{ route('admin.catalog.index') }}" class="admin-button admin-button--secondary">Kthehu te lista</a>
        </div>
    </section>

    <form method="POST" action="{{ $action }}" class="admin-form-grid">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">TË DHËNAT</span>
                    <h2>Identifikimi</h2>
                </div>
            </div>

            <div class="admin-form-grid">
                <label class="admin-field">
                    <span>Marka <em>*</em></span>
                    <input type="text" name="brand" list="brand-options" value="{{ old('brand', $battery->brand) }}" required>
                    <datalist id="brand-options">
                        @foreach ($brands as $brand)
                            <option value="{{ $brand }}"></option>
                        @endforeach
                    </datalist>
                    @error('brand') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Modeli <em>*</em></span>
                    <input type="text" name="model" value="{{ old('model', $battery->model) }}" required>
                    @error('model') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Numri i serës</span>
                    <input type="text" name="serial_number" value="{{ old('serial_number', $battery->serial_number) }}">
                    @error('serial_number') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Kategoria</span>
                    <input type="text" name="category" list="category-options" value="{{ old('category', $battery->category) }}">
                    <datalist id="category-options">
                        @foreach ($categories as $category)
                            <option value="{{ $category }}"></option>
                        @endforeach
                    </datalist>
                    <small>Përdoret për grupimin në faqen publike.</small>
                    @error('category') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Teknologjia</span>
                    <input type="text" name="technology" list="technology-options" value="{{ old('technology', $battery->technology) }}">
                    <datalist id="technology-options">
                        @foreach ($technologies as $technology)
                            <option value="{{ $technology }}"></option>
                        @endforeach
                    </datalist>
                    @error('technology') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Aplikimi</span>
                    <select name="application_type">
                        <option value="">Pa caktuar</option>
                        @foreach ($applicationTypes as $type)
                            <option value="{{ $type }}" @selected(old('application_type', $battery->application_type) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('application_type') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Kapaciteti (Ah) <em>*</em></span>
                    <input type="number" name="capacity_ah" min="1" value="{{ old('capacity_ah', $battery->capacity_ah) }}" required>
                    @error('capacity_ah') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Voltazhi</span>
                    <input type="text" name="voltage" value="{{ old('voltage', $battery->voltage) }}">
                    @error('voltage') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Gjendja e stokut</span>
                    <select name="stock_status" required>
                        @foreach ($stockStatuses as $value => $label)
                            <option value="{{ $value }}" @selected(old('stock_status', $battery->stock_status) === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    <span class="admin-field-hint">Produktet jashtë stokut apo me porosi paraprake mbeten të dukshme në faqe me etiketën përkatëse.</span>
                    @error('stock_status') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field admin-field--checkbox">
                    <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $battery->is_available))>
                    <span>Shfaq në faqen publike</span>
                </label>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">SPECIFIKAT</span>
                    <h2>Detajet teknike</h2>
                    <p>Shfaqen si tabela në kartën e produktit.</p>
                </div>
            </div>

            <div class="admin-form-grid">
                @foreach (\App\Http\Requests\BatteryRequest::SPEC_KEYS as $specKey)
                    <label class="admin-field">
                        <span>{{ \Illuminate\Support\Str::headline($specKey) }}</span>
                        <input type="text" name="specs[{{ $specKey }}]" value="{{ old('specs.'.$specKey, data_get($battery->specs, $specKey)) }}">
                        @error('specs.'.$specKey) <span class="admin-field-error">{{ $message }}</span> @enderror
                    </label>
                @endforeach

                <label class="admin-field admin-field--wide">
                    <span>Përshkrimi</span>
                    <textarea name="description" rows="4">{{ old('description', $battery->description) }}</textarea>
                    @error('description') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">CMIMI</span>
                    <h2>Shitja dhe garancia</h2>
                </div>
            </div>

            <div class="admin-form-grid">
                <label class="admin-field">
                    <span>Gjendja <em>*</em></span>
                    <select name="status" required>
                        @foreach ($statusLabels as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $battery->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Çmimi i blirjes</span>
                    <input type="number" name="purchase_price" step="0.01" min="0" value="{{ old('purchase_price', $battery->purchase_price) }}">
                    @error('purchase_price') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Çmimi i shitjes</span>
                    <input type="number" name="sale_price" step="0.01" min="0" value="{{ old('sale_price', $battery->sale_price) }}">
                    @error('sale_price') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>

                <label class="admin-field">
                    <span>Garancia (muaj)</span>
                    <input type="number" name="warranty_months" min="0" value="{{ old('warranty_months', $battery->warranty_months) }}">
                    @error('warranty_months') <span class="admin-field-error">{{ $message }}</span> @enderror
                </label>
            </div>
        </section>

        <div class="admin-save-bar">
            <span>{{ $method === 'POST' ? 'Ruajtja do të shtojë baterinë në katalog.' : 'Ruaj ndryshimet për këtë bateri.' }}</span>
            <div>
                <a href="{{ route('admin.catalog.index') }}" class="admin-button admin-button--secondary">Anulo</a>
                <button type="submit" class="admin-button admin-button--primary">Ruaj baterinë</button>
            </div>
        </div>
    </form>
@endsection
