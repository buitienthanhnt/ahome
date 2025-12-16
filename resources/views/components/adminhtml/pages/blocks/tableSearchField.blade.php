<div class="d-flex justify-content-between">
    @isset($title)
        <span class="text-info font-italic font-weight-bold text-2xl">
            {{ $title }}
        </span>
    @endisset
    <div class="d-flex">
        <form action="" method="get">
            <div class="form-group d-flex" style="column-gap: 8px">
                <button type="submit" class="btn btn-link">
                    <i class="material-icons" style="font-size: 36px; transform: rotateZ(90deg)">search</i>
                </button>
                <input type="text" name="q" class="form-control" placeholder="search" id="fast-search">
            </div>
        </form>
    </div>
</div>
