@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    Rule edit
@endsection

@section('body_main_conten')
    <div class="col-md-12">
        <a href="{{ route('admin_rule_add_children', ['parent_id' => $rule->id]) }}" class="btn btn-info">add children</a>
        <a href="{{ route('admin_rule_list') }}" class="btn btn-info">list rules</a>
    </div>

    <div class="col-md-6">
        <form action="{{ route('admin_rule_insert') }}" method="post">
            @csrf
            @if ($message = session('success'))
                <?php alert()->success('server message', $message); ?>
            @elseif ($error = session('error'))
                <?php alert()->warning('server mesage', $error); ?>
            @endif

            <div class="form-group">
                <label for="name">rule name:</label>
                <input id="name" class="form-control" type="text" name="label" required
                    value="{{ $rule->label }}">
            </div>

            <div class="form-group hidden">
                <label for="name">parent: {{ isset($parent) ? $parent->label : '' }}</label>
                <input id="parent" class="form-control" type="hidden" name="parent_id"
                    value="{{ isset($parent) ? $parent->id : 0 }}">
            </div>

            <div class="form-group">
                <label for="children">childrent:</label>
                <select id="category_option" class="form-control" name="children[]" multiple="multiple">
                    @if ($children)
                        @foreach ($children as $item)
                            <option value="{{ $item->id }}" selected>{{ $item->label }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-info btn-lg" style="width: -webkit-fill-available;">save new
                    rule</button>
            </div>

        </form>
    </div>
@endsection
