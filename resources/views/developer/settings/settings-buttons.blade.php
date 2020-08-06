@extends("developer.template")

@section("title")
    Кнопки
@endsection

@section("h3")
    <h3>Кнопки</h3>
@endsection

@section("main")
    <table>
        <tr>
            <td>№</td>
            <td>Имя</td>
            <td>Текст</td>
            <td>Меню</td>
            <td>Действия</td>
        </tr>
        @foreach($fields as $field)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $field['name'] }}</td>
                <td>{{ base64_decode($field['text']) }}</td>
                <td>{{ $field['menu'] }}</td>
                <td class="actions">
                    <div>
                        <form action="/developer/settings/buttons/edit" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $field['id'] }}">
                            <button>
                                <i class='icon-pen'></i>
                            </button>
                        </form>
                        <form action="/developer/settings/buttons/delete" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $field['id'] }}">
                            <button>
                                <i class='icon-trash-empty'></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
    <br>
    <form action="/developer/settings/buttons/add" method="POST">
        @csrf
        <div>
            <label for="name">Имя</label>
            <input type="text" name="name" id="name">
        </div>
        <div>
            <label for="text">Текст</label>
            <input type="text" name="text" id="text">
        </div>
        <div>
            <label for="name">Меню</label>
            <input type="text" name="menu" id="menu">
        </div>
        <div>
            <label for="menu_us">Меню на английском</label>
            <input type="text" name="menu_us" id="menu_us">
        </div>
        <br>
        <div>
            <button class="button">Добавить</button>
        </div>
        </form>
@endsection
