@foreach($items as $item)
            <li {{ (\Illuminate\Support\Facades\URL::current() == $item->url()) ? 'class=active' : '' }}>
                <a href="{{$item->url()}}">{{$item->title}}</a>
                    @if($item->hasChildren())
                        <ul class="sub-menu">
                            @include(env('THEME').'.customMenuItems', ['items' => $item->children()])
                        </ul>
                    @else
                    @endif
            </li>

@endforeach