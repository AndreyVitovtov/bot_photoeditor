@extends("payment.template")

@section("title")
    {{ $texts->payment_header }} |
@endsection

@section("h3")
    <h3>{{ $texts->payment_details }}</h3>
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
    <div class="invoice">
        <table>
            <tr>
                <td>
                    <b><pre>Username:</pre></b>
                </td>
                <td>
                    <pre>{{ $username }}</pre>
                </td>
            </tr>
            <tr>
                <td>
                    <b><pre>Email:</pre></b>
                </td>
                <td>
                    <pre>{{ $email }}</pre>
                </td>
            </tr>
            <tr>
                <td>
                    <b><pre>Phone:</pre></b>
                </td>
                <td>
                    <pre>{{ $phone }}</pre>
                </td>
            </tr>
            <tr>
                <td>
                    <b><pre>{{ $texts->payment_method }}</pre></b>
                </td>
                <td>
                    <pre>{{ $paySystem }}</pre>
                </td>
            </tr>
            <tr>
                <td>
                    <b><pre>{{ $texts->payment_purpose }}</pre></b>
                </td>
                <td>
                    <pre>{{ $description }}</pre>
                </td>
            </tr>
            <tr>
                <td>
                    <b><pre>{{ $texts->payment_sum }}</pre></b>
                </td>
                <td>
                    <pre>{{ $amount }} {{ $currency }}</pre>
                </td>
            </tr>
        </table>
        <br>
        {!! $payData !!}
    </div>
    <div class="invoice-bottom">
        &nbsp;
    </div>
@endsection


