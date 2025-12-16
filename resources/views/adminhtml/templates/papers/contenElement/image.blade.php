@isset($item)
    <div data-type="p-picture">
        <p style="display: none">paper picture</p>
        <div class="data-content input-group">
            <span class="input-group-btn">
                <a class="btn btn-primary">
                    <i class="fa fa-picture-o"></i> Choose
                </a>
            </span>
            <input class="form-control" type="text" name="{{ $item->key }}" value="{{ $item->value }}">
            @isset($item->depend_value)
                <div class="input-group">
                    <span class="input-group-btn">description:&nbsp;</span>
                    <input class="form-control" type="text"
                        name="{{ Str::replaceFirst('images_imagex_', 'imagex_desc_', $item->key) }}"
                        value="{{ $item->depend_value }}">
                </div>
            @endisset
        </div>
        <img style="margin-top:15px;max-height:100px;" src="{{ $item->value }}">
    </div>
@else
    <div data-type="p-picture" style="border-radius: 5px">
        <div>
            <div style="display: flex; justify-content: center; align-items: center">
                {{-- https://www.flaticon.com/search?word=picture --}}
                <img src="{{ asset('/assets/adminhtml/images/picture.png') }}" alt="" style="width: auto; height: 50px">
            </div>
            <p style="text-align: center; color: aliceblue; margin-top: 5px; margin-bottom: 0px">Picture</p>
        </div>
        <div class="data-content input-group" style="display: none">
            <div>
                <span class="input-group-btn">
                    <a class="btn btn-primary">
                        <i class="fa fa-picture-o"></i> Choose
                    </a>
                </span>
            </div>
            <input class="form-control" type="text" multiple>
            <div class="input-group">
                <span class="input-group-btn">description:&nbsp;</span>
                <input class="form-control" type="text" name="">
            </div>
            <img id="holder" style="margin-top:15px;max-height:100px;">
        </div>
    </div>
@endisset
