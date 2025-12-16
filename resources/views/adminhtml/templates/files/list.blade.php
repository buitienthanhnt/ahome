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
    @if (session('success'))
        <?php alert()->success('server message', session('success')); ?>
    @endif

    <div class="container">
        <div class="row">
            <a href="{{ route('admin_add_file') }}">
                <button class="btn btn-info">add new file</button>
            </a>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>file name</td>
                            <td>file image</td>
                            <td>resize image</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($list_file)
                            @foreach ($list_file as $file)
                                <tr>
                                    <td>{{ $file->file_name }}</td>
                                    <td>
                                        <img src="{{ $file->file_url }}" style="width: 320px; height: 240px; border-radius: 0px" alt="">
                                    </td>
                                    <td>
                                        <img src="{{ $file->resize_url }}" style="width: 100px; height: 100px; border-radius: 0px" alt="">
                                    </td>
                                    <td>
                                        <a href="" >
                                            <button disabled class="btn btn-info">edit</button>
                                        </a>
                                        <a>
                                            <button class="btn btn-danger btn-flat delete_image" data-id="{{ $file->id }}"
                                                data-path="{{ $file->file_path }}" data-resize="{{ $file->resize_path }}">delete</button>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="row">
                <div class="col-md-12 mt-20 d-flex flex-row-reverse">
                    {{ $list_file->links() }}
                </div>

            </div>
        </div>
    </div>

    <script type="text/javascript">
        var token = "{{ csrf_token() }}";
        $('.delete_image').click(function(event) {
            var file_path = $(this).attr("data-path");
            var resize_path = $(this).attr("data-resize");
            var _id = $(this).attr("data-id");
            var url = "{{ route('admin_delete_file') }}";

            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            _token: token,
                            file_path: file_path,
                            resize_path: resize_path,
                            id: _id
                        }),
                        success: function(result) {
                            if (result) {
                                var data = JSON.parse(result);
                                if (data.code == 200) {

                                    Swal.fire({
                                        position: 'center',
                                        type: 'success',
                                        title: data.value,
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $(this).parent().parent().parent().remove();
                                } else {
                                    Swal.fire({
                                        position: 'center',
                                        type: 'warning',
                                        title: data.value,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                            }
                        }.bind(this),
                        error: function(e) {
                            // console.log("fail for request", e);
                            Swal.fire({
                                position: 'center',
                                type: 'warning',
                                title: "can not delete, please try again.",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    })
                }
            });
            return;
        });
    </script>
@endsection
