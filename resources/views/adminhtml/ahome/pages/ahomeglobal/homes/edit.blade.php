@extends('adminhtml.ahome.layouts.left-bar')

@section('title')
   update home ahome
@endsection

@section('formBaseContentRight')
   {!! view('components.adminhtml.formfields.formFieldRender', ['listAttributes' => $optionAttribute,]) !!}
@endsection

@section('mainBody')
    <x-dashboard-chart></x-dashboard-chart>
	<a href="/adminhtml/ahome/room-create/{{$home->id}}" class="btn btn-info">create new room</a>
    <div class="px-4">
        <div class="row">
            {!! view('components.adminhtml.formfields.formBase', [
                'method' => 'POST',
                'action' => $action,
                'listAttributes' => $listAttributes,
            ]) !!}

        </div>
    </div>
@endsection
