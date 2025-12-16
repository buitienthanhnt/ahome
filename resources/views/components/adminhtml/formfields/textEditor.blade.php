<div class="p-1 form form-textarea">
    @include('components.adminhtml.formfields.label', [
        'field' => $field,
    ])
    <textarea name="{{ $field['key'] }}" id="id-{{ $field['key'] }}" placeholder="content of area">@isset($field['value']){!! $field['value'] !!}@endisset</textarea>
</div>
<script type="text/javascript">
    // lưu ý cần đặt giá trị trong dấu "" để nhận được giá trị: string trong js.
    // https://ckeditor.com/ckeditor-4/download/
    // nên ưu tiên dùng: tinymce hơn: ckeditor vì nó dễ dùng hơn.
    // https://www.tiny.cloud/docs/tinymce/latest/php-projects/ || https://www.tiny.cloud/docs/tinymce/latest/php-projects/
    tinymce.init({
        selector: "#id-{{ $field['key'] }}",
        license_key: 'gpl' // gpl for open source, T8LK:... for commercial
    });
</script>
