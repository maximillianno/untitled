@if($menu)
    <div class="menu classic">
        <ul id="nav" class="menu">
            {{--передаем корневые элементы в макет--}}
            @include(env('THEME').'.customMenuItems',['items' => $menu->roots()])
        </ul>
    </div>


@endif

