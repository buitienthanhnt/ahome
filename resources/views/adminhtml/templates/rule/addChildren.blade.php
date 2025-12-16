@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    add children rule
@endsection

@section('body_main_conten')
    <style type="text/css">

    </style>
    <div class="col-md-12">
        <a href="{{ route('admin_rule_list') }}" class="btn btn-info">list rules</a>
    </div>
    <div class="col-md-6">
        <form action="{{ route('admin_rule_add_children') }}" method="post">
            @csrf
            @if ($message = session('success'))
                <?php alert()->success('server message', $message); ?>
            @elseif ($error = session('error'))
                <?php alert()->warning('server mesage', $error); ?>
            @endif

            <div class="form-group">
                <label for="rule_parent">Parent: <span class="text text-info">{{ $parent->label }}</span></label>
                <input id="rule_parent" hidden class="form-control" type="number" name="parent_id"
                    value="{{ $parent->id }}">
            </div>

            <div class="form-group">
                <label for="name">rule name:</label>
                <input id="name" class="form-control" type="text" name="label" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-info btn-lg" style="width: -webkit-fill-available;">add
                    children</button>
            </div>
        </form>
    </div>

    <script>
        $("#rules_option").select2({
            placeholder: 'Select an option',
            tags: true,
            tokenSeparators: [',', ' ']
        });
    </script>
@endsection
