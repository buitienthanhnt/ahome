<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Title:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="page_title" required
                    value="@isset($title){{ $title }}@endisset" required
                    placeholder="page title" />
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <label for="active" class="col-sm-2">Active:</label>
        <input id="active" class="form-check-input" type="checkbox" name="active"
            @if (isset($active)) {{ $active ? 'checked' : '' }} @else checked @endif>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label for="url-alias" class="col-sm-2">Url alias:</label>
            <div class="col-sm-8">
                <input id="url-alias" class="form-control" type="text" name="alias"
                    placeholder="use page title if this value is null"
                    value="@isset($url_alias){{ $url_alias }}@endisset">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <label for="show" class="col-sm-2">Show: </label>
        <input id="show" class="form-check-input" type="checkbox" name="show"
            @if (isset($show)) {{ $show ? 'checked' : '' }} @else checked @endif>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label for="short_conten" class="col-sm-4 col-form-label">Short conten:</label>
        <div class="col-sm-10">
            <textarea 
                id="short_conten" name="short_conten" 
                class="form-control" rows="4"
                style="padding: 10px; height: 100%;">@isset($short_conten){{ $short_conten }}@endisset</textarea>
        </div>
    </div>

    <div class="col-md-6">
        <label for="auto_hide" class="col-sm-2">Auto hide: </label>
        <input id="auto_hide" class="form-check-input" type="checkbox" name="auto_hide"
            @if (isset($auto_hide)) {{ $auto_hide ? 'checked' : '' }} @else checked @endif>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label for="time_line_type" class="col-sm-2 col-form-label">TimeLine:</label>
        <div class="col-sm-10">
            <div class="form-group">
                <select id="time_line_type" class="form-control" name="time_line_type" multiple="multiple">
                    {!! $time_line_option !!}
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6" style="align-content: center">
        <div class="form-group row" style="margin-bottom: 0px">
            <label for="url-alias" class="col-sm-2">Timeline:</label>
            <div class="cs-form col-sm-8">
                <input name="time_line_value" id="timelineInput" />
                <script type="text/javascript">
                    // https://gijgo.com/datetimepicker
                    $("#timelineInput").datetimepicker({
                        datepicker: {
                            showOtherMonths: true,
                            calendarWeeks: true,
                            todayHighlight: true
                        },
                        footer: true,
                        modal: true,
                        header: true,
                        value: '',
                        format: 'yyyy-mm-dd HH:MM:ss',
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label for="category" class="col-sm-2 col-form-label">Category:</label>
        <div class="col-sm-10">
            <div class="form-group">
                <select id="category_option" class="form-control" name="category_option[]" multiple="multiple">
                    {!! $category_option !!}
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        {{-- <div class="form-group row"> --}}
        <label class="col-sm-2 col-form-label">Image: </label>
        <div class="col-sm-9">
            <div class="input-group">
                <span class="input-group-btn">
                    <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                        <i class="fa fa-picture-o"></i> Choose
                    </a>
                </span>
                <input id="thumbnail" class="form-control" type="text" name="image_path">
            </div>
            <img id="holder" style="margin-top:15px;max-height:100px;">
        </div>
        {{-- </div> --}}
    </div>
</div>

<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            <label for="conten" class="col-sm-2 col-form-label">Page content: </label>
            <textarea style="height: 1000px" id="conten" name="conten" class="form-control">
@if (isset($conten))
{!! $conten !!}
@else
{!! old('content', '') !!}
@endif
</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="paper-tag">Tag for links</label>
            <select class="paper_tag form-control" name="paper_tag[]" multiple="multiple">
            </select>
        </div>
    </div>
    <div class="col-md-6">
        {{-- <input id="paper_writer" class="form-control" type="text" name="writer"> --}}
        <div class="form-group">
            <label for="paper_writer">Writer:</label>
            <select class="form-control" name="writer" id="paper_writer">
                @if ($writers)
                    @foreach ($writers as $writer)
                        <option value="{{ $writer->id }}">{{ $writer->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>
