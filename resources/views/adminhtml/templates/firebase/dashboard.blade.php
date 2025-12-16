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
    <div class="row">
        <div class="colo-md-2">
            <a href="{{ route('admin_firebase_setup_home_info') }}" class="btn btn-info" style="align-self: flex-start">setup home info</a>
        </div>
    </div>
    <table class="table table-striped table-inverse table-responsive">
        <thead class="thead-inverse">
            <tr>
                <th>id</th>
                <th>title</th>
                <th>image</th>
                <th>action</th>
            </tr>
        </thead>
        <tbody>
            @isset($listPaper)
                @foreach ($listPaper as $key => $item)
                    <tr>
                        <td scope="row">{{ $item['id'] }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>
                            <img @isset($item['image_path'])
                                    src="{{ $item['image_path'] }}"
                                @endisset
                                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle}"
                                alt="">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger fa-pull-right firebase_action delete_firebase"
                                data-id="{{ $key }}">del in firebase</button>

                            <a style="min-width: 110px" type="button"
                                href="{{ route('admin_edit_paper', ['paper_id' => $item['id']]) }}"
                                class="btn btn-warning fa-pull-right">view</a>
                        </td>
                    </tr>
                @endforeach

                @foreach ($papers as $paper)
                    <tr>
                        <td scope="row">{{ $paper->id }}</td>
                        <td>{{ $paper->title }}</td>
                        <td>
                            <img src="{{ $paper->getImagePath() }}"
                                class="img-fluid ${3|rounded-top,rounded-right,rounded-bottom,rounded-left,rounded-circle}"
                                alt="">
                        </td>
                        <td>
                            <button type="button" class="btn btn-info firebase_action up_firebase"
                                data-id="{{ $paper->id }}">up to
                                firebase</button>

                            <a type="button" style="min-width: 110px"
                                href="{{ route('admin_edit_paper', ['paper_id' => $paper->id]) }}"
                                class="btn btn-warning fa-pull-right">view</a>
                        </td>
                    </tr>
                @endforeach
            @endisset


        </tbody>
    </table>
    <div class="row" style="float: left">
        <div class="col-md-12 mt-20 d-flex flex-row-reverse">
            {{ $papers->links() }}
        </div>

    </div>
@endsection

@section('before_bottom_js')
@endsection

@section('after_js')
    <script type="text/javascript">
        $(document).ready(function() {
            let _token = "{{ csrf_token() }}";
            let addUrl = "{{ route('admin_firebase_upload_paper') }}";

            $('.firebase_action').on('click', function() {
                if ($(this).hasClass('up_firebase')) {
                    let paperId = $(this).attr('data-id');
                    if (paperId) {
                        Swal.fire({
                            title: 'Please Wait !',
                            html: 'data uploading', // add html attribute if you want or remove
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                        // swal.close();

                        $.ajax({
                            url: addUrl,
                            type: "POST",
                            contentType: 'application/json',
                            data: JSON.stringify({
                                _token: _token,
                                paper_id: paperId
                            }),
                            success: function(result) {
                                var data = JSON.parse(result);
                                if (data.code == 200) {
                                    console.log(data);
                                    Swal.fire({
                                        position: 'center',
                                        type: 'success',
                                        title: 'added the paper to firebase',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    $(this).parent().siblings().first().html(data.data);
                                    $(this).addClass('btn-danger').removeClass('up_firebase')
                                        .addClass('delete_firebase').html('del in firebase')
                                        .attr('data-id',
                                            data.data).off();
                                }

                                if (data.code == 400) {
                                    Swal.fire({
                                        position: 'center',
                                        type: 'error',
                                        title: 'can`t added the paper to firebase',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }

                        }.bind(this),
                        error: function(error) {
                            Swal.fire({
                                position: 'center',
                                type: 'error',
                                title: 'can`t added the paper to firebase',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        })
                    }
                } else if ($(this).hasClass('delete_firebase')) {
                    let sourceId = $(this).attr('data-id');
                    let deleteUrl = "{{ route('admin_firebase_delete_paper') }}";
                    if (sourceId) {
                        Swal.fire({
                            title: 'Please Wait !',
                            html: 'remove running !!!', // add html attribute if you want or remove
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading()
                            },
                        });
                        $.ajax({
                            url: deleteUrl,
                            type: "DELETE",
                            contentType: 'application/json',
                            data: JSON.stringify({
                                _token: _token,
                                paper_id: sourceId
                            }),
                            success: function(result) {
                                var data = JSON.parse(result);
                                if (data.code == 200) {
                                    Swal.fire({
                                        position: 'center',
                                        type: 'success',
                                        title: 'remove the paper in firebase',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                    // $(this).parent().parent().remove();
                                    $(this).removeClass('delete_firebase').removeClass(
                                            'btn-danger')
                                        .addClass('up_firebase')
                                        .addClass('btn-info').attr('data-id', data.data).html(
                                            'up to firebase');
                                    $(this).parent().siblings().first().html(data.data).off();
                                }

                                if (data.code == 400) {
                                    Swal.fire({
                                        position: 'center',
                                        type: 'error',
                                        title: 'can`t remove the paper in firebase',
                                        showConfirmButton: false,
                                        timer: 2000
                                    });
                                }
                            }.bind(this),
                            error: function(error) {

                            }
                        })
                    }
                }
            })
        })
    </script>
@endsection
