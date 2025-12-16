@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    firebase dashboard
@endsection

@section('after_css')
    <style>
        .table th,
        .table td {
            white-space: normal;
        }
    </style>
@endsection

@section('head_js_after')
@endsection

@section('body_main_conten')
    <div class="colo-md-2">
        <span id="uploadHomeFedault" class="btn btn-info" style="align-self: flex-start">upload default home info</span>
    </div>
@endsection

@section('before_bottom_js')
@endsection

@section('after_js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#uploadHomeFedault').on('click', function() {
                Swal.fire({
                    title: 'Please Wait !',
                    html: 'data uploading', // add html attribute if you want or remove
                    allowOutsideClick: false,
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                });

                $.ajax({
                    url: 'uploadHomeInfo',
                    type: "POST",
                    contentType: 'application/json',
                    data: JSON.stringify({
                        _token: $('meta[name="csrf-token"]').attr('content'),
                    }),
                    success: function(result) {
                        var data = JSON.parse(result);
                        if (data.code == 200) {
                            Swal.fire({
                                position: 'center',
                                type: 'success',
                                title: 'uploaded the info data to firebase',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                        if (data.code == 400) {
                            Swal.fire({
                                position: 'center',
                                type: 'error',
                                title: 'can`t upload the info data to firebase',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                }.bind(this),
                error: function(error) {
                    Swal.fire({
                        position: 'center',
                        type: 'error',
                        title: 'can`t upload the info data to firebase',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                })
            })
        })
    </script>
@endsection
