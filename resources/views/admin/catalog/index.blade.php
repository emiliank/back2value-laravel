@extends('admin.layout', ['title' => 'Katalogu i baterive'])

@section('content')
    <section class="admin-page-intro">
        <div>
            <span class="admin-kicker">DATABAZA</span>
            <h2>Katalogu i baterive</h2>
            <p>
                Këto janë bateritë që shfaqen në faqja publike <a href="{{ route('products.index') }}" target="_blank" rel="noopener">/products</a>.
                Shtoni, ndryshoni ose hiqni produkte këtu.
            </p>
        </div>
        <div class="admin-inline-form">
            <a href="{{ route('admin.catalog.create') }}" class="admin-button admin-button--primary">
                <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'plus'])</span>
                Shto bateri
            </a>
        </div>
    </section>

    <div class="admin-metrics">
        <div class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--blue">@include('admin.partials.icon', ['name' => 'box'])</span>
            <div class="admin-metric__label">Bateri totale</div>
            <strong>{{ $total }}</strong>
        </div>
        <div class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--green">@include('admin.partials.icon', ['name' => 'check'])</span>
            <div class="admin-metric__label">Të shfaqura në faqe</div>
            <strong>{{ $availableCount }}</strong>
        </div>
        <div class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--amber">@include('admin.partials.icon', ['name' => 'clock'])</span>
            <div class="admin-metric__label">Me porosi paraprake</div>
            <strong>{{ $preorderCount }}</strong>
        </div>
    </div>

    <section class="admin-panel">
        <div class="admin-panel__heading">
            <div>
                <span class="admin-kicker">KËRKO</span>
                <h2>Filtrimi i katalogut</h2>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.catalog.index') }}" class="admin-form-grid">
            <label class="admin-field">
                <span>Kërko</span>
                <input type="text" name="q" value="{{ $filters['q'] }}" placeholder="Model, markë, seri ose kategori…">
            </label>

            <label class="admin-field">
                <span>Kategoria</span>
                <select name="category">
                    <option value="">Të gjitha</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" @selected((string) $filters['category'] === (string) $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </label>

            <label class="admin-field">
                <span>Marka</span>
                <select name="brand">
                    <option value="">Të gjitha</option>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand }}" @selected((string) $filters['brand'] === (string) $brand)>{{ $brand }}</option>
                    @endforeach
                </select>
            </label>

            <label class="admin-field">
                <span>Aplikimi</span>
                <select name="application_type">
                    <option value="">Të gjitha</option>
                    @foreach ($applicationTypes as $type)
                        <option value="{{ $type }}" @selected((string) $filters['application_type'] === (string) $type)>{{ $applicationLabels[$type] ?? $type }}</option>
                    @endforeach
                </select>
            </label>

            <label class="admin-field">
                <span>Gjendja</span>
                <select name="status">
                    <option value="">Të gjitha</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" @selected((string) $filters['status'] === (string) $status)>{{ $statusLabels[$status] ?? $status }}</option>
                    @endforeach
                </select>
            </label>

            <label class="admin-field">
                <span>Stok</span>
                <select name="stock_status">
                    <option value="">Të gjitha</option>
                    @foreach ($stockStatuses as $value => $label)
                        <option value="{{ $value }}" @selected((string) $filters['stock_status'] === (string) $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>

            <label class="admin-field">
                <span>Shfaqja</span>
                <select name="available">
                    <option value="">Të gjitha</option>
                    <option value="1" @selected((string) $filters['available'] === '1')>Vetëm të shfaqura</option>
                    <option value="0" @selected((string) $filters['available'] === '0')>Vetëm të fshehura</option>
                </select>
            </label>

            <div class="admin-fieldset" style="grid-column: 1 / -1; display: flex; gap: 10px; align-items: end;">
                <button type="submit" class="admin-button admin-button--primary">Filtro</button>
                <a href="{{ route('admin.catalog.index') }}" class="admin-button admin-button--secondary">Pastro</a>
            </div>
        </form>
    </section>

    <section class="admin-panel">
        <div class="admin-panel__heading">
            <div>
                <span class="admin-kicker">REZULTATE</span>
                <h2>{{ $batteries->total() }} bateri</h2>
            </div>
        </div>

        @if ($batteries->isEmpty())
            <p class="admin-repeater__empty">Nuk u gjet asnjë bateri me këto kritere.</p>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.86rem;">
                    <thead>
                        <tr style="text-align: left; color: #6b7686; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.04em;">
                            <th style="padding: 8px 10px;">Modeli</th>
                            <th style="padding: 8px 10px;">Marka</th>
                            <th style="padding: 8px 10px;">Kategoria</th>
                            <th style="padding: 8px 10px;">Ah</th>
                            <th style="padding: 8px 10px;">Aplikimi</th>
                            <th style="padding: 8px 10px;">Gjendja</th>
                            <th style="padding: 8px 10px;">Stok</th>
                            <th style="padding: 8px 10px;">Shfaqja</th>
                            <th style="padding: 8px 10px; text-align: right;">Veprimi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($batteries as $battery)
                            <tr style="border-top: 1px solid #e6e9ef;">
                                <td style="padding: 10px;">
                                    <strong>{{ $battery->model ?: '—' }}</strong>
                                    @if ($battery->serial_number)
                                        <br><small style="color: #6b7686;">{{ $battery->serial_number }}</small>
                                    @endif
                                </td>
                                <td style="padding: 10px;">{{ $battery->brand }}</td>
                                <td style="padding: 10px;">{{ $battery->category ?: '—' }}</td>
                                <td style="padding: 10px;">{{ $battery->capacity_ah }}</td>
                                <td style="padding: 10px;">{{ $applicationLabels[$battery->application_type] ?? $battery->application_type ?: '—' }}</td>
                                <td style="padding: 10px;">
                                    <span class="admin-badge @if ($battery->status === 'new') admin-badge--green @endif">{{ $statusLabels[$battery->status] ?? $battery->status }}</span>
                                </td>
                                <td style="padding: 10px;">
                                    @php
                                        $stockTone = match ($battery->stock_status) {
                                            'in_stock' => 'admin-badge--green',
                                            'low_stock' => 'admin-badge--amber',
                                            default => 'admin-badge',
                                        };
                                    @endphp
                                    <span class="admin-badge {{ $stockTone }}">{{ $stockStatuses[$battery->stock_status] ?? $battery->stock_status }}</span>
                                </td>
                                <td style="padding: 10px;">
                                    @if ($battery->is_available)
                                        <span class="admin-badge admin-badge--green">Po</span>
                                    @else
                                        <span class="admin-badge">Jo</span>
                                    @endif
                                </td>
                                <td style="padding: 10px; text-align: right; white-space: nowrap;">
                                    <a href="{{ route('admin.catalog.edit', ['battery' => $battery]) }}" class="admin-button admin-button--secondary">Ndrysho</a>

                                    <form method="POST" action="{{ route('admin.catalog.destroy', ['battery' => $battery]) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-button admin-button--remove" data-confirm='Fshihet &quot;{{ $battery->model }}&quot;? Nuk mund të kthehet.'>Hiq</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-save-bar">
                <span>{{ $batteries->firstItem() }}–{{ $batteries->lastItem() }} nga {{ $batteries->total() }}</span>
                <div>{{ $batteries->links() }}</div>
            </div>
        @endif
    </section>
@endsection
