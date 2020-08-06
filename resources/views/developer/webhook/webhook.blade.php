@extends("developer.template")

@section("title")
    Основные настройки
@endsection

@section("h3")
    <h3>Настройки Webhook</h3>
@endsection

@section("main")
    <form action="/developer/webhook/set" method="POST">
        @csrf
        <label for="">URI Webhook</label>
        <br>
        <input type="text" name="uri" value="{{ $uri }}">
        <br>
        <br>
        <input type="submit" value="Отправить" class="button">
    </form>
@endsection
