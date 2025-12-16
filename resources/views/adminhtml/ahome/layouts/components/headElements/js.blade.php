@include('adminhtml.ahome.layouts.components.headElements.beJs')

{{-- import fontawesome js library --}}
<script src="/source/adminhtml/js/fontawesome.js"></script>
{{-- import sweetalert2 library: https://sweetalert2.github.io/#usage --}}
<script src="/source/adminhtml/js/sweetalert2@11.js"></script>
{{-- import jquery library --}}
<script src="/source/adminhtml/js/jquery-3.7.1.min.js"></script>
{{-- <script src="/source/adminhtml/js/jquery-ui/jquery-ui.min.js"></script> --}}
{{-- import select2 library https://select2.org/ --}}
<script src="/source/adminhtml/js/select2.min.js"></script>
{{-- insert bootstrap support --}}
<script src="/source/adminhtml/js/popper.min.js"></script>
<script src="/source/adminhtml/js/bootstrap.min.js"></script>
<script src="/source/adminhtml/js/tinymce/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
{{-- source library for charts --}}
{{-- <script src="/source/adminhtml/js/plugins/perfect-scrollbar.min.js"></script>
<script src="/source/adminhtml/js/plugins/smooth-scrollbar.min.js"></script>
<script src="/source/adminhtml/js/plugins/chartjs.min.js"></script> --}}

{{-- insert filemanager support --}}
<script src="/vendor/laravel-filemanager/js/filemanager.min.js"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
{{-- insert knockoutJs library --}}
<script src="/source/adminhtml/js/knockout.js"></script>
{{-- insert underScore library --}}
<script src="/source/adminhtml/js/underscore.js"></script>
{{-- insert require library chay khi dat o head tag --}}
<script src="/source/adminhtml/js/require.js" data-main="/source/adminhtml/js/requireMain"></script>

<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>

@include('adminhtml.ahome.layouts.components.headElements.afJs')

{{-- jquery ajax setup: https://laravel.com/docs/12.x/csrf#csrf-x-csrf-token --}}
<script type="text/javascript">
    var filemanager_url_base = "{{ url('adminhtml/file/manager') }}";
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    })

    require(['plugins/perfect-scrollbar.min', 'plugins/smooth-scrollbar.min',], function(){})
</script>
