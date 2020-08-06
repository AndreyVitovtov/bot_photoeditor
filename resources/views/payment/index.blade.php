@extends("payment.template")

@section("title")
    {{ $texts->payment_header }} |
@endsection

@section("h3")
    <h3>{{ $texts->payment_title }}</h3>
@endsection

@section('messenger')
    {{ $messenger }}
@endsection

@section('color')
    @if($messenger == "telegram")
        #0088cc
    @elseif($messenger == "viber")
        #665CAC
    @elseif($messenger == "facebook")
        #0078FF
    @endif
@endsection

@section("main")
@php
    if(!empty(old('amount'))) {
        $amount = old('amount');
    }
@endphp

    <form action="{{ route('payment-invoice') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $id }}">
        <input type="hidden" name="messenger" value="{{ $messenger }}">
        <input type="hidden" name="purpose" value="{{ $purpose }}">
        <div class="pay-systems">
            <div class="label">
                <label for="qiwi">
                    <img src="{{ url("/img/qiwi.png") }}" alt="QIWI">
                </label>
                <br>
                <input type="radio" name="pay_system" value="qiwi" id="qiwi"
                @if(old('pay_system') == "qiwi")
                    checked
                @endif
                    >
            </div>
            <div class="label">
                <label for="yandex">
                    <img src="{{ url("/img/yandexmoney.png") }}" alt="Yandex">
                </label>
                <br>
                <input type="radio" name="pay_system" value="yandex" id="yandex"
                @if(old('pay_system') == "yandex")
                    checked
                @endif
                >
            </div>
            <div class="label">
                <label for="webmoney">
                    <img src="{{ url("/img/webmoney.png") }}" alt="Webmoney">
                </label>
                <br>
                <input type="radio" name="pay_system" value="webmoney" id="webmoney"
                @if(old('pay_system') == "webmoney")
                    checked
                @endif
                >
            </div>
            <div class="label">
                <label for="paypal">
                    <img src="{{ url("/img/paypal.png") }}" alt="PayPal">
                </label>
                <br>
                <input type="radio" name="pay_system" value="paypal" id="paypal"
                @if(old('pay_system') == "paypal")
                    checked
                @endif
                >
            </div>
        </div>
        <div>
            <label for="amount">{{ $texts->payment_sum }}:</label>
        </div>
        <div class="summ">
            <input type="text" name="amount" value="{{ $amount }}"
                   @if(isset($amount)) readonly @endif class="amount" required>
            <span>{{ $texts->payment_currency }}</span>
        </div>
        <br>
        <div>
            <label for="email">Email:</label>
        </div>
        <div class="email">
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
            <span><i class="icon-at-4"></i></span>
        </div>
        <br>
        <div>
            <label for="phone">Phone:</label>
        </div>
        <div class="phone">
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
            <span><i class="icon-phone-3"></i></span>
        </div>
        <br>
        <div class="button-center">
            <input type="submit" value="{{ $texts->payment_next }}" class="button">
        </div>
    </form>
@endsection


