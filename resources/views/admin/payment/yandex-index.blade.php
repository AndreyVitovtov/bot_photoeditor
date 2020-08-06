@extends("admin.template")

@section("title")
    @lang('pages.yandex_money')
@endsection

@section("h3")
    <h3>@lang('pages.yandex_money')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/pay.css')}}">

    <div class="payment">
        <div>
            <form action="{!! route('admin-yandex-save') !!}" method="POST">
                @csrf
                <div>
                    <label for="wallet">@lang('pages.payment_wallet'):</label>
                    <input type="text" name="wallet" id="wallet" value="{{ isset($wallet) ? $wallet : "" }}">
                </div>
                <div>
                    <label for="secret">Secret key:</label>
                    <input type="text" name="secret" id="secret" value="{{ isset($secret) ? $secret : "" }}">
                </div>
                <br>
                <div>
                    <input type="submit" value="@lang('pages.payment_save')" class="button">
                </div>
            </form>
        </div>
        <br>
        <pre>Подключить http уведомления:</pre>
        <pre><a href="https://money.yandex.ru/myservices/online.xml" target="_blank">https://money.yandex.ru/myservices/online.xml</a></pre>
        <pre>Адрес: {{ url('/payment/yandex/handler') }}</pre>
        <pre>Secret key: Кнопка "Показать секрет"</pre>
    </div>
@endsection
