@extends('adminhtml.ahome.layouts.left-bar')

@section('title')
    ahome home list
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div>
        <a class="btn btn-sm btn-info" href="{{ url('adminhtml/ahome/home-create') }}">create new home</a>
    </div>
    <div class='p-2'>
        {{-- $__data : dung de lay tat ca cac bien duoc truyen vao 1 blade template --}}
        {!! view('components.adminhtml.pages.blocks.tableListItem', $__data) !!}
    </div>
@endsection
