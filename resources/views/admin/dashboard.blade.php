@extends('admin.layout', ['title' => 'Përmbledhje'])

@section('content')
    <section class="admin-welcome">
        <div>
            <span class="admin-kicker">PANELI I ADMINISTRIMIT</span>
            <h2>Mirë se vini, {{ auth()->user()->name }}</h2>
            <p>Menaxhoni përmbajtjen dhe pamjen e faqes Back2Value nga një vend i vetëm.</p>
        </div>
        <a class="admin-button admin-button--primary" href="{{ route('home') }}" target="_blank" rel="noreferrer">
            Hap faqen live <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'external'])</span>
        </a>
    </section>

    <section class="admin-metrics" aria-label="Përmbajtja e faqes">
        <article class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--green">@include('admin.partials.icon', ['name' => 'box'])</span>
            <span class="admin-metric__label">Produkte</span>
            <strong>{{ $counts['products'] }}</strong>
            <a href="{{ route('admin.products') }}">Menaxho produktet <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'arrow'])</span></a>
        </article>
        <article class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--blue">@include('admin.partials.icon', ['name' => 'services'])</span>
            <span class="admin-metric__label">Shërbime</span>
            <strong>{{ $counts['services'] }}</strong>
            <a href="{{ route('admin.services') }}">Menaxho shërbimet <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'arrow'])</span></a>
        </article>
        <article class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--amber">@include('admin.partials.icon', ['name' => 'chart'])</span>
            <span class="admin-metric__label">Statistika</span>
            <strong>{{ $counts['stats'] }}</strong>
            <a href="{{ route('admin.about') }}">Ndrysho statistikat <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'arrow'])</span></a>
        </article>
        <article class="admin-metric">
            <span class="admin-metric__icon admin-metric__icon--violet">@include('admin.partials.icon', ['name' => 'image'])</span>
            <span class="admin-metric__label">Imazhe produktesh</span>
            <strong>{{ $counts['images'] }}</strong>
            <a href="{{ route('admin.products') }}">Përditëso imazhet <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'arrow'])</span></a>
        </article>
    </section>

    <section class="admin-panel admin-quick-panel">
        <div class="admin-panel__heading">
            <div>
                <span class="admin-kicker">FILLONI KËTU</span>
                <h2>Çfarë dëshironi të përditësoni?</h2>
            </div>
        </div>
        <div class="admin-quick-links">
            <a href="{{ route('admin.settings') }}">
                <strong>Tekstet dhe cilësimet</strong>
                <span>Ndryshoni titullin, prezantimin, kontaktet, hartën dhe ngjyrën.</span>
                <span class="admin-quick-link__arrow">@include('admin.partials.icon', ['name' => 'arrow'])</span>
            </a>
            <a href="{{ route('admin.products') }}">
                <strong>Produktet dhe imazhet</strong>
                <span>Shtoni kategori, përditësoni përshkrimet ose ngarkoni fotografi.</span>
                <span class="admin-quick-link__arrow">@include('admin.partials.icon', ['name' => 'arrow'])</span>
            </a>
            <a href="{{ route('admin.services') }}">
                <strong>Shërbimet teknike</strong>
                <span>Ndryshoni listën e shërbimeve, përshkrimet dhe renditjen.</span>
                <span class="admin-quick-link__arrow">@include('admin.partials.icon', ['name' => 'arrow'])</span>
            </a>
            <a href="{{ route('admin.about') }}">
                <strong>Besimi dhe statistikat</strong>
                <span>Përditësoni avantazhet e kompanisë dhe shifrat në faqe.</span>
                <span class="admin-quick-link__arrow">@include('admin.partials.icon', ['name' => 'arrow'])</span>
            </a>
        </div>
    </section>

    <section class="admin-live-card">
        <span class="admin-live-indicator" aria-hidden="true"></span>
        <div>
            <strong>Ndryshimet shfaqen menjëherë</strong>
            <p>Kur ruani ndryshimet, vizitorët i shohin në faqen kryesore. Nuk nevojitet publikim manual.</p>
        </div>
    </section>
@endsection
