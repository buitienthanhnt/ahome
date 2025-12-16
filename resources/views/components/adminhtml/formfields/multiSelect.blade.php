<div class="form-group">
    @include('components.adminhtml.formfields.label', [
        'field' => $field,
    ])
    <select id="select2-{{ $field['key'] }}" name="{{ $field['key'] }}[]" multiple="multiple" style="width: 75%">
        @isset($field['model'])
            @foreach ($field['model']::{ $field['key'] . 'Options' }() as $item)
                <option value="{{ $item['value'] }}"
                    @isset($field['value'])
            @if (in_array($item['value'], $field['value']))
                selected
            @endif
        @endisset>
                    {{ $item['label'] }}</option>
            @endforeach
        @else
            @isset($field['value'])
                @foreach ($field['value'] as $i)
                    <option value="{{ $i->value }}" selected>{{ $i->value }}</option>
                @endforeach
            @endisset
        @endisset
    </select>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#select2-{{ $field['key'] }}").select2({
                placeholder: "Select a state",
                allowClear: true,
                width: 'resolve',
                multiple: true,
                ...JSON.parse(`{!! isset($field['model']) ? '{}' : json_encode(['tags' => true, 'tokenSeparators' => [',',]]) !!}`)
            });
        });
    </script>
</div>
