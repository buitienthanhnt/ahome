{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
@render(App\ViewBlock\AdminGlobalVar::class)

<script src={{asset('assets/frontend/js/vendor/jquery-1.12.4.min.js')}}></script>
<script src={{asset('assets/frontend/js/popper.min.js')}}></script>
<script src={{asset('assets/frontend/js/bootstrap.min.js')}}></script>
<script src={{ asset('assets/select2/dist/js/select2.js') }}></script>
{{-- <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script> --}}
<script src={{asset('assets/frontend/js/sweetalert2.all.js')}}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>

@yield('head_js_after')