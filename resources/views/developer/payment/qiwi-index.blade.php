@extends("developer.template")

@section("title")
    Qiwi
@endsection

@section("h3")
    <h3>Qiwi</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/pay.css')}}">
    <div class="payment">
        <div>
            <form action="{!! route('qiwi-save') !!}" method="POST">
                @csrf
                <div>
                    <label for="token">Token:</label>
                    <input type="text" name="token" id="token" value="{{ isset($token) ? $token : "" }}">
                </div>
                <div>
                    <label for="webhookId">Webhook id:</label>
                    <input type="text"
                           name="webhookId"
                           id="webhookId"
                           value="{{ isset($webhookId) ? $webhookId : "" }}"
                           placeholder="Не заполнять">
                </div>
                <div>
                    <label for="publicKey">Public key:</label>
                    <input type="text" name="publicKey" id="publicKey" value="{{ isset($publicKey) ? $publicKey : "" }}">
                </div>
                <br>
                <div>
                    <input type="submit" value="Сохранить" class="button">
                </div>
            </form>
        </div>
        <br>
        <pre>Token:</pre>
        <pre><a href="https://qiwi.com/api" target="_blank">https://qiwi.com/api</a></pre>
        <br>
        <pre>Public Key:</pre>
        <pre>Для браузера "Google Chrome"</pre>
        <pre>Перейти на <a href="https://qiwi.com/p2p-admin/transfers/invoice" target="_blank">https://qiwi.com/p2p-admin/transfers/invoice</a>,
Открыть "Панель разработчика" (F12), вкладка "Network", заполнить поля, нажать "Выставить счет".
В панели разработчика в колонке "Name" выбрать "create". Вкладка "Headers", "Request Payload" -> "public_key";
        </pre>
    </div>
@endsection
