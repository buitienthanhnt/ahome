<div class="form-group">
    <label for="short_conten">short conten:</label>
    <div class="col-sm-10">
        <textarea id="short_conten" name="short_conten" class="form-control" rows="4"
                  style="padding: 10px; height: 100%;"
                  @isset($require)
                  @if ($require) required @endif
            @endisset
        >@isset($value){{ $value }}@endisset</textarea>
    </div>
</div>
