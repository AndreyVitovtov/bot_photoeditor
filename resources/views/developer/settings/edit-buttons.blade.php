@extends("developer.template")

@section("title")
    Изменить кнопки
@endsection

@section("h3")
    <h3>Изменить кнопки</h3>
@endsection

@section("main")
    <form action="/developer/settings/buttons/save" method="POST">
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
            <label for="menu">Меню</label>
            <input type="text" name="menu" value="{{ $menu }}" id="menu">
        </div>
        <div>
            <label for="menu_us">Меню на английском</label>
            <input type="text" name="menu_us" value="{{ $menu_us }}" id="menu_us">
        </div>
        <br>
        <div>
            <input type="submit" value="Сохранить" class="button">
        </div>
    </form>
@endsection
