@extends('adminhtml.layouts.body_main')

{{-- @section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection --}}

@section('body_footer')
    @include('adminhtml.layouts.body_footer')
@endsection

{{-- @section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection --}}

@section('body_main_conten')
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <center>
                <h4>Thông tin đăng nhập</h4>
            </center>
            <form action="{{ route('admin_login_post') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="admin_user">user name:</label>
                    <input id="admin_user" class="form-control" type="text" name="admin_user" required
                        placeholder="enter your admin user name">
                </div>

                <div class="form-group">
                    <label for="user_pass">password:</label>
                    <input id="user_pass" class="form-control" type="password" name="admin_password" required
                        placeholder="enter your admin password">
                </div>

                <div class="form-group">
                    <a href="" class="text-info"><label>Forgot password?</label></a>
                    <a href="{{ route('admin_create_user') }}" class="text-info" style="float: right"><label>new
                            account?</label></a>
                </div>

                <div class="form-group clear">
                    <center>
                        <button type="submit" class="btn btn-info btn-sm" style="width: 50%">login into</button>
                    </center>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('body_left_colum')
@endsection
