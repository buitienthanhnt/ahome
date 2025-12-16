<div class="row">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-sm-2">Title:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="page_title" required
                    value="@isset($title){{ $title }}@endisset" required
                    placeholder="tiêu đề" />
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
                    placeholder="dùng tiêu đề thay thế nếu dòng này để trống"
                    value="@isset($url_alias){{ $url_alias }}@endisset">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group row">
            <label for="price" class="col-sm-2">Price:</label>
            <div class="col-sm-8">
                <input type="number" name="price" id="price" placeholder="nghìn vnđ" class="form-control"
                    min="0" value="@isset($price)
			{{ $price }}
		   @endisset">
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <label for="short_conten" class="col-sm-4 col-form-label">Short conten:</label>
        <div class="col-sm-10">
            <textarea id="short_conten" name="short_conten" class="form-control" rows="4"
                placeholder="mô tả ngắn cho bài viết" style="padding: 10px; height: 100%;">
@isset($short_conten)
{{ $short_conten }}
@endisset
</textarea>
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
                <input id="thumbnail" class="form-control" type="text" name="image_path" placeholder="ảnh đại diện">
            </div>
            <img id="holder" style="margin-top:15px;max-height:100px;">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            <label for="conten" class="col-sm-2 col-form-label">Page content: </label>
            <textarea style="height: 560px" id="conten" name="conten" class="form-control">
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
