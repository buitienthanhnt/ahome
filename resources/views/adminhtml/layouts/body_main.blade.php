@extends('adminhtml.layouts.base_admin')

@section('body_main')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-sm-12">
                    {!! view('adminhtml/templates/pageBlock/message') !!}
                    <div class="home-tab">
                        @yield('body_top_tab')
                        <div class="tab-content tab-content-basic">
                            <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                                @yield('body_overview')
                                @yield('body_main_conten')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html')}} -->
        @yield('body_footer')
        <!-- partial -->
    </div>
@endsection
