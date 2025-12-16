@if ($errors->any())
    <div class="alert alert-danger" id="form-validate-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-dark font-semibold text-lg">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <script>
        $(document).ready(function() {
            var errors = $('#form-validate-error');
            if (errors) {
                setTimeout(() => {
                    errors.remove();
                }, 3000);
            }
        })
    </script>
@endif
