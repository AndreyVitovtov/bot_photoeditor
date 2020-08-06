@extends("developer.template")

@section("title")
    WebMoney
@endsection

@section("h3")
    <h3>WebMoney</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/pay.css')}}">

    <div class="payment">
        <div>
            <form action="{!! route('webmoney-save') !!}" method="POST">
                @csrf
                <div>
                    <label for="wallet">Кошелек:</label>
                    <input type="text" name="wallet" id="wallet" value="{{ isset($wallet) ? $wallet : "" }}">
                </div>
                <div>
                    <label for="secret">Secret Key:</label>
                    <input type="text" name="secret" id="secret" value="{{ isset($secret) ? $secret : "" }}">
                </div>
                <br>
                <div>
                    <input type="submit" value="Сохранить" class="button">
                </div>
            </form>
        </div>
        <br>
        <pre>Активировать кошелек для приема средств:</pre>
        <pre><a href="https://merchant.webmoney.ru/conf/SimpleWizardConstructor.asp">https://merchant.webmoney.ru/conf/SimpleWizardConstructor.asp</a></pre>
        <br>
        <pre>Перейти на <a href="https://merchant.webmoney.ru/conf/purses.asp">https://merchant.webmoney.ru/conf/purses.asp</a></pre>
        <pre>Выбрать нужный кошелек, нажать "настроить"</pre>
        <pre>В открывшемся окне заполнить форму</pre>
        <br>
        <pre>Отметить checkbox: "Высылать на Result URL, если обеспечивается секретность"</pre>
        <br>
        <pre>Result URL: {{ url('/payment/webmoney/handler') }}</pre>
        <pre>Success URL: {{ url('/payment/webmoney/success') }}</pre>
        <pre>Fail URL: {{ url('/payment/webmoney/fail') }}</pre>
        </div>
    </div>
@endsection
