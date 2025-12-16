@extends('adminhtml.layouts.body_main')

@section('body_top_tab')
    @include('adminhtml.layouts.body_top_tab')
@endsection

@section('body_overview')
    @include('adminhtml.layouts.body_overview')
@endsection

@section('admin_title')
    permission create
@endsection

@section('head_js_after')
    <script src={{ asset('assets/adminhtml/json-hierarchical-tree-picker/jquery.simple-tree-picker.js') }}></script>
@endsection
    <link rel="stylesheet" href={{asset('assets/adminhtml/json-hierarchical-tree-picker/jquery.simple-tree-picker.css')}}>
@section('after_css')

@endsection

@section('body_main_conten')
    <style type="text/css">
        .select2-selection--multiple {
            .select2-selection__choice {
                color: color(white);
                border: 0;
                border-radius: 3px;
                padding: 6px;
                font-size: larger !important;
                font-family: inherit;
                line-height: 1;
            }
        }
    </style>

    <div class="col-md-12">
        <a href="{{ route('admin_list_permission') }}" class="btn btn-info">list permission</a>
    </div>

    <div class="col-md-6">
        <form action="{{ route('admin_insert_permission') }}" method="post">
            @csrf
            @if (session('success'))
                <?php alert()->success('server message', session('success')); ?>
            @elseif (session('error'))
                <?php alert()->warning('server mesage', session('error')); ?>
            @endif

            <div class="form-group">
                <label for="name">permission name:</label>
                <input id="name" class="form-control" type="text" name="label" required>
            </div>

            <div class="form-group">
                <p class="sel">Selected areas: <span id="selected">Nothing selected</span> </p>
                <div class="tree"></div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-info btn-lg" style="width: -webkit-fill-available;">save permission</button>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $("#rules_option").select2({
            placeholder: 'Select an option',
            tags: true
        });

        var demoTreeData = <?php echo($rules) ?>;

        // Initialize Simple Tree Picker
        // Pass it an onclick function to update the view
        // Pass it an initial selected state
        $('.tree').simpleTreePicker({
            "tree": demoTreeData,
            "onclick": function () {
                var selected = $(".tree").simpleTreePicker("display");
                $("#selected").html(!!selected.length ? selected.toString().replace(/,/g, ', ') : "Nothing Selected");
            },
            "selected": ["App-Http-Controllers-CategoryController_setupCategory"],
            "name": "room-selection-tree"
        });

        // Update view with initial state (onclick isn't called for initial selection)
        $("#selected").html($(".tree").simpleTreePicker("display").toString().replace(/,/g, ', '));
    </script>
@endsection
