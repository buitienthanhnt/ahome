<label for="{{ 'form-id-' . $field['key'] }}" class="font-bold text-info text-2xl">
    @isset($field['label'])
        {{ $field['label'] }}:
    @else
        {{ __('attr.' . $field['key']) }}:
    @endisset
</label>
