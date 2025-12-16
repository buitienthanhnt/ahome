<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('admin_title')</title>
    <!-- plugins:css -->
    @yield('head_css')
    <!-- endinject -->
    <link rel="shortcut icon" href={{ asset('assets/adminhtml/images/favicon.png') }} />
    @yield('head_js')
</head>
<body>
    @include('sweetalert::alert')
    @yield('body')
    @yield('bottom_js')
</body>
</html>
