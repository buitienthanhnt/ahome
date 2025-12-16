@isset($item)
    <div data-type="p-price">
        <div class="data-content form-group row">
            <label for="price" class="col-sm-2">Price:</label>
            <div class="col-sm-8">
                <input type="number" name="{{ $item->key }}" placeholder="nghìn vnđ" class="form-control" min="0"
                    value="@isset($item){{ $item->value }}@endisset">
            </div>
        </div>
    </div>
@else
    <div data-type="p-price" style="border-radius: 5px">
        <div>
            <div style="display: flex; justify-content: center; align-items: center">
                <img src="{{ asset('/assets/adminhtml/images/price-tag.png') }}" alt="" style="width: auto; height: 50px">
            </div>
            <p style="text-align: center; color: aliceblue; margin-top: 5px; margin-bottom: 0px">Product price</p>
        </div>
        <div class="data-content form-group row" style="display: none">
            <label for="price" class="col-sm-2">Price:</label>
            <div class="col-sm-8">
                <input type="number" name="{{ App\Models\PaperContentInterface::TYPE_PRICE }}" id="price" placeholder="nghìn vnđ" class="form-control"
                    min="0"
                    value="@isset($price)
                   {{ $price }}
                   @endisset">
            </div>
        </div>
    </div>
@endisset
