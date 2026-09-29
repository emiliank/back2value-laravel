@extends('admin.layout', ['title' => 'Përmbledhja'])

@section('content')
    <section class="admin-welcome">
        <div>
            <span class="admin-kicker">CMS</span>
            <h2>Mirë se vini në panelin e përmbajtjes</h2>
            <p>
                Gjithçka që shfaqet në faqe — tekstet, butonat, imazhet, filtrat dhe listat —
                editohet këtu, pa prekur kod.
            </p>
        </div>
    </section>

    <div class="admin-metrics">
        <div class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--blue">@include('admin.partials.icon', ['name' => 'box'])</span>
            <div class="admin-metric__label">Faqe edituese</div>
            <strong>{{ $totalPages }}</strong>
            <a href="#seksionet">Shiko të gjitha</a>
        </div>
        <div class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--green">@include('admin.partials.icon', ['name' => 'settings'])</span>
            <div class="admin-metric__label">Seksione përmbajtje</div>
            <strong>{{ $totalSections }}</strong>
            <a href="#seksionet">Shiko të gjitha</a>
        </div>
        <div class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--amber">@include('admin.partials.icon', ['name' => 'image'])</span>
            <div class="admin-metric__label">Të personalizuara</div>
            <strong>{{ $totalCustomised }}</strong>
            <a href="#seksionet">Shiko të gjitha</a>
        </div>
        <div class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--violet">@include('admin.partials.icon', ['name' => 'external'])</span>
            <div class="admin-metric__label">Faqja publike</div>
            <strong>Live</strong>
            <a href="{{ route('home') }}" target="_blank" rel="noopener">Shfaq</a>
        </div>
    </div>

    <section class="admin-quick-panel" id="seksionet">
        <div class="admin-panel__heading">
            <div>
                <h2>Seksionet e përmbajtjes</h2>
                <p>Zgjidhni një seksion për ta redaktuar ose për ta kthyer te vlerat fillestare.</p>
            </div>
        </div>

        <div class="admin-quick-links">
            @foreach ($groups as $groupName => $groupPages)
                <span class="admin-subheading">{{ $groupName }}</span>

                @foreach ($groupPages as $slug => $page)
                    <a href="{{ route('admin.content.edit', ['page' => $slug]) }}">
                        <span class="admin-quick-link__arrow">@include('admin.partials.icon', ['name' => 'arrow'])</span>
                        <span class="admin-quick-link__body">
                            <strong>{{ $page['label'] }}</strong>
                            <small>{{ $page['description'] }}</small>
                        </span>
                    </a>
                @endforeach
            @endforeach
        </div>
    </section>

    <section class="admin-quick-panel">
        <div class="admin-panel__heading">
            <div>
                <h2>Çfarë mund të redaktohet</h2>
                <p>Secili seksion merr vlerat nga <code>config/site.php</code> derisa të ruajteni ndryshimet.</p>
            </div>
        </div>

        <div class="admin-section-table">
            @foreach ($sections as $sectionKey => $section)
                <div class="admin-section-table__row">
                    <div>
                        <strong>{{ $section['title'] }}</strong>
                        <small>{{ $section['description'] }}</small>
                    </div>
                    <div class="admin-section-table__meta">
                        <span class="admin-badge {{ ($customised[$sectionKey] ?? false) ? 'admin-badge--green' : '' }}">
                            {{ ($customised[$sectionKey] ?? false) ? 'E personalizuar' : 'Default' }}
                        </span>
                        <a class="admin-button admin-button--secondary" href="{{ route('admin.content.edit', ['page' => $pageOfSection[$sectionKey]]) }}">
                            Edito
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
