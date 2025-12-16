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
            <textarea id="short_conten" name="short_conten" 
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
    <div class="col-md-6"></div>
    <div class="col-md-6">
        <div class="form-group row">
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
                        format: 'yyyy-dd-mm HH:MM:ss',
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
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="col-sm-6 col-form-label">Slider image lists:</label>
            <div class="col-sm-9">
                <div class="form-group">
                    <button type="button" class="btn form-control btn-primary" data-toggle="modal" id="addSlider">
                        Add slider item
                    </button>
                    <textarea id="sliderDataConten" style="display: none" name="slider_data"></textarea>
                    <div class="modal fade" id="sliderModal" tabindex="-1" role="dialog"
                        aria-labelledby="sliderModal" aria-hidden="true">
                        <div class="modal-dialog modal-lg" style="max-width: 800px" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Input item content</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="captions_label">captions label</label>
                                        <input id="captions_label" class="form-control" type="text"
                                            name="captions_label">
                                    </div>

                                    <div class="form-group">
                                        <label for="captions_content">captions content</label>
                                        <textarea id="captions_content" class="form-control" style="height: auto" name="captions_content" rows="6"></textarea>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">image:</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <span class="input-group-btn">
                                                        <a id="slider_image" data-input="slider_images"
                                                            data-preview="holder" class="btn btn-primary">
                                                            <i class="fa fa-picture-o"></i> Choose
                                                        </a>
                                                    </span>
                                                    <input id="slider_images" class="form-control" type="text"
                                                        name="slider_images">
                                                </div>
                                                <img id="holder" style="margin-top:15px;max-height:100px;">
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                        id="closeSliderImages">Close</button>
                                    <button type="button" class="btn btn-primary" id="saveCarouiselItem">Save
                                        changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <img id="holder" style="margin-top:15px;max-height:100px;">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <label class="col-sm-6 col-form-label">Paper carousel preview: </label>
        <div class="col-md-6">
            <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            </div>
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

<script>
    var slider = [];

    function renderCarousel(data) {
        let beginIndicator = '<ol class="carousel-indicators">';
        let content = ' <div class="carousel-inner">';
        let change =
            '<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">' +
            '<span class="carousel-control-prev-icon" aria-hidden="true"></span>' +
            '<span class="sr-only">Previous</span>' +
            '</a>' +
            '<a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">' +
            '<span class="carousel-control-next-icon" aria-hidden="true"></span>' +
            '<span class="sr-only">Next</span>' +
            '</a>';
        for (let i = 0; i < slider.length; i++) {
            beginIndicator += '<li data-target="#carouselExampleCaptions" data-slide-to="' + i + '" class="' + (
                i == 0 ? 'active' : ' ') + '"></li>';

            content += '<div class="carousel-item ' + (i == 0 ? 'active' : '') + '">' +
                '<img class="d-block w-100 sliderImages" src="' + (slider[i].image_path) + '" >' +
                '<div class="carousel-caption d-none d-md-block">' +
                '<h5>' + (slider[i].title) + '</h5>' +
                '<p>' + (slider[i].label) + '</p>' +
                '</div>' +
                '</div>';
        }
        beginIndicator += '</ol>';
        content += ' </div>';
        return beginIndicator + content + change;
    }
    $(document).ready(function() {
        $("#carouselExampleCaptions").html(renderCarousel(slider));

        $("#addSlider").click(function() {
            $("#sliderModal").modal('show');
        });
        $("#closeSliderImages").click(function() {
            $("#sliderModal").modal('hide');
        })

        $("#saveCarouiselItem").click(function() {
            let title = $("#captions_label").val();
            let content = $("#captions_content").val();
            let image_path = $("#slider_images").val();
            slider.push({
                title: title,
                label: content,
                image_path: image_path
            });
            $("#carouselExampleCaptions").html(renderCarousel(slider));
            $("#sliderDataConten").val(JSON.stringify(slider));
            $("#sliderModal").modal('hide');
        });
    })
</script>
