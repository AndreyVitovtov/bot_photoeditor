@extends("developer.template")

@section("title")
    Изменить основные настройки
@endsection

@section("h3")
    <h3>Изменить основные настройки</h3>
@endsection

@section("main")
    <form action="/developer/settings/main/save" method="POST">
        <input type="hidden" name="id" value="{{ $id }}">
        @csrf
        <div>
            <label for="name">Имя поля:</label>
            <input type="text" name="name" value="{{ $name }}" id="name">
        </div>
        <div>
            <label for="name">Имя поля на английском:</label>
            <input type="text" name="name_us" value="{{ $name_us }}" id="name_us">
        </div>
        <div>
            <label for="prexix">Префикс</label>
            <input type="text" name="prefix" value="{{ $prefix }}" id="prefix">
        </div>
        <div>
            <label for="value">Значение</label>
            <input type="text" name="value" value="{{ $value }}" id="value">
        </div>
        <div>
            <label for="type">Тип</label>
            <select name="type" id="type">
                <option value="number" @if($type == "number") selected @endif>Number</option>
                <option value="text" @if($type == "text") selected @endif>Text</option>
                <option value="date" @if($type == "date") selected @endif>Date</option>
            </select>
        </div>
        <br>
        <div>
            <input type="submit" value="Сохранить" class="button">
        </div>
    </form>
@endsection
