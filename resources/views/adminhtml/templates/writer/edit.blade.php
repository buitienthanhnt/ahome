@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('body_main_conten')
    @if($message = session('success'))
        <?php alert()->success('server message', $message);?>
    @elseif ($error = session("error"))
        <?php alert()->error('server mesage',$error);?>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin_update_writer', ['writer_id' => $writer->id]) }}" method="post" enctype="multipart/form-data" >
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label pr-0 text-success">Writer email:</label>
                                <div class="col-sm-9 pl-0">
                                    <input type="email" class="form-control" name="email" value="{{ $writer->email }}" required placeholder="input an email"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label text-warning">active</label>
                                <div class="col-sm-4">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="active"
                                                id="membershipRadios1" value="1" {{ $writer->active == true ? "checked='checked'" : ""}}>
                                            active
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" name="active"
                                                id="membershipRadios2" value="0" {{ $writer->active == false ? "checked='checked'" : ""}}>
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
                                <label class="col-sm-3 pr-0 col-form-label">Writer Name:</label>
                                <div class="col-sm-9 pl-0">
                                    <input type="text" class="form-control" name="name" value="{{ $writer->name }}" required placeholder="name of writer"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label pr-0 text-primary">Writer alias:</label>
                                <div class="col-sm-9 pl-0">
                                    <input type="text" class="form-control" name="alias" value="{{ $writer->name_alias }}" placeholder="input alias"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 text-info" for="my-input">address:</label>
                                <div class="col-sm-9">
                                    <input id="my-input" class="form-control" type="text" value="{{ $writer->address }}" name="address"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3 text-danger" for="my-input">phone number:</label>
                                <div class="col-sm-9">
                                    <input id="my-input" class="form-control" type="number" value="{{ $writer->phone }}" name="phone"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                              <label class="col-sm-3" for="date_of_birth">date of birth:</label>
                              <div class="col-sm-9">
                                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="{{ date('Y-m-d', strtotime($writer->date_of_birth)) }}" placeholder="date of birth" required/>
                              </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-3" for="image_post">image_post:</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" id="image_post"
                                        name="image_post" />
                                    <img src="{{ $writer->image_path }}" style="width: 100%; height: 240px; resize: cover; {{$writer->image_path ? "display: conten" : "display: none"}} "
                                        class="form-control" alt="your image" id="category_preview" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="offset-sm-4 col-sm-4 float-right">
                            <button type="submit" class=" form-control btn btn-info">save new writer</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        image_post.onchange = evt => {
            const [file] = image_post.files
            if (file) {
                $(category_preview).show();
                category_preview.src = URL.createObjectURL(file)
            }else{
                $(category_preview).hide();
            }
        }
    </script>
@endsection
