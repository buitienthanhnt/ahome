  @if (session('message'))
      @php
          // alert()->info('message', session('message'));
          toast(session('message') ?: 'please login first', 'warning');
      @endphp
  @endif
  @include('sweetalert::alert')
