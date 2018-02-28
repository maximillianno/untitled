@extends(env('THEME').'.layouts.site')
@section('navigation')
    {{--@include(env('THEME').'.navigation')--}}
    {!! $navigation !!}
@endsection
@section('slider')
    {!! $sliders !!}
@endsection
@section('content')
    {!! $content !!}
@endsection
@section('bar')
    {!! $rightBar !!}
    {{--@include('pink.indexBar')--}}
@endsection
@section('footer')
    {!! $footer !!}
@endsection