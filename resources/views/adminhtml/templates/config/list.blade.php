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
        <a href="{{ route('admin_create_config') }}" class="btn btn-info">create new config</a>
        <div class="col-md-10">
            <form action="{{ route('admin_update_config') }}" method="post">
                @csrf
                @if ($allOfCoreConfig)
                    @foreach ($allOfCoreConfig as $item)
                        <div class="form-group">
                            <label>{{ $item->name }}</label>
                            <div class="d-flex flex-row ">
                                <input type="text" class=" col-md-5 border rounded" name="{{ $item->name }}"
                                    aria-describedby="helpId" value="{{ $item->value }}" disabled>
                                <div class="offset-1 action flex-row">
                                    <a href="#" class="btn btn-primary edit-action"
                                        style="display: table-cell">edit</a>
                                    <a href="#" class="btn btn-warning cancel-action" style="display: none">cancel</a>
                                    <a href="#" class="btn btn-danger delete-action" style="display: table-cell"
                                        data-id="{{ $item->id }}">delete</a>
                                </div>
                            </div>
                            <small id="helpId" class="form-text text-muted">{{ $item->description }}</small>
                        </div>
                    @endforeach
                    <div class="form-group justify-content-end">
                        <input type="submit" class="btn btn-info align-content-end" value="save config">
                    </div>
                @endif
            </form>
            <div class="form-group justify-content-end">
                <button class="clear_cache btn btn-danger align-content-end" data-url="{{ route('admin_clear_cache') }}">Clear cache</button>
                <button class="clear_cache btn btn-warning align-content-end" data-url="{{ route('admin_clear_cache', ['is_refresh' => true]) }}">Update cache</button>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var url = "{{ route('admin_delete_config') }}";
            var token = "{{ csrf_token() }}";

            $('.edit-action').click(function(e) {
                e.preventDefault();

                $($(this).parent().siblings()[0]).removeAttr('disabled');
                $(this).next().css('display', 'table-cell');
                $(this).css('display', 'none');
            })

            $('.cancel-action').click(function(e) {
                e.preventDefault();
                $(this).css('display', 'none');
                $($(this).siblings()[0]).css('display', 'table-cell');
                $($(this).parent().siblings()[0]).prop('disabled', true);
            })

            $('.delete-action').click(function(e) {
                e.preventDefault();
                var config_id = $(this).attr("data-id");
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
                                // _token: token,
                                config_id: config_id
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

            })

            $(".clear_cache").click(function(e){
                var clear_cache_url = $(this).attr("data-url");
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, clear it!'
                }).then((result) => {
                    if (result.value) {
                        Swal.fire({
                            title: 'Please Wait !',
                            html: 'data uploading', // add html attribute if you want or remove
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });

                        $.ajax({
                            url: clear_cache_url,
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                // _token: token,
                            }),
                            success: function(result) {
                                if (result) {
                                    if (result.code == 200) {
                                        Swal.fire({
                                            position: 'center',
                                            type: 'success',
                                            title: result.value,
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    } else {
                                        Swal.fire({
                                            position: 'center',
                                            type: 'warning',
                                            title: result.value,
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
                                    title: "can not clear cache, please try again.",
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        })
                    }
                });

            })

        })
    </script>
@endsection
