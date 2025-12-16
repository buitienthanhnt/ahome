@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    setup config
@endsection

@section('after_css')
    <style type="text/css">
        .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
            font-size: medium
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            font-size: medium !important
        }
    </style>
@endsection

@section('body_main_conten')
    <div class="container">
        <div>
            <a href="{{ route('admin_list_config') }}" class="btn btn-info"> list config</a>
        </div>
        <div class="col-md-6">
            <form action="{{ route('admin_insert_config') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">name</label>
                    <input type="text" class="form-control" name="name" id="name" aria-describedby="helpId"
                        placeholder="input name of config" required>
                    <small id="helpId" class="form-text text-muted">not use space character</small>
                </div>

                <div class="form-group">
                    <label for="value">value</label>
                    <input type="text" name="value" id="value" class="form-control"
                        placeholder="input value of config" aria-describedby="helpId" required>
                    <small id="helpId" class="text-muted">Help text</small>
                </div>

                <div class="form-check d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">save config</button>
                </div>
            </form>
        </div>
    </div>
@endsection
