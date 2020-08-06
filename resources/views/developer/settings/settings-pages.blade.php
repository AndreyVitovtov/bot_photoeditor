@extends("developer.template")

@section("title")
    Страницы
@endsection

@section("h3")
    <h3>Страницы</h3>
@endsection

@section("main")
    <table>
        <tr>
            <td>№</td>
            <td>Имя</td>
            <td>Текст</td>
            <td>Описание</td>
            <td>Действия</td>
        </tr>
    @foreach($fields as $id => $field)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $field['name'] }}</td>
                <td>{{ base64_decode($field['text']) }}</td>
                <td>{{ $field['description'] }}</td>
                <td class="actions">
                    <div>
                        <form action="/developer/settings/pages/edit" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $field['id'] }}">
                            <button>
                                <i class='icon-pen'></i>
                            </button>
                        </form>
                        <form action="/developer/settings/pages/delete" method="POST">
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
    <form action="/developer/settings/pages/add" method="POST">
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
            <label for="description">Описание</label>
            <input type="text" name="description" id="description">
        </div>
        <div>
            <label for="description_us">Описание на английском</label>
            <input type="text" name="description_us" id="description_us">
        </div>
        <br>
        <div>
            <button class="button">Добавить</button>
        </div>
    </form>
@endsection
