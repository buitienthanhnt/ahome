@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('body_main_conten')
    <div class="col-12 grid-margin">
        <div class="">
            <div class="card-body">
                <h4 class="card-title col-form-label">Create @if (request()->get('type'))
                        new TimeLine
                    @else
                        new Category
                    @endif
                </h4>
                <form class="form-sample" method="POST" enctype="multipart/form-data"
                    action={{ route('admin_insert_category', ['type' => request()->get('type')]) }}>
                    @csrf
                    @if (session('success'))
                        <div class="alert alert-success" id="category_insert_success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">
                                    @if (request()->get('type'))
                                        TimeLine
                                    @else
                                        Category
                                    @endif Name:
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Short Description:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="description" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Parent Category: </label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="parent_id">
                                        <?= $parent_category ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Active: </label>
                                <div class="col-sm-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="active"
                                                id="membershipRadios1" value="1" checked>
                                            active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="active"
                                                id="membershipRadios2" value="0">
                                            in_active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Url rewrite:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="url_alias" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Image:</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control pb-10" id="category_image"
                                        name="category_image" />
                                    <img src="#" style="width: 100%; height: 240px; resize: cover; display: none"
                                        class="form-control" alt="your image" id="category_preview" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="offset-md-8 col-md-4">
                            <button type="submit" class="btn btn-info">
                                Save
                                @if (request()->get('type'))
                                    TimeLine
                                @else
                                    Category
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        category_image.onchange = evt => {
            const [file] = category_image.files
            if (file) {
                $(category_preview).show();
                category_preview.src = URL.createObjectURL(file)
            } else {
                $(category_preview).hide();
            }
        }


        setInterval(() => {
            var category_insert_success = $("#category_insert_success");
            if (category_insert_success.length) {
                $(category_insert_success).remove()
            }
        }, 5000);
    </script>
@endsection
