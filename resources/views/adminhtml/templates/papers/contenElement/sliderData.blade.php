<div data-type="p-carousel" ondragover="" style="border-radius: 5px">
    <div>
        <div
            style="@isset($item)display: none; @endisset display: flex; justify-content: center; align-items: center">
            <img src="{{ asset('/assets/adminhtml/images/image-split-testing.png') }}" alt=""
                style="width: auto; height: 50px">

        </div>
        <p style="text-align: center; color: aliceblue; margin-top: 5px; margin-bottom: 0px">Carousel images</p>
    </div>
    <div class="data-content"
        @isset($item)
            @else style="display: none"
        @endisset>
        <button type="button" class="btn form-control btn-primary" data-toggle="modal" id="addSlider">
            Add slider item
        </button>
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel"></div>
        <textarea id="sliderDataConten" style="display: none" name="{{ App\Models\PaperContentInterface::TYPE_SLIDER }}"></textarea>
        <div class="modal fade" id="sliderModal" tabindex="-1" role="dialog" aria-labelledby="sliderModal"
            aria-hidden="true">
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
                            <input id="captions_label" class="form-control" type="text">
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
                                            <a id="slider_image" data-input="slider_images" data-preview="holder"
                                                class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> Choose
                                            </a>
                                        </span>
                                        <input id="slider_images" class="form-control" type="text">
                                    </div>
                                    <img id="holder" style="margin-top:15px;max-height:100px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="closeSliderImages">Close
                        </button>
                        <button type="button" class="btn btn-primary" id="saveCarouiselItem">Save
                            changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#slider_image').filemanager('file', {
        prefix: filemanager_url_base
    });
    var value = '@php echo($item->value ?? json_encode([])); @endphp';
    var slider = JSON.parse(value);

    function renderCarousel(data) {
        if (!data || data.length < 1) {
            return '';
        }
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

            content += '<div class="carousel-item ' + (i == 0 ? 'active' : '') + '" style="max-height: 320px;">' +
                '<span class="position-absolute btn btn-danger" onclick="removeItem(' + i +
                ')" style="right: 50px; top: 10px">delete</span>' +
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

    function removeItem(index) {
        console.log(index);

        slider.splice(index, 1);
        console.log(slider);

        $("#carouselExampleCaptions").html(renderCarousel(slider));
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
