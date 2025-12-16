@yield('before_bottom_js')
<script>
    $.ajaxSetup({
       headers: {
           url: '{{url("adminhtml")}}',
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
   });
</script>
<!-- plugins:js -->
<script src={{asset('assets/adminhtml/vendors/js/vendor.bundle.base.js')}}></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src={{asset('assets/adminhtml/vendors/chart.js/Chart.min.js')}}></script>
<script src={{asset('assets/adminhtml/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}></script>
<script src={{asset('assets/adminhtml/vendors/progressbar.js/progressbar.min.js')}}></script>

<!-- End plugin js for this page -->
<!-- inject:js -->
<script src={{asset('assets/adminhtml/js/off-canvas.js')}}></script>
<script src={{asset('assets/adminhtml/js/hoverable-collapse.js')}}></script>
<script src={{asset('assets/adminhtml/js/template.js')}}></script>
<script src={{asset('assets/adminhtml/js/settings.js')}}></script>
<script src={{asset('assets/adminhtml/js/todolist.js')}}></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src={{asset('assets/adminhtml/js/dashboard.js')}}></script>
<script src={{asset('assets/adminhtml/js/Chart.roundedBarCharts.js')}}></script>

@yield('after_js')
