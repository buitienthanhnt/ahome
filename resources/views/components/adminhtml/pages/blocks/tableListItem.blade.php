@if (count($lists instanceof \Illuminate\Pagination\LengthAwarePaginator ? $lists->items() : $lists))
    <table class="table">
        <thead>
            <tr>
                @foreach ($attributes as $attr)
                    <th scope="col">{{ __('attr.' . $attr) }}</th>
                @endforeach
                @isset($actions)
                    <th scope="col">action</th>
                @endisset
            </tr>
        </thead>
        <tbody>
            @foreach ($lists instanceof \Illuminate\Pagination\LengthAwarePaginator ? $lists->items() : $lists as $item)
                <tr>
                    @for ($i = 0; $i < count($attributes); $i++)
                        @if (isset($item::FORM_FIELDS[$attributes[$i]]) &&
                                in_array($item::FORM_FIELDS[$attributes[$i]]['type'], [
                                    \App\Models\Types\FormInterface::TYPE_FILE,
                                    \App\Models\Types\FormInterface::TYPE_IMAGE_CHOOSE,
                                ]))
                            <td>
                                <img src="{{ $item->{$attributes[$i]} }}" class="rounded-circle" style="object-fit: cover"
                                    alt="none image" width='90px' height="90px" />
                            </td>
                        @else
                            <td>{{ $item->{$attributes[$i]} }}</td>
                        @endif
                    @endfor
                    <th scope="row">
                        <div class="d-flex" style="column-gap: 16px">
                            @isset($actions)
                                @foreach ($actions as $action)
                                    @switch($action['type'])
                                        @case('delete')
                                            {!! view('components.adminhtml.formfields.deleteBtn', [...$action, 'id' => $item->id]) !!}
                                        @break

                                        @default
                                            {!! view('components.adminhtml.formfields.redirectBtn', [...$action, 'id' => $item->id]) !!}
                                    @endswitch
                                @endforeach
                            @endisset
                        </div>
                    </th>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p class="text-2xl font-bold text-danger">khong co thong tin hien thi!</p>
@endif

@if ($lists instanceof \Illuminate\Pagination\LengthAwarePaginator)
    {{ $lists->links('components.adminhtml.pages.links') }}
@endif

@section('body-afjs')
    {!! view('components.adminhtml.pages.blocks.deleteSwalAction') !!}
@endsection
