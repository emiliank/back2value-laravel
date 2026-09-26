@extends('admin.layout', ['title' => 'Për Back2Value'])

@section('content')
    <form method="POST" action="{{ route('admin.about.update') }}" class="admin-form">
        @csrf
        @method('PUT')

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">AVANTAZHI YNË</span>
                    <h2>Seksioni “Pse Back2Value?”</h2>
                </div>
            </div>
            <div class="admin-form-grid">
                <label class="admin-field">
                    <span>Etiketa e seksionit</span>
                    <input type="text" name="about[eyebrow]" value="{{ old('about.eyebrow', $about['eyebrow']) }}" maxlength="80" required>
                </label>
                <label class="admin-field">
                    <span>Titulli</span>
                    <input type="text" name="about[title]" value="{{ old('about.title', $about['title']) }}" maxlength="120" required>
                </label>
                <label class="admin-field admin-field--wide">
                    <span>Përshkrimi i seksionit</span>
                    <textarea name="about[description]" rows="3" maxlength="350" required>{{ old('about.description', $about['description']) }}</textarea>
                </label>
            </div>

            <div class="admin-subheading"><h3>Pikat e forta</h3><p>Dy kartat që shpjegojnë përparësitë e kompanisë.</p></div>
            <div class="admin-form-grid">
                @foreach (old('about.points', $about['points']) as $index => $point)
                    @php($pointKey = $point['key'] ?? 'point-'.$loop->index)
                    <div class="admin-fieldset admin-field--wide">
                        <input type="hidden" name="about[points][{{ $index }}][key]" value="{{ $pointKey }}">
                        <strong>{{ $loop->iteration }}. {{ $point['title'] ?? 'Pikë e fortë' }}</strong>
                        <label class="admin-field">
                            <span>Titulli</span>
                            <input type="text" name="about[points][{{ $index }}][title]" value="{{ $point['title'] ?? '' }}" maxlength="120" required>
                        </label>
                        <label class="admin-field">
                            <span>Përshkrimi</span>
                            <textarea name="about[points][{{ $index }}][description]" rows="4" maxlength="800" required>{{ $point['description'] ?? '' }}</textarea>
                        </label>
                        <label class="admin-field">
                            <span>Emri poshtë kartës (opsional)</span>
                            <input type="text" name="about[points][{{ $index }}][label]" value="{{ $point['label'] ?? '' }}" maxlength="120">
                        </label>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel__heading">
                <div>
                    <span class="admin-kicker">SHIRIT I BESIMIT</span>
                    <h2>Katër përfitimet kryesore</h2>
                </div>
            </div>
            <div class="admin-form-grid">
                <label class="admin-field">
                    <span>Importues ekskluziv</span>
                    <input type="text" name="trust[title]" value="{{ old('trust.title', $trust['title']) }}" maxlength="100" required>
                </label>
                <label class="admin-field">
                    <span>Prodhimi</span>
                    <input type="text" name="trust[subtitle]" value="{{ old('trust.subtitle', $trust['subtitle']) }}" maxlength="100" required>
                </label>
                <label class="admin-field">
                    <span>Garancia</span>
                    <input type="text" name="trust[guarantee]" value="{{ old('trust.guarantee', $trust['guarantee']) }}" maxlength="100" required>
                </label>
                <label class="admin-field">
                    <span>Shërbimi vendor</span>
                    <input type="text" name="trust[service]" value="{{ old('trust.service', $trust['service']) }}" maxlength="100" required>
                </label>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel__heading admin-panel__heading--split">
                <div>
                    <span class="admin-kicker">SHIFRAT NË FAQE</span>
                    <h2>Statistikat dhe garancitë</h2>
                </div>
                <button class="admin-button admin-button--secondary" type="button" data-add-row="stat-template" data-list="stat-list">
                    <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'plus'])</span> Shto statistikë
                </button>
            </div>
            <div id="stat-list" class="admin-stats-edit" data-repeat-list>
                @foreach (old('stats', $stats) as $index => $stat)
                    @php($statKey = $stat['key'] ?? $index)
                    <article class="admin-stat-edit" data-repeat-card>
                        <div class="admin-repeat-card__heading">
                            <strong>{{ $stat['label'] ?? 'Statistikë' }}</strong>
                            <button class="admin-button admin-button--remove" type="button" data-remove-row title="Hiq statistikën">Hiq</button>
                        </div>
                        <input type="hidden" name="stats[{{ $statKey }}][key]" value="{{ $statKey }}">
                        <label class="admin-field">
                            <span>Vlera</span>
                            <input type="text" name="stats[{{ $statKey }}][value]" value="{{ $stat['value'] ?? '' }}" maxlength="30" required>
                        </label>
                        <label class="admin-field">
                            <span>Etiketa</span>
                            <input type="text" name="stats[{{ $statKey }}][label]" value="{{ $stat['label'] ?? '' }}" maxlength="80" required>
                        </label>
                        <label class="admin-field">
                            <span>Renditja</span>
                            <input type="number" name="stats[{{ $statKey }}][sort_order]" value="{{ $stat['sort_order'] ?? $loop->iteration }}" min="0" max="1000" required>
                        </label>
                    </article>
                @endforeach
            </div>
        </section>

        <template id="stat-template">
            <article class="admin-stat-edit" data-repeat-card>
                <div class="admin-repeat-card__heading">
                    <strong>Statistikë e re</strong>
                    <button class="admin-button admin-button--remove" type="button" data-remove-row title="Hiq statistikën">Hiq</button>
                </div>
                <input type="hidden" name="stats[__KEY__][key]" value="__KEY__">
                <label class="admin-field">
                    <span>Vlera</span>
                    <input type="text" name="stats[__KEY__][value]" maxlength="30" required>
                </label>
                <label class="admin-field">
                    <span>Etiketa</span>
                    <input type="text" name="stats[__KEY__][label]" maxlength="80" required>
                </label>
                <label class="admin-field">
                    <span>Renditja</span>
                    <input type="number" name="stats[__KEY__][sort_order]" value="99" min="0" max="1000" required>
                </label>
            </article>
        </template>

        <div class="admin-save-bar">
            <span>Përfitimet shfaqen në faqen kryesore bashkë me statistikat.</span>
            <button class="admin-button admin-button--primary" type="submit">Ruaj ndryshimet <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'check'])</span></button>
        </div>
    </form>
@endsection
