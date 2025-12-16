@extends('adminhtml.layouts.body_main')

{{-- @section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection --}}

@section('admin_title')
    user edit
@endsection

@section('body_footer')
    @include('adminhtml.layouts.body_footer')
@endsection

{{-- @section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection --}}

@section('after_css')
    <style type="text/css">
        .select2-selection--multiple {
            .select2-selection__choice {
                color: color(white);
                border: 0;
                border-radius: 3px;
                padding: 6px;
                font-size: larger !important;
                font-family: inherit;
                line-height: 1;
            }
        }
    </style>
@endsection

@section('body_main_conten')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <center>
                <h4>edit admin user</h4>
            </center>

            @if (session('success'))
                <?php alert()->success('server message', session('success')); ?>
            @elseif (session('error'))
                <?php alert()->warning('server mesage', session('error')); ?>
            @endif

            <form action="{{ route('admin_update_user', ['user_id' => $user->id]) }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="admin_user">User name:</label>
                            <input id="admin_user" class="form-control" type="text" name="admin_user" required
                                value="{{ $user->name }}" placeholder="user for login">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <label for="category" class="col-sm-2">Permissions:</label>
                        <div class="form-group">
                            <select id="permission_values" class="form-control" name="permission_values[]"
                                multiple="multiple">
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->id }}" @if (in_array($permission->id, $userPermissions)) selected @endif>
                                        {{ $permission->label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- <div class="form-group">
                    <label for="admin_user_email">email:</label>
                    <input id="admin_user_email" class="form-control" type="text" name="admin_email" required value=""
                        placeholder="enter your admin email">
                </div> --}}

                {{-- <div class="form-group">
                    <label for="admin_user_pass">password:</label>
                    <input id="admin_user_pass" class="form-control" type="password" name="admin_password" required
                        placeholder="enter your admin password">
                </div> --}}

                {{-- <div class="form-group">
                    <label for="admin_co_user_pass">confirm password:</label>
                    <input id="admin_co_user_pass" class="form-control" type="password" name="confirm_admin_password" required
                        placeholder="enter your admin password">
                </div> --}}

                {{-- <div class="form-group">
                    <a href="" class="text-info"><label>Forgot password?</label></a>
                    <a href="" class="text-info" style="float: right"><label>new account?</label></a>
                </div> --}}

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group clear">
                            <center><button type="submit" class="btn btn-info btn-sm">save update adminUser</button>
                            </center>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $("#permission_values").select2({
            placeholder: 'Select an option',
            tags: true,
            tokenSeparators: [',', ' ']
        });
    </script>
@endsection

@section('body_left_colum')
@endsection
