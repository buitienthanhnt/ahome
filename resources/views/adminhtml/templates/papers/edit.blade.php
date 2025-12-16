@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    paper edit
@endsection

@section('head_js_after')
    <script src="{{ asset('assets/all/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/all/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
    <script src="{{ asset('assets/adminhtml/gijgo/gijgo.min.js') }}"></script>
    <script src="{{ asset('assets/frontend/js/dragula/dragula.js') }}"></script>
@endsection

@section('after_css')
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/frontend/css/dragula/dragula.css') }}" rel='stylesheet' type='text/css' />
    <link href="{{ asset('assets/frontend/css/dragula/example.css') }}" rel='stylesheet' type='text/css' />
    <style type="text/css">
        .select2-selection--multiple>.select2-selection__choice {
            color: color('white');
            border: 0;
            border-radius: 3px;
            padding: 6px !important;
            font-size: larger !important;
            font-family: inherit;
            line-height: 1;
        }

        .select2-selection--multiple>.sliderImages {
            max-height: 480px !important;
        }

        .form-group {
            margin-bottom: 10px !important;
        }
    </style>
@endsection

@section('body_main_conten')
    @inject('category', 'App\Models\Category')
    <div class="col-12 grid-margin">
        <h4 class="card-title">add update source</h4>
        <form class="form-sample" method="POST" enctype="multipart/form-data"
            action={{ route('admin_update_paper', ['paper_id' => $paper->id]) }}>
            @csrf
            {!! view('elements.message.index')->render() !!}
            <div class="row">
                <div class="col-md-6">
                    {!! view('elements.formFields.textField', [
                        'label' => 'Title',
                        'name' => App\Models\PaperInterface::ATTR_TITLE,
                        'require' => true,
                        'value' => $paper->title ?? null,
                    ])->render() !!}
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            {!! view('elements.formFields.checkBox', [
                                'label' => 'Active',
                                'name' => App\Models\PaperInterface::ATTR_ACTIVE,
                                'checked' => $paper->active,
                            ])->render() !!}
                        </div>
                        <div class="col-md-4">
                            {!! view('elements.formFields.checkBox', [
                                'label' => 'Show Top',
                                'name' => App\Models\PaperInterface::ATTR_SHOW,
                                'checked' => $paper->show,
                            ])->render() !!}
                        </div>
                        <div class="col-md-4">
                            {!! view('elements.formFields.checkBox', [
                                'label' => 'Auto hide',
                                'name' => App\Models\PaperInterface::ATTR_AUTO_HIDE,
                                'checked' => $paper->auto_hide,
                            ])->render() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {!! view('elements.formFields.textField', [
                        'label' => 'Url alias',
                        'name' => \App\Models\PaperInterface::ATTR_URL_ALIAS,
                        'value' => $paper->url_alias ?? '',
                    ])->render() !!}
                </div>
                <div class="col-md-6">
                    <div class="col-sm-10">
                        {!! view('elements.formFields.select2Fields', [
                            'label' => 'Select category',
                            'id' => 'category_option',
                            'name' => App\Models\PaperInterface::EX_ATTR_CATEGORY,
                            'options' => $category->setSelected($paper->listIdCategories())->category_tree_option(),
                            'place_holder' => 'Select an option',
                            'token_separators' => [',', ' '],
                            'multiple' => true,
                        ])->render() !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    {!! view('elements.formFields.textArea', [
                        'label' => 'Short conten',
                        'name' => App\Models\PaperInterface::ATTR_SHORT_CONTENT,
                        'value' => $paper->short_conten ?? null,
                    ])->render() !!}
                </div>
                <div class="col-md-6">
                    {!! view('elements.formFields.chooseFile', [
                        'label' => 'Thumbnail image',
                        'id' => 'lfm_1',
                        'input_id' => 'input_id_1',
                        'name' => App\Models\PaperInterface::ATTR_IMAGE_PATH,
                        'preview_id' => 'preview_id_1',
                        'value' => $paper->image_path ?? null,
                    ])->render() !!}
                </div>
            </div>

            <div class="row">
                <div class='wrapper col-md-12'>
                    <div id='left-defaults' class='container' style="width: 30%">
                        {!! view('adminhtml.templates.papers.contenElement.image')->render() !!}
                        @php
                            $list_contens = $paper->getContents()->pluck('type')->toArray();
                        @endphp

                        @if (!in_array('price', $list_contens))
                            {!! view('adminhtml.templates.papers.contenElement.price')->render() !!}
                        @endif

                        @if (!in_array('timeline', $list_contens))
                            {!! view('adminhtml.templates.papers.contenElement.timeline', [
                                'time_line_option' => $category->timelineOptionHtml(
                                    array_filter(
                                        array_map(function ($i) {
                                            return $i[App\Models\PaperContentInterface::ATTR_TYPE] ===
                                                App\Models\PaperContentInterface::TYPE_TIMELINE
                                                ? $i[App\Models\PaperContentInterface::ATTR_DEPEND_VALUE]
                                                : null;
                                        }, $paper->getContents()->toArray()),
                                    ),
                                ),
                            ])->render() !!}
                        @endif

                        @if (!in_array('conten', $list_contens))
                            {!! view('adminhtml.templates.papers.contenElement.conten')->render() !!}
                        @endif

                        @if (!in_array('slider_data', $list_contens))
                            {!! view('adminhtml.templates.papers.contenElement.sliderData')->render() !!}
                        @endif

                        @if (!in_array('video', $list_contens))
                            {!! view('adminhtml.templates.papers.contenElement.video')->render() !!}
                        @endif


                    </div>
                    <div id='right-defaults' class='container col-md-7'>
                        @foreach ($paper->getContents() as $item)
                            @switch($item->type)
                                @case('price')
                                    {!! view('adminhtml.templates.papers.contenElement.price', ['item' => $item])->render() !!}
                                @break;
                                @case('image')
                                    {!! view('adminhtml.templates.papers.contenElement.image', ['item' => $item])->render() !!}
                                @break;
                                @case('timeline')
                                    {!! view('adminhtml.templates.papers.contenElement.timeline', [
                                        'item' => $item,
                                        'time_line_option' => $category->timelineOptionHtml(
                                            array_filter(
                                                array_map(function ($i) {
                                                    return $i[App\Models\PaperContentInterface::ATTR_TYPE] ===
                                                        App\Models\PaperContentInterface::TYPE_TIMELINE
                                                        ? $i[App\Models\PaperContentInterface::ATTR_DEPEND_VALUE]
                                                        : null;
                                                }, $paper->getContents()->toArray()),
                                            ),
                                        ),
                                    ])->render() !!}
                                @break;
                                @case('content')
                                    {!! view('adminhtml.templates.papers.contenElement.conten', [
                                        'item' => $item,
                                    ])->render() !!}
                                @break;
                                @case('video')
                                    {!! view('adminhtml.templates.papers.contenElement.video', [
                                        'item' => $item,
                                    ])->render() !!}
                                @break;
                                @case('slider_data')
                                    {!! view('adminhtml.templates.papers.contenElement.sliderData', [
                                        'item' => $item,
                                    ])->render() !!}
                                @break;

                                @default
                            @endswitch
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    @php
                        $tags = $paper->getTags();
                        $tag_values = array_column($tags->toArray(), 'value');
                    @endphp
                    {!! view('elements.formFields.select2Fields', [
                        'id' => 'paper_tag',
                        'label' => 'Tag for links',
                        'name' => App\Models\PaperInterface::EX_ATTR_TAGS,
                        'place_holder' => 'liên kết',
                        'token_separators' => [','],
                        'options' => $tags,
                        'value' => $tag_values,
                        'multiple' => true,
                    ])->render() !!}
                </div>
                <div class="col-md-6">
                    {!! view('elements.formFields.select2Fields', [
                        'label' => 'Writer',
                        'id' => 'paper_writer',
                        'name' => App\Models\PaperInterface::ATTR_WRITER,
                        'options' => App\Models\Writer::all(),
                        'value' => [$paper->writer],
                        'token_separators' => [''],
                        'multiple' => false,
                        'require' => true,
                    ])->render() !!}
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    {!! view('elements.formFields.submitBtn', ['label' => 'update content'])->render() !!}
                </div>
            </div>
        </form>
    </div>
@endsection

@section('after_js')
    <script>
        function imgOnclick(type = 'file', options = {
            prefix: filemanager_url_base,
        }) {
            var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
            var target_input = $('#' + $(this).data('input'));
            var target_preview = $('#' + $(this).data('preview'));
            window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
            window.SetUrl = function(items) {
                var file_path = items.map(function(item) {
                    return item.url;
                }).join(',');

                // set the value of the desired input to image url
                target_input.val('').val(file_path).trigger('change');

                // clear previous preview
                target_preview.html('');

                // set or change the preview image src
                items.forEach(function(item) {
                    target_preview.append(
                        $('<img>').css('height', '5rem').attr('src', item.thumb_url)
                    );
                });

                // trigger change event
                target_preview.trigger('change');
            };
            return false;
        }

        // https://github.com/bevacqua/dragula
        var dra = dragula(
            [document.getElementById('left-defaults'), document.getElementById('right-defaults')], {
                isContainer: function(el) {
                    return false; // only elements in drake.containers will be taken into account
                },
                moves: function(el, source, handle, sibling) {
                    return true; // elements are always draggable by default
                },
                accepts: function(el, target, source, sibling) {
                    return true; // elements can be dropped in any of the `containers` by default
                },
                invalid: function(el, handle) {
                    return false; // don't prevent any drags from initiating by default
                },
                direction: 'vertical', // Y axis is considered when determining where an element would be dropped
                copy: function(el, source) {
                    return $(el).attr('data-type') === 'p-picture' && $(source).attr('id') === 'left-defaults';
                }, // elements are moved by default, not copied
                copySortSource: true, // elements in copy-source containers can be reordered
                revertOnSpill: false, // spilling will put the element back where it was dragged from, if this is true
                removeOnSpill: false, // spilling will `.remove` the element, if this is true
                mirrorContainer: document.body, // set the element that gets mirror elements appended
                ignoreInputTextSelection: true, // allows users to select input text, see details below
                slideFactorX: 0, // allows users to select the amount of movement on the X axis before it is considered a drag instead of a click
                slideFactorY: 0, // allows users to select the amount of movement on the Y axis before it is considered a drag instead of a click
            }
        ).on('dragend', function(el) {
            console.log('dragend', $($(el).parent()[0]).attr('id'));
            if ($(el).attr('data-type') === 'p-picture' && $($(el).parent()[0]).attr('id') === 'left-defaults') {
                if ($("div[data-type=p-picture]").length > 1) {
                    $(el).remove();
                }
                return;
            }

            if ($($(el).parent()[0]).attr('id') === 'left-defaults') {
                $($(el).children()[0]).show();
                $($(el).children()[1]).hide();
                return;
            }

            $(el).click(function(params) {
                // console.log(params, $(this).attr('data-type'));
                let p_type = $(this).attr('data-type');
                switch (p_type) {
                    case 'p-html':
                        $($(this).children()[0]).hide();
                        $($($(this).children()[1]).children()[0]).show();
                        encodeURI('\uD800\uDFFF');
                        tinymce.init({
                            convert_urls: false,
                            selector: "textarea#conten",
                            plugins: ["image", "table", "code", "codesample",
                                "showcomments", "media"
                            ],
                            toolbar1: 'undo redo | fontfamily fontsize styles bold italic underline | alignleft aligncenter alignright alignjustify alignnone | indent outdent | wordcount | lineheight help image media',
                            toolbar2: 'anchor | blockquote | backcolor forecolor | copy | cut | paste pastetext | hr | language | newdocument | print | remove removeformat | selectall | strikethrough | subscript superscript | visualaid | a11ycheck typopgraphy anchor restoredraft casechange charmap checklist ltr rtl editimage fliph flipv imageoptions rotateleft rotateright emoticons export footnotes footnotesupdate formatpainter fullscreen insertdatetime link openlink unlink bullist numlist mergetags mergetags_list nonbreaking pagebreak pageembed permanentpen preview quickimage quicklink cancel save searchreplace spellcheckdialog spellchecker | template typography | insertfile | visualblocks visualchars',
                            file_picker_callback: function(callback, value, meta) {
                                let x = window.innerWidth || document.documentElement
                                    .clientWidth || document
                                    .getElementsByTagName('body')[0].clientWidth;
                                let y = window.innerHeight || document.documentElement
                                    .clientHeight || document
                                    .getElementsByTagName('body')[0].clientHeight;

                                let type = 'image' === meta.filetype ? 'Images' : 'Files';

                                tinymce.activeEditor.windowManager.openUrl({
                                    url: filemanager_url_mce,
                                    title: 'Filemanager',
                                    width: x * 0.8,
                                    height: y * 0.8,
                                    onMessage: (api, message) => {
                                        callback(message.content);
                                    }
                                });
                            }
                        })
                        break;
                    case 'p-picture':
                        $($(this).children()[0]).hide();
                        $($(this).children()[1]).show();
                        const pictute_thum = 'imagex_' + $("#right-defaults > div[data-type=p-picture]")
                            .length;
                        const id_thum = 'lfm_' + pictute_thum;
                        $($($($(this).children()[1]).children()[0]).children()[0]).attr("id", id_thum).attr(
                            "data-input", pictute_thum);
                        $($($(this).children()[1]).children()[1]).attr("id", pictute_thum).attr("name",
                            "images_" + pictute_thum);
                        // set image desciption name:
                        const pictute_desc = 'imagex_desc_' + $(
                                "#right-defaults > div[data-type=p-picture]")
                            .length;
                        $($($($(this).children()[1]).children()[2]).children()[1]).attr('name',
                            pictute_desc)
                        $($($($(this).children()[1]).children()[0]).children()[0]).on('click', imgOnclick);
                        $(this).off('click');
                    case 'p-price':
                    case 'p-timeline':
                    case 'p-video':
                    case 'p-carousel':
                        $($(this).children()[0]).hide();
                        $($(this).children()[1]).show();
                        break;
                    default:
                        break;
                }
            });
        }).on('remove', function(el) {
            console.log('remove');

        }).on('drop', function(el) {
            console.log('drop');

        }).on('cancel', function(el) {
            console.log('cancel');

        });

        tinymce.init({
            convert_urls: false,
            selector: "textarea#conten",
            plugins: ["image", "table", "code", "codesample", "addcomment", "showcomments", "media"],
            toolbar1: 'undo redo | fontfamily fontsize styles bold italic underline | alignleft aligncenter alignright alignjustify alignnone | indent outdent | wordcount | lineheight help image media',
            toolbar2: 'anchor | blockquote | backcolor forecolor | copy | cut | paste pastetext | hr | language | newdocument | print | remove removeformat | selectall | strikethrough | subscript superscript | visualaid | a11ycheck typopgraphy anchor restoredraft casechange charmap checklist ltr rtl editimage fliph flipv imageoptions rotateleft rotateright emoticons export footnotes footnotesupdate formatpainter fullscreen insertdatetime link openlink unlink bullist numlist mergetags mergetags_list nonbreaking pagebreak pageembed permanentpen preview quickimage quicklink cancel save searchreplace spellcheckdialog spellchecker | template typography | insertfile | visualblocks visualchars',
            file_picker_callback: function(callback, value, meta) {
                let x = window.innerWidth || document.documentElement.clientWidth || document
                    .getElementsByTagName('body')[0].clientWidth;
                let y = window.innerHeight || document.documentElement.clientHeight || document
                    .getElementsByTagName('body')[0].clientHeight;

                let type = 'image' === meta.filetype ? 'Images' : 'Files';

                tinymce.activeEditor.windowManager.openUrl({
                    url: filemanager_url_mce,
                    title: 'Filemanager',
                    width: x * 0.8,
                    height: y * 0.8,
                    onMessage: (api, message) => {
                        callback(message.content);
                    }
                });
            }
        });
    </script>
@endsection
