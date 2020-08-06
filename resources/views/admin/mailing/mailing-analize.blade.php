@extends("admin.template")

@section("title")
    @lang('pages.analize')
@endsection

@section("h3")
    <h3>@lang('pages.analize')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/mailing.css')}}">
    <script>
        let texts = {};

        texts.mailing_messages_sent = '@lang('pages.mailing_messages_sent')';
        texts.mailing_count_messages = '@lang('pages.mailing_count_messages')';
        texts.mailing_successfully = '@lang('pages.mailing_successfully')';
        texts.mailing_not_successful = '@lang('pages.mailing_not_successful')';

        let data = {!! json_encode($data) !!};

            // console.log(data);
            google.load('visualization', '1.0', {'packages': ['corechart'] });
        // Установка функции обратного вызова для запуска отрисовки,
        // по окончанию загрузки API визуализации.
        google.setOnLoadCallback(function() {
            drawChartAnalizeMailingLog(data, texts);
        });
    </script>

    <div class="chart_analize_mailing_log">
        <div id="chart_div"></div>
    </div>
    <br>
    <hr>
    <br>
    <div>
        <form action="/admin/mailing/mark-inactive-users" method="POST">
            @csrf
            <input type="submit" value="@lang('pages.analize_mark_inactive_users')" class="button">
        </form>
    </div>
@endsection
