@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    paper list
@endsection

@section('after_css')
    <style>
        .table th,
        .table td {
            line-height: inherit
        }
    </style>
@endsection

@section('head_js_after')
@endsection

@section('body_main_conten')
    <div class="row">
        <div class="col-md-8">
            <span class="text-uppercase">thông tin đơn hàng:</span>
            <div class="card mt-2">
                <div class="card-body">
                  <h5 class="card-title">người mua: {{ $order->name }}</h5>
                  <h6 class="card-subtitle mb-2 text-muted">email: {{ $order->email }}</h6>
                  <h6 class="card-subtitle mb-2 text-muted">sdt: {{ $order->phone }}</h6>
                  <h6 class="card-subtitle mb-2 text-muted">ngày tạo: {{ $order->created_at }}</h6>
                  <p class="card-text">ghi chú: Hiện tại chưa hỗ trợ</p>

                  <ul class="list-group">
                    @foreach ($order->orderItems() as $item)
                    <li class="list-group-item">
                        <div>
                            <p>Tên sản phẩm: {{$item->title}}</p>
                            <p>Giá: {{$item->price}}</p>
                            <p>Số lượng: {{$item->qty}}</p>
                        </div>
                    </li>
                    @endforeach
                  </ul>

                    <a href="#" class="text-success mr-5">Trạng thái: {{ $order->status }}</a>
                </div>
              </div>
        </div>
    </div>
@endsection
