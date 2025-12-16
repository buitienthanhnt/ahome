
@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    permission list
@endsection

@section('head_js_after')
    <script src={{ asset('assets/adminhtml/json-hierarchical-tree-picker/jquery.simple-tree-picker.js') }}></script>
@endsection
    <link rel="stylesheet" href={{asset('assets/adminhtml/json-hierarchical-tree-picker/jquery.simple-tree-picker.css')}}>
@section('after_css')

@endsection

@section('body_main_conten')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin_create_permission') }}" class="btn btn-info">create permission</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-light">
                    <thead class="thead-light">
                        <tr>
                            <th>id</th>
                            <th>label</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->label }}</td>
                            <td>
                                <a href="{{ route('admin_detail_permission', ['permission_id' => $permission->id]) }}" class="btn btn-info">edit</a>
                                <a href="" class="">
                                    <button class="btn btn-danger btn-flat show_confirm" data-id="{{ $permission->id }}">delete</button>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" style="float: left">
            <div class="col-md-12 mt-20 d-flex flex-row-reverse">
                {{ $permissions->links() }}
            </div>

        </div>

    </div>
@endsection

@section('after_js')
    <script type="text/javascript">
        var token = "{{ csrf_token() }}";
        $('.show_confirm').click(function(event) {
            var permission_id = $(this).attr("data-id");
            var url = "{{ route('admin_delete_permission') }}";

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
                            permission_id: permission_id
                        }),
                        success: function(result) {
                            // console.log(result, this);
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
