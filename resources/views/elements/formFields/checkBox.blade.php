<div class="form-group row">
    <label for="active" class="col-sm-6">
        @isset($label)
            {{ $label }}:
        @endisset
    </label>
    <div class="col-sm-2">
        <input @isset($id)
                id="{{ $id }}"
            @endisset
            class="form-check-input" type="checkbox" name="{{ $name }}"
            @isset($checked)
                @if ($checked)checked @endif
            @endisset>
    </div>
</div>
