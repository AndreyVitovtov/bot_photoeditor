@extends("admin.template")

@section("title")
    @lang('pages.statistics')
@endsection

@section("h3")
    <h3>@lang('pages.statistics')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/statistics.css')}}">
    <script>
        let statistics = {};
        let statistics2 = {};
        let statistics3 = {};
        let statistics4 = {};

        let texts = {};

        texts.count_users_visits = "@lang('pages.statistics_count_users_visits')";
        texts.date = "@lang('pages.statistics_date')";
        texts.count = "@lang('pages.statistics_count')";
        texts.users_count = "@lang('pages.statistics_users_count')";
        texts.count_users_country = "@lang('pages.statistics_count_users')";
        texts.count_users_messengers = "@lang('pages.statistics_count_users_messengers')";


        statistics.data = {!! json_encode($data) !!}
        console.log(statistics);

        statistics2.data = {!! json_encode($countries) !!}
            statistics2.title = "@lang('pages.statistics_count_users')";
        console.log(statistics2);

        statistics3.data = {!! json_encode($messengers) !!}
        console.log(statistics3);

        statistics.data = {!! json_encode($data) !!}
        console.log(statistics);
        {{--statistics4.data = {!! json_encode($access) !!}--}}
        {{--    statistics4.title = "@lang('pages.statistics_access')";--}}
        {{--console.log(statistics4);--}}

        google.load('visualization', '1.0', {'packages': ['corechart'] });
        // Установка функции обратного вызова для запуска отрисовки,
        // по окончанию загрузки API визуализации.
        google.setOnLoadCallback(function() {

            drawChart(statistics, statistics2, statistics3, texts);
        });
    </script>

    <div class="chart_statistics_2">
        <div id="chart_div_2"></div>
    </div>
    <div class="chart_statistics_2">
        <div id="chart_div_3"></div>
    </div>
    <div class="chart_statistics_2 w100">
        <div id="chart_div"></div>
    </div>
    <div class="chart_statistics_2">
        <div id="chart_div_4"></div>
    </div>




@endsection
