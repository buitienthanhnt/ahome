@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    paper create
@endsection

@section('head_js_after')
    <script src="{{ asset('assets/all/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/all/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('/vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
@endsection

@section('after_css')
    <style type="text/css">
        .select2-selection--multiple {
            .select2-selection__choice {
                color: color(white);
                border: 0;
                border-radius: 3px;
                padding: 6px;
                font-size: larger !important;
                font-family: inherit;
                line-height: 1;
            }
        }
    </style>
@endsection

@section('body_main_conten')
    <div class="col-12 grid-margin">
        <div>
            <div class="card-body">
                <h4 class="card-title">add new source</h4>
                <form class="form-sample" style="margin-top: 12px" method="POST" enctype="multipart/form-data"
                    action={{ route('admin_source_paper') }}>
                    @csrf
                    @if (session('success'))
                        <?php alert()->success('server message', session('success')); ?>
                    @elseif (session('error'))
                        <?php alert()->warning('server mesage', session('error')); ?>
                    @endif
                    <div class="row" style="margin-bottom: 10px">
                        <div class="col-md-12 row">
                            <label for="source_request" class="col-sm-2">URL source--->:</label>
                            <div class="col-sm-10">
                                <input id="source_request" class="form-control" type="text" name="source_request"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-info btn-lg"
                                    style="width: -webkit-fill-available;">view source conten</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('before_bottom_js')
    <script></script>
@endsection

@section('after_js')
    <script></script>
@endsection
