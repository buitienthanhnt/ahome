@extends('adminhtml.layouts.base')

@section('head_css')
    @include('adminhtml.layouts.head_css')
@endsection

@section('head_js')
    @include('adminhtml.layouts.head_js')
@endsection

@section('bottom_js')
    @include('adminhtml.layouts.bottom_js')
@endsection

@section('body')
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html')}} -->
        @yield('page_top_head')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">

            {{-- setting color for admin page theme --}}
            @yield('theme_setting_wrapper')

            @yield('right_sidebar')

            {{-- body left column --}}
            @yield('body_left_colum')

            <!-- main-panel start -->
            @yield('body_main')
            <!-- main-panel ends -->

        </div>
    </div>
    <!-- End custom js for this page-->
@endsection
