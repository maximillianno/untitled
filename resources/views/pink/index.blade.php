@extends(env('THEME').'.layouts.site')
@section('navigation')
    {{--@include(env('THEME').'.navigation')--}}
    {!! $navigation !!}
@endsection