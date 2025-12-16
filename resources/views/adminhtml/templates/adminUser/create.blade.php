@extends('adminhtml.layouts.body_main')

{{-- @section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection --}}
@section('admin_title')
    user create
@endsection

@section('body_footer')
    @include('adminhtml.layouts.body_footer')
@endsection

{{-- @section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection --}}

@section('body_main_conten')
    <div class="row">

        @if ($message = session('success'))
            <?php alert()->success('server message', $message); ?>
        @elseif ($error = session('error'))
            <?php alert()->warning('server mesage', $error); ?>
        @endif

        <div class="col-md-6 offset-md-3">
            <center>
                <h4>Login admin form</h4>
            </center>
            <form action="{{ route('admin_insert_user') }}" method="post">
                @csrf

                <div class="form-group">
                    <label for="admin_user">user name:</label>
                    <input id="admin_user" class="form-control" type="text" name="admin_user" required
                        placeholder="user for login">
                </div>

                <div class="form-group">
                    <label for="admin_user_email">email:</label>
                    <input id="admin_user_email" class="form-control" type="text" name="admin_email" required
                        placeholder="enter your admin email">
                </div>

                <div class="form-group">
                    <label for="admin_user_pass">password:</label>
                    <input id="admin_user_pass" class="form-control" type="password" name="admin_password" required
                        placeholder="enter your admin password">
                </div>

                <div class="form-group">
                    <label for="admin_co_user_pass">confirm password:</label>
                    <input id="admin_co_user_pass" class="form-control" type="password" name="confirm_admin_password"
                        required placeholder="enter your admin password">
                </div>

                {{-- <div class="form-group">
                    <a href="" class="text-info"><label>Forgot password?</label></a>
                    <a href="" class="text-info" style="float: right"><label>new account?</label></a>
                </div> --}}

                <div class="form-group clear">
                    <center><button type="submit" class="btn btn-info btn-sm">create admin user</button></center>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('body_left_colum')
@endsection
