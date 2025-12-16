{{--[ 'label' => str, 'id' => uni_str_, 'input_id' => uni_str, 'name' => uni_str, 'preview_id' => uni_str,] --}}
<div class="form-group">
    <label class="col-sm-3">
        @isset($label)
            {{ $label }}:
        @endisset
    </label>
    <div class="col-sm-9">
        <div class="input-group">
            <span class="input-group-btn">
                <a id="{{ $id }}" data-input="{{ $input_id }}" data-preview="{{ $preview_id }}"
                    class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> Choose
                </a>
            </span>
            <input id="{{ $input_id }}" class="form-control" type="text" name="{{ $name }}"
                @isset($value)value="{{ $value }}"@endisset>
        </div>
        <img id="{{ $preview_id }}" style="margin-top:5px;max-height:100px;">
    </div>
</div>

<script type="text/javascript">
    // not use: $(document).ready()
    $('#@php echo($id); @endphp').filemanager('file', {
        prefix: filemanager_url_base
    });
</script>
