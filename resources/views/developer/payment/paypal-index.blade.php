@extends("developer.template")

@section("title")
    PayPal
@endsection

@section("h3")
    <h3>PayPal</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/pay.css')}}">

    <div class="payment">
        <div>
            <form action="{!! route('paypal-save') !!}" method="POST">
                @csrf
                <div>
                    <label for="facilitator">Facilitator:</label>
                    <input type="text"
                           name="facilitator"
                           id="facilitator"
                           value="{{ isset($facilitator) ? $facilitator : "" }}">
                </div>
                <br>
                <div>
                    <input type="submit" value="Сохранить" class="button">
                </div>
            </form>
        </div>
        <br>
        <pre>Зарегистрировать "Business Account" на <a href="https://www.sandbox.paypal.com">https://www.sandbox.paypal.com</a></pre>
        <pre>Перейти в "Account Settings", в колонке "BUSINESS PROFILE" выбрать "Notifications".</pre>
        <pre>"Instant payment notifications" -> "update", нажать кнопку "Edit settings"</pre>
        <pre>В поле "Notification URL" вставить адрес: {{ url('/payment/paypal/handler') }}</pre>

        </div>
    </div>
@endsection
