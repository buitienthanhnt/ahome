@if (session('success'))
    <?php
        alert()->success('server message', session('success'));
        session()->forget('success');
    ?>
@elseif ($error = session('error'))
    <?php
        alert()->warning('server mesage', session('error'));
    ?>
@endif
