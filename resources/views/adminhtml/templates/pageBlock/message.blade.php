<div class="col-md-12">
    @if (session('not_page'))
        <?php Alert::error('not page', 'Message')->autoClose(2000); ?>
    @endif

    @if (session('error'))
        <div class="alert alert-success" id="server-message" role="alert">
            {{ session('error') }}
            <script>
                setTimeout(() => {
                    var server_message = $("#server-message");
                    if (server_message.length) {
                        $(server_message).remove()
                    }
                }, 2000);
            </script>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success" id="server-message" role="alert">
            {{ session('success') }}
            <script>
                setTimeout(() => {
                    var server_message = $("#server-message");
                    if (server_message.length) {
                        $(server_message).remove()
                    }
                }, 2000);
            </script>
        </div>
    @endif
</div>
