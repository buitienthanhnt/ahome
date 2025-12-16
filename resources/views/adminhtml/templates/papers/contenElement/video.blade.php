@isset($item)
    <div data-type="p-video">
        <div style="border-radius: 5px; display: none">
            <div style="display: flex; justify-content: center; align-items: center;">
                <img src="{{ asset('/assets/adminhtml/images/bullhorn.png') }}" alt="" style="width: auto; height: 50px">
            </div>
            <p style="text-align: center; color: aliceblue; margin-top: 5px; margin-bottom: 0px">Video</p>
        </div>
        <div class="data-content input-group gap-2">
            <div class="input-group">
                <span class="form-text col-md-2">url || Yid:&nbsp;</span>
                <input class="form-control" placeholder="video url" name="video_url" value="{{$item->value}}" type="text">
            </div>
            <div class="input-group">
                <span class="form-text col-md-2">description:&nbsp;</span>
                <input class="form-control" type="text" placeholder="mô tả ngắn" value="{{$item->depend_value ?? ''}}" name="video_desc">
            </div>
        </div>
    </div>
@else
    <div data-type="p-picture" style="border-radius: 5px">
        <div style="border-radius: 5px">
            <div style="display: flex; justify-content: center; align-items: center;" data-type="p-video">
                <img src="{{ asset('/assets/adminhtml/images/bullhorn.png') }}" alt="" style="width: auto; height: 50px">
            </div>
            <p style="text-align: center; color: aliceblue; margin-top: 5px; margin-bottom: 0px">Video</p>
        </div>
        <div class="data-content input-group gap-2" style="display: none;">
            <div class="input-group">
                <span class="form-text col-md-2">url || Yid:&nbsp;</span>
                <input class="form-control" placeholder="video url" name="video" type="text">
            </div>

            <div class="input-group">
                <span class="form-text col-md-2">description:&nbsp;</span>
                <input class="form-control" type="text" placeholder="mô tả ngắn" name="video_desc">
            </div>
        </div>
    </div>
@endisset
