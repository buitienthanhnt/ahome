 {!! view('components.adminhtml.formfields.formValidate') !!}
 <form
     @isset($method)
		method="{{ $method }}"
		@if ($method !== 'GET')
			enctype="multipart/form-data"
		@endif
	@else
		method="GET"
	@endisset
     action="{{ $action }}">

     @isset($method)
         @if (in_array($method, ['POST', 'PUT', 'DELETE']))
             @csrf
         @endif
     @endisset
     <div class="row d-flex">
         <div class="p-1 col-md-5">
              {!! view('components.adminhtml.formfields.formFieldRender', ['listAttributes' => $listAttributes,]) !!}
             <div class="justify-content-center d-flex">
                 <button type="submit" class="btn btn-primary col-md-4">Submit</button>
             </div>
         </div>
         <div class="col-md-7">
            @yield('formBaseContentRight')
         </div>
     </div>
 </form>
