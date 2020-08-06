@extends("developer.template")

@section("title")
    Изменить страницы
@endsection

@section("h3")
    <h3>Изменить страницы</h3>
@endsection

@section("main")
    <form action="/developer/settings/pages/save" method="POST">
        <input type="hidden" name="id" value="{{ $id }}">
        @csrf
        <div>
            <label for="name">Имя:</label>
            <input type="text" name="name" value="{{ $name }}" id="name">
        </div>
        <div>
            <label for="text">Текст</label>
            <input type="text" name="text" value="{{ $text }}" id="text">
        </div>
        <div>
            <label for="description">Описание</label>
            <input type="text" name="description" value="{{ $description }}" id="description">
        </div>
        <div>
            <label for="description_us">Описание на английском</label>
            <input type="text" name="description_us" value="{{ $description_us }}" id="description_us">
        </div>
        <br>
        <div>
            <input type="submit" value="Сохранить" class="button">
        </div>
    </form>
@endsection
