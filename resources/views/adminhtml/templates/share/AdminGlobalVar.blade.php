<script type="text/javascript">
@foreach ($vars as $key => $var)
    var {!! $key !!} = '{!! $var !!}';
@endforeach
</script>