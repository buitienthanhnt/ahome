@isset($item)
    <div style="" data-type="p-html">
        <p>paper html content</p>
        <div class="data-content">
            <textarea id="conten" name="{{ $item->key }}" class="form-control" style="height: 720px;">
            {!! $item->value !!}
        </textarea>
        </div>
    </div>
@else
    <div style="border-radius: 5px" data-type="p-html">
        <div class="demo-content-source">
            <div style="display: flex; justify-content: center; align-items: center">
                {{-- https://www.flaticon.com/search?word=picture --}}
                <img src="{{ asset('/assets/adminhtml/images/content-management.png') }}" alt="" style="width: auto; height: 50px">
            </div>
            @isset($conten)
                <style>
                    .demo-content-source img {
                        max-width: 30% !important;
                        width: auto !important;
                        height: auto !important;
                    }
                </style>
                {!! $conten !!}
            @else
                <p style="text-align: center; color: aliceblue; margin-top: 5px; margin-bottom: 0px">Paper html content</p>
            @endisset
        </div>
        <div class="data-content">
            <textarea id="conten" name="{{ App\Models\PaperContentInterface::TYPE_CONTENT }}" class="form-control" style="height: 720px; display: none">
                @isset($conten){{ $conten }}@endisset
            </textarea>
        </div>
    </div>
@endisset
