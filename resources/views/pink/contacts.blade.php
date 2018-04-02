@extends(env('THEME').'.layouts.site')
@section('navigation')
    {{--@include(env('THEME').'.navigation')--}}
    {!! $navigation !!}
@endsection

@section('content')
    {!! $content !!}
@endsection
@section('bar')
    {!! $leftBar !!}
    {{--@include('pink.indexBar')--}}
@endsection
@section('footer')
    {!! $footer !!}
@endsection