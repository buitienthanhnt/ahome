@extends('adminhtml.layouts.left-bar')

@section('mainBody')
    <x-dashboard-chart />
    <div class="p-2">
		{{-- the template file is layout used to for livewire component. --}}
		{{-- The current view component will be rendered in place of the $slot variable in the template above. --}}
        {{ $slot }}
    </div>
@endsection
