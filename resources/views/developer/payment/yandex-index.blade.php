@extends("developer.template")

@section("title")
    Яндекс Деньги
@endsection

@section("h3")
    <h3>Яндекс Деньги</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/pay.css')}}">

    <div class="payment">
        <div>
            <form action="{!! route('yandex-save') !!}" method="POST">
                @csrf
                <div>
                    <label for="secret">Secret key:</label>
                    <input type="text" name="secret" id="secret" value="{{ isset($secret) ? $secret : "" }}">
                </div>
                <div>
                    <label for="wallet">Номер кошелька:</label>
                    <input type="text" name="wallet" id="wallet" value="{{ isset($wallet) ? $wallet : "" }}">
                </div>
                <br>
                <div>
                    <input type="submit" value="Сохранить" class="button">
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
