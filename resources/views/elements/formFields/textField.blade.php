<div class="form-group row">
    @isset($label)
        <label class="col-sm-2">{{ $label }}:</label>
    @endisset
    <div class="col-sm-8">
        <input type="text" class="form-control" name="{{ $name }}"
            @isset($require)
                @if ($require) required @endif
            @endisset
            @isset($value)
               value="{{ $value }}"
            @endisset />
    </div>
</div>
