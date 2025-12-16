<div class="form-group">
    @include('components.adminhtml.formfields.label', [
        'field' => $field,
    ])
    <input type="{{ $field['type'] }}" class="form-control" id="{{ 'form-id-' . $field['key'] }}"
        @isset($field['required']) required @endisset @if (old($field['key']) !== null) value="{{ old($field['key']) }}" @endisset
        aria-describedby="{{ $field['key'] . '_Help' }}" name="{{ $field['key'] }}" placeholder="{{ $field['placeholder'] ??  __('attr.' . $field['key']) }}"
        @isset($field['value']) value="{{ $field['value'] }}" @endisset
    />
</div>
