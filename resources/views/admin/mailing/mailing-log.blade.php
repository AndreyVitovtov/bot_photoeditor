@extends("admin.template")

@section("title")
    @lang('pages.log')
@endsection

@section("h3")
    <h3>@lang('pages.log')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/mailing.css')}}">
    <div class="log">
        {!! str_replace('=>' , "<i class='icon-right-small'></i>", $log) !!}
    </div>
@endsection
