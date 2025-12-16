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
        <div class="col-md-12">
            <span>noi dung cua list orders</span>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <td>name</td>
                            <td>status</td>
                            <td>email</td>
                            <td>phone</td>
                            <td>created</td>
                            <td>total</td>
                            <td>action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($orders)
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->name }}</td>
                                    <td>{{ $order->status}}</td>
                                    <td>{{ $order->email }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->total }}</td>
                                    <td>
                                        <a href="{{ route('admin_order_info', ['order_id' => $order->id]) }}">
                                            <button class="btn btn-info">info</button>
                                        </a>
                                        {{-- <a href="#">
                                            <button class="btn btn-danger btn-flat show_confirm"
                                                data-id="{{ $order->id }}">delete</button>
                                        </a> --}}
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                    </tbody>
                </table>
            </div>

            <div class="row" style="float: left">
                <div class="col-md-12 mt-20 d-flex flex-row-reverse">
                    {{ $orders->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
