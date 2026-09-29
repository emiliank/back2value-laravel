@extends('admin.layout', ['title' => $page['label']])

@section('content')
    <section class="admin-page-intro">
        <div>
            <span class="admin-kicker">{{ $page['group'] }}</span>
            <h2>{{ $page['label'] }}</h2>
            <p>{{ $page['description'] }}</p>
        </div>
        <form method="POST" action="{{ route('admin.content.reset', ['page' => $slug]) }}" class="admin-inline-form">
            @csrf
            <button class="admin-button admin-button--secondary" type="submit" data-confirm="Ktheni të gjithë fushat e kësaj faqeje te vlerat fillestare?">
                <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'refresh'])</span>
                Kthe te default
            </button>
        </form>
    </section>

    <form
        method="POST"
        action="{{ route('admin.content.update', ['page' => $slug]) }}"
        enctype="multipart/form-data"
        class="admin-form"
        data-content-form
    >
        @csrf
        @method('PUT')

        @foreach ($page['sections'] as $sectionKey)
            @php($section = $sectionDefinitions[$sectionKey])
            <section class="admin-panel admin-content-section">
                <div class="admin-panel__heading">
                    <div>
                        <span class="admin-kicker">{{ $section['kicker'] }}</span>
                        <h2>{{ $section['title'] }}</h2>
                        <p>{{ $section['description'] }}</p>
                    </div>
                </div>

                @if (($section['shape'] ?? 'group') === 'list')
                    @include('admin.content.repeater', [
                        'name' => $sectionKey,
                        'field' => \App\Content\ContentSchema::asRepeater($section),
                        'rows' => old($sectionKey, $content[$sectionKey] ?? []),
                        'errors' => $errors,
                    ])
                @else
                    @foreach ($section['groups'] as $group)
                        <div class="admin-content-group">
                            <span class="admin-subheading">{{ $group['label'] }}</span>
                            <div class="admin-form-grid">
                                @foreach ($group['fields'] as $field)
                                    @include('admin.content.field', [
                                        'name' => $sectionKey.'.'.$field['key'],
                                        'field' => $field,
                                        'value' => old($sectionKey.'.'.$field['key'], $content[$sectionKey][$field['key']] ?? null),
                                        'errors' => $errors,
                                        'placeholder' => false,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </section>
        @endforeach

        <div class="admin-save-bar">
            <span>{{ count($page['sections']) }} seksione në këtë faqe.</span>
            <button class="admin-button admin-button--primary" type="submit">
                Ruaj ndryshimet
                <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'check'])</span>
            </button>
        </div>
    </form>
@endsection
