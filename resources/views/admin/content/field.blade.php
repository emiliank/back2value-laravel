@php
    // Implicit keys (a multi-line field validated per line) return an array of
    // messages, so keep only the first one to stay renderable.
    $fieldPath = $path ?? $name;
    $errorKey = $errors->get($fieldPath);
    $errorKey = is_array($errorKey) ? ($errorKey[0] ?? null) : $errorKey;
    $describedBy = $errorKey ? $name.'-error' : null;
    $id = 'field-'.md5($name);

    // PHP rewrites "." to "_" in submitted field names, so a dotted path never
    // arrives intact. Submit bracket syntax instead, which nests correctly.
    // Repeater rows already arrive bracketed, so they pass through unchanged.
    $bracketed = str_contains($fieldPath, '.');
    $segments = $bracketed ? explode('.', $fieldPath) : [];
    $leaf = $bracketed ? array_pop($segments) : null;
    $head = $bracketed ? (string) array_shift($segments) : '';
    $tail = $bracketed ? implode('', array_map(fn ($s) => '['.$s.']', $segments)) : '';

    $inputName = $inputName ?? ($bracketed ? $head.$tail.'['.$leaf.']' : $name);
    $imagePathName = $bracketed ? $head.$tail.'['.$leaf.'_path]' : $name.'_path';
    $imageRemoveName = $bracketed ? $head.$tail.'['.$leaf.'_remove]' : $name.'_remove';
@endphp

@if ($field['type'] === 'repeater')
    @include('admin.content.repeater', [
        'name' => $fieldPath,
        'inputName' => $inputName,
        'field' => $field,
        'rows' => old($name, $value ?? []),
        'errors' => $errors,
    ])
@elseif ($field['type'] === 'checkbox')
    <label class="admin-field admin-field--checkbox {{ $field['wide'] ? 'admin-field--wide' : '' }}">
        <input type="hidden" name="{{ $inputName }}" value="0">
        <input type="checkbox" id="{{ $id }}" name="{{ $inputName }}" value="1" @checked((bool) ($value ?? false))>
        <span>{{ $field['label'] }}</span>
    </label>
@elseif ($field['type'] === 'color')
    <label class="admin-field">
        <span>{{ $field['label'] }}</span>
        <input type="color" name="{{ $inputName }}" value="{{ $value ?: '#17b78b' }}" maxlength="7" @if($errorKey) aria-invalid="true" @endif>
        @if ($field['hint'])<small>{{ $field['hint'] }}</small>@endif
        @include('admin.content.error', ['message' => $errorKey])
    </label>
@elseif ($field['type'] === 'select')
    <label class="admin-field">
        <span>{{ $field['label'] }}</span>
        <select
            id="{{ $id }}"
            name="{{ $inputName }}"
            @if($errorKey) aria-invalid="true" @endif
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
        >
            @if ($field['optional'])
                <option value="">— Pa zgjedhje —</option>
            @endif
            @foreach ($field['options'] as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
            @endforeach
        </select>
        @if ($field['hint'])<small>{{ $field['hint'] }}</small>@endif
        @include('admin.content.error', ['message' => $errorKey])
    </label>
@elseif ($field['type'] === 'image')
    @php
        $src = null;
        if (filled($value)) {
            $src = str_starts_with($value, 'images/') ? asset($value) : \Illuminate\Support\Facades\Storage::disk('public')->url($value);
        }
    @endphp
    <div class="admin-field admin-field--wide admin-image-field">
        <span>{{ $field['label'] }}</span>
        @if ($src)
            <div class="admin-product-preview">
                <img src="{{ $src }}" alt="" loading="lazy">
            </div>
        @endif
        <input type="hidden" name="{{ $imagePathName }}" value="{{ $value }}">
        <input type="file" id="{{ $id }}" name="{{ $inputName }}" accept="image/jpeg,image/png,image/webp,image/svg+xml" @if($errorKey) aria-invalid="true" @endif>
        @if ($field['hint'])<small>{{ $field['hint'] }}</small>@endif
        @if ($src)
            <label class="admin-inline-checkbox">
                <input type="checkbox" name="{{ $imageRemoveName }}" value="1">
                <span>Hiq imazhin aktual</span>
            </label>
        @endif
        @include('admin.content.error', ['message' => $errorKey])
    </div>
@elseif ($field['type'] === 'textarea' || $field['type'] === 'list' || $field['type'] === 'pairs')
    <label class="admin-field {{ in_array($field['type'], ['textarea', 'list', 'pairs'], true) && $field['wide'] ? 'admin-field--wide' : '' }}">
        <span>{{ $field['label'] }}</span>
        <textarea
            id="{{ $id }}"
            name="{{ $inputName }}"
            rows="{{ $field['rows'] }}"
            maxlength="{{ $field['max'] }}"
            @if($errorKey) aria-invalid="true" @endif
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
        >{{ in_array($field['type'], ['list', 'pairs'], true) ? \App\Content\ContentSchema::fromList($field, $value) : $value }}</textarea>
        @if ($field['hint'])<small>{{ $field['hint'] }}</small>@endif
        @include('admin.content.error', ['message' => $errorKey])
    </label>
@else
    <label class="admin-field {{ $field['wide'] ? 'admin-field--wide' : '' }}">
        <span>{{ $field['label'] }}</span>
        <input
            @if ($field['type'] === 'number') type="number" min="0" @else type="{{ $field['input'] ?? 'text' }}" @endif
            id="{{ $id }}"
            name="{{ $inputName }}"
            value="{{ $value }}"
            maxlength="{{ $field['max'] }}"
            @if($errorKey) aria-invalid="true" @endif
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
        >
        @if ($field['hint'])<small>{{ $field['hint'] }}</small>@endif
        @include('admin.content.error', ['message' => $errorKey])
    </label>
@endif
