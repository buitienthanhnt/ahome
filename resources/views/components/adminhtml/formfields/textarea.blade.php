<div class="form-group">
    @include('components.adminhtml.formfields.label', [
        'field' => $field,
    ])
    <textarea class="form-control" id="{{ 'form-id-' . $field['key'] }}" rows="3" name="{{ $field['key'] }}" value="{{ old($field['key']) }}">@isset($field['value']){{ $field['value'] }}@endisset</textarea>
</div>
