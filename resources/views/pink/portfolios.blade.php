@extends(env('THEME').'.layouts.site')
@section('navigation')
    {{--@include(env('THEME').'.navigation')--}}
    {!! $navigation !!}
@endsection

@section('content')
    {!! $content !!}
@endsection

@section('footer')
    {!! $footer !!}
@endsection