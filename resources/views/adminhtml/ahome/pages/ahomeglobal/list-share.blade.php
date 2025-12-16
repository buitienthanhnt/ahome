@extends('adminhtml.ahome.layouts.left-bar')

@section('title')
    {{$title ?? ''}}
@endsection

@section('mainBody')
    <x-dashboard-chart />
    <div class='p-2'>
        {{-- $__data : dung de lay tat ca cac bien duoc truyen vao 1 blade template --}}
        {!! view('components.adminhtml.pages.blocks.tableListItem', $__data) !!}
    </div>
@endsection
