@extends('adminhtml.ahome.layouts.left-bar')

@section('title')
    create category
@endsection

@section('formBaseContentRight')
   {!! view('components.adminhtml.formfields.formFieldRender', ['listAttributes' => $optionAttribute,]) !!}
@endsection

@section('mainBody')
    <x-dashboard-chart></x-dashboard-chart>
    <div class="px-4">
        <div class="row">
            {!! view('components.adminhtml.formfields.formBase', [
                'method' => 'POST',
                'action' => $action ?? url(''. 'adminhtml/ahome/home-register'),
                'listAttributes' => $listAttributes,
            ]) !!}

        </div>
    </div>
@endsection
