<div class="form-group">
    @include('components.adminhtml.formfields.label', [
        'field' => $field,
    ])
    <select class="custom-select" name="{{ $field['key'] }}">
        <option value="">Open this select menu</option>
        @foreach ($field['model']::{ $field['key'] . 'Options' }() as $item)
            <option value="{{ $item['value'] }}"
                @isset($field['value'])
                @if ($field['value'] === $item['value'])
                    selected
                @endif
            @endisset>
                {{ $item['label'] }}</option>
        @endforeach
    </select>
</div>
