<div class="row">
    <div class="col-sm-12">
        <div class="statistics-details d-flex align-items-center justify-content-between">
            <div>
                <p class="statistics-title">lượt xem</p>
                <h3 class="rate-percentage">{{ $list_data['paper_view_count'] }}</h3>
                <p class="text-success d-flex"><i class="mdi mdi-menu-up"></i><span>avg:
                        {{$list_data['active_page'] ? floor($list_data['paper_view_count'] / $list_data['active_page']) : 0 }} views/page</span>
                </p>
            </div>
            <div>
                <p class="statistics-title">Số bài viết</p>
                <h3 class="rate-percentage">{{ $list_data['active_page'] }}</h3>
                <p class="text-danger d-flex"><i
                        class="mdi mdi-menu-down"></i><span>{{$list_data['all_page'] ? number_format(($list_data['active_page'] / $list_data['all_page']) * 100, 2) : 0 }}</span>
                </p>
            </div>
            <div>
                <p class="statistics-title">trên firebase</p>
                <h3 class="rate-percentage">{{ $list_data['paper_in_firebase'] }}</h3>
                <p class="text-danger d-flex"><i
                        class="mdi mdi-menu-down"></i><span>{{$list_data['all_page'] ? number_format(($list_data['paper_in_firebase'] / $list_data['all_page']) * 100, 2) : 0 }}%</span>
                </p>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">đăng ký</p>
                <h3 class="rate-percentage">{{ $list_data['user_count'] }}</h3>
                <p class="text-danger d-flex"><i class="mdi mdi-menu-down"></i><span>68.8</span></p>
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Avg. Time on Site</p>
                <h3 class="rate-percentage">2m:35s</h3>
                <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
            </div>
            {{-- <div class="d-none d-md-block">
                <p class="statistics-title">Avg. Time on Site</p>
                <h3 class="rate-percentage">2m:35s</h3>
                <p class="text-success d-flex"><i class="mdi mdi-menu-down"></i><span>+0.8%</span></p>
            </div> --}}
        </div>
    </div>
</div>
