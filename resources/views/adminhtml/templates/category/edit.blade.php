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
                <h4 class="card-title">Create new category</h4>
                <form class="form-sample" method="POST" enctype="multipart/form-data"
                    action={{ route('admin_update_category', ['category_id' => $category->id]) }}>
                    @csrf
                    @if (session('success'))
                        <div class="alert alert-success" id="category_insert_success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <p class="card-description">
                        category info
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Category Name:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" value="{{ $category->name }}"
                                        required />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Short Description:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $category->description }}"
                                        name="description" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Parent Category</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="parent_id">
                                        <?= $category_tree_option ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">active</label>
                                <div class="col-sm-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="active"
                                                id="membershipRadios1" value="1"
                                                @if ($category->active) checked @endif>
                                            active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="active"
                                                @if (!$category->active) checked @endif id="membershipRadios2"
                                                value="0">
                                            in_active
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="card-description">
                        Infomation:
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">url rewrite:</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="url_alias"
                                        value="{{ $category->url_alias }}" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">image:</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control pb-10" id="category_image"
                                        name="image_path" />
                                    @if ($category->image_path)
                                        <img src="{{$category->image_path}}" style="width: 100%; height: 240px; resize: cover"
                                            class="form-control" alt="your image" id="category_preview" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-info">update category</button>
                        </div>

                        <div class="col-md-2">
                            <a href="{{ route('admin_list_category') }}" class="btn btn-primary">list category</a>
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
                category_preview.src = URL.createObjectURL(file)
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
