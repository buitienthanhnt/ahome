@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    rule list
@endsection

@section('body_main_conten')
    <span>noi dung rule create</span>
    <div class="col-md-12">
        <a href="{{ route('admin_rule_create') }}" class="btn btn-info">create rule</a>
    </div>

    <div class="col-md-12">
        <table class="table table-light">
            <thead>
                <tr>
                    {{-- <td>select</td> --}}
                    <td>label</td>
                    <td>parent</td>
                    <td>action</td>
                </tr>
            </thead>

            <tbody>
                @isset($rules)
                    @foreach ($rules as $rule)
                        <tr>
                            {{-- <td></td> --}}
                            <td>{{ $rule->label }}</td>
                            <td>{{ $rule->getParent() ? $rule->getParent()->label : 'root' }}</td>
                            <td>
                                <a href="{{ route('admin_rule_edit', ['id' => $rule->id]) }}" class="btn btn-primary">edit</a>
                                <a href="" class="btn btn-danger show_confirm" data-id="{{ $rule->id }}">delete</a>
                            </td>
                        </tr>
                    @endforeach
                @endisset
            </tbody>
        </table>
    </div>

    <div class="row" style="float: left">
        <div class="col-md-12 mt-20 d-flex flex-row-reverse">
            {{ $rules->links() }}
        </div>

    </div>
@endsection

@section('after_js')
    <script type="text/javascript">
        var token = "{{ csrf_token() }}";
        $('.show_confirm').click(function(event) {
            var id = $(this).attr("data-id");
            var url = "{{ route('admin_rule_delete') }}";

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
                            rule_id: id
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
                                    $(this).parent().parent().remove();
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
