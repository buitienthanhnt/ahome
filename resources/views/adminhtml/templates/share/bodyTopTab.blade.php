@section('body_top_tab')
    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab"
                    href={{ asset('assets/adminhtml/#overview') }} role="tab" aria-controls="overview"
                    aria-selected="true">Demographics</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-success" id="list-cate-tab" href={{ route('admin_list_category') }} role="tab"
                    aria-selected="false">list category</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-info" id="more-tab" href={{ route('admin_setup_category') }} role="tab"
                    aria-selected="false">setup category top</a>
            </li>
            <li class="nav-item">
                <a class="nav-link border-0 text-warning" id="new-paper-url-tab" href={{ route('admin_new_by_url') }} role="tab"
                    aria-selected="false">paper by url</a>
            </li>
        </ul>
        <div>
            <div class="btn-wrapper">
                <a href={{ asset('assets/adminhtml/#') }} class="btn btn-otline-dark align-items-center"><i
                        class="icon-share"></i>
                    Share</a>
                <a href={{ asset('assets/adminhtml/#') }} class="btn btn-otline-dark"><i class="icon-printer"></i> Print</a>
                <a href={{ asset('assets/adminhtml/#') }} class="btn btn-primary text-white me-0"><i
                        class="icon-download"></i>
                    Export</a>
            </div>
        </div>
    </div>
@endsection
