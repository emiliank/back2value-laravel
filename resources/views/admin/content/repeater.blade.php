@php
    $rows = is_array($rows) ? $rows : [];
    $templateId = 'template-'.md5($name);
    $titleField = $field['title_field'] ?? 'title';
    $blankErrors = new \Illuminate\Support\ViewErrorBag;
@endphp

<div class="admin-repeater" data-repeater="{{ $name }}">
    <div class="admin-repeater__head">
        <span class="admin-subheading">{{ $field['label'] }}</span>
        <button class="admin-button admin-button--secondary" type="button" data-add-row="{{ $templateId }}" data-list="{{ $name }}-list">
            <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'plus'])</span>
            {{ $field['add_label'] }}
        </button>
    </div>

    <div id="{{ $name }}-list" class="admin-repeater__list" data-repeat-list>
        @forelse ($rows as $rowIndex => $row)
            @php
                $rowKey = is_string($rowIndex) ? $rowIndex : 'item-'.$rowIndex;
            @endphp

            <article class="admin-panel admin-repeat-card" data-repeat-card>
                <div class="admin-repeat-card__heading">
                    <div>
                        <span class="admin-kicker">RRESHT</span>
                        <h3>{{ is_array($row) ? ($row[$titleField] ?? 'Rresht i ri') : 'Rresht i ri' }}</h3>
                    </div>
                    <div class="admin-repeat-card__actions">
                        @if ($field['sortable'] ?? true)
                            @if ($loop->iteration > 1)
                                <button class="admin-button admin-button--secondary admin-button--icon" type="button" data-move-row="up" title="Lëviz lart">
                                    <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'arrow'])</span>
                                </button>
                            @endif
                            @if (! $loop->last)
                                <button class="admin-button admin-button--secondary admin-button--icon" type="button" data-move-row="down" title="Lëviz poshtë">
                                    <span class="admin-inline-icon admin-rotate-180">@include('admin.partials.icon', ['name' => 'arrow'])</span>
                                </button>
                            @endif
                        @endif

                        <button class="admin-button admin-button--remove" type="button" data-remove-row title="Hiq rreshtin">Hiq</button>
                    </div>
                </div>

                <input type="hidden" name="{{ $name }}[{{ $rowKey }}][remove]" value="0">

                <div class="admin-form-grid">
                    @foreach ($field['fields'] as $item)
                        @php
                            $itemKey = $item['key'];
                            $itemName = $name.'['.$rowKey.']['.$itemKey.']';
                            $itemValue = old($name.'.'.$rowKey.'.'.$itemKey, is_array($row) ? ($row[$itemKey] ?? null) : null);
                        @endphp

                        @include('admin.content.field', ['name' => $itemName, 'field' => $item, 'value' => $itemValue, 'errors' => $errors])
                    @endforeach
                </div>
            </article>
        @empty
            <p class="admin-repeater__empty">Nuk ka ende rreshta. Shtoni rreshtin e parë me butonin sipër.</p>
        @endforelse
    </div>

    <template id="{{ $templateId }}">
        <article class="admin-panel admin-repeat-card" data-repeat-card>
            <div class="admin-repeat-card__heading">
                <div>
                    <span class="admin-kicker">RRESHT I RI</span>
                    <h3>Rresht i ri</h3>
                </div>
                <div class="admin-repeat-card__actions">
                    <button class="admin-button admin-button--secondary admin-button--icon" type="button" data-move-row="up" title="Lëviz lart">
                        <span class="admin-inline-icon">@include('admin.partials.icon', ['name' => 'arrow'])</span>
                    </button>
                    <button class="admin-button admin-button--secondary admin-button--icon" type="button" data-move-row="down" title="Lëviz poshtë">
                        <span class="admin-inline-icon admin-rotate-180">@include('admin.partials.icon', ['name' => 'arrow'])</span>
                    </button>
                    <button class="admin-button admin-button--remove" type="button" data-remove-row title="Hiq rreshtin">Hiq</button>
                </div>
            </div>

            <input type="hidden" name="{{ $name }}[__KEY__][remove]" value="0">

            <div class="admin-form-grid">
                @foreach ($field['fields'] as $item)
                    @php($newItemName = $name.'[__KEY__]['.$item['key'].']')

                    @include('admin.content.field', ['name' => $newItemName, 'field' => $item, 'value' => null, 'errors' => $blankErrors])
                @endforeach
            </div>
        </article>
    </template>
</div>
