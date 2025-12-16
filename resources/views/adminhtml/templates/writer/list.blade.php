@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    writer list
@endsection


@section('body_main_conten')
    @if (session('success'))
        <?php alert()->success('server message',  session('success')); ?>
    @endif
    <div class="container">
        <div class="row">
            <a href="{{ route('admin_create_writer') }}"  class="col-md-2 btn btn-info">
                create writer
            </a>

            <a href="{{ route('admin_firebase_upload_writer') }}"  class="col-md-2 btn btn-info">
                upload writer to firebase
            </a>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>name</td>
                            <td>email</td>
                            <td>alias</td>
                            <td>image</td>
                            <td>active</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($all_writer)
                            @foreach ($all_writer as $writer)
                                <tr>
                                    <td>{{ $writer->name }}</td>
                                    <td>{{ $writer->email }}</td>
                                    <td>{{ $writer->name_alias }}</td>
                                    <td>
                                        <img src="{{ $writer->image_path }}" style="width: 100px; height: 100px;"
                                            alt="">
                                    </td>
                                    <td>{{ $writer->active ? 'active' : 'inactive' }}</td>
                                    <td>
                                        <a class="btn btn-info" href="{{ route('admin_edit_writer', ['writer_id' => $writer->id]) }}">
                                            edit
                                        </a>
                                        <a href="" class="btn btn-danger btn-flat show_confirm" data-id="{{ $writer->id }}">
                                            delete
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
                    {{ $all_writer->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('after_js')
    <script type="text/javascript">
        var token = "{{ csrf_token() }}";
        $('.show_confirm').click(function(event) {
            var id = $(this).attr("data-id");
            var url = "{{ route('admin_delete_writer') }}";

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
                            writer_id: id
                        }),
                        success: function(result) {
                            // console.log(result, this);
                            if (result) {
                                var data = JSON.parse(result);
                                if (data.code == 200) {
                                    // Swal.fire({
                                    //     title: 'message from server',
                                    //     text: data.value,
                                    //     type: 'success',
                                    //     showConfirmButton: false,
                                    //     timer: 2000
                                    // });
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
