@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    file add
@endsection

@section('body_main_conten')
    <div class="col-12 grid-margin">
        <div class="">
            <div class="card-body">
                <h4 class="card-title">add new file to source</h4>
                <form class="form-sample" method="POST" enctype="multipart/form-data" action={{ route('admin_save_file') }}>
                    @csrf
                    @if (session('success'))
                        <div class="alert alert-success" id="save_image_success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">file name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="file_name" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">image:</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control pb-10" id="save_image"
                                        name="save_image" />
                                    <img src="#" style="width: 100%; height: 240px; resize: cover; display: none"
                                        class="form-control" alt="your image" id="priview_image" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 justify-content-center row">
                            {{-- <div class="col-md-4 row"> --}}
                                <button type="submit" class="btn btn-info btn-lg btn-block">save file</button>
                            {{-- </div> --}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        save_image.onchange = evt => {
            const [file] = save_image.files
            if (file) {
                $(priview_image).show();
                priview_image.src = URL.createObjectURL(file)
            }else{
                $(priview_image).hide();
            }
        }
    </script>
@endsection
