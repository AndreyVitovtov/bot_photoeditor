@extends("developer.template")

@section("title")
    Ответы
@endsection

@section("h3")
    <h3>Ответы</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/answers.css')}}">

    <div class="answers">
        <div>
            <table border="1">
                <tr class="head">
                    <td>
                        №
                    </td>
                    <td>
                        Вопрос
                    </td>
                    <td>
                        Ответ
                    </td>
                    <td>
                        Метод
                    </td>
                    <td>
                        Действия
                    </td>
                </tr>
                @foreach($answers as $answer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            {{ $answer['question'] }}
                        </td>
                        <td>
                            {{ $answer['answer'] }}
                        </td>
                        <td>
                            {{ $answer['method'] }}
                        </td>
                        <td class="actions">
                            <div>
                                <form action="{!! route('edit-answer') !!}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $answer['id'] }}">
                                    <button>
                                        <i class='icon-pen'></i>
                                    </button>
                                </form>

                                <form action="{!! route('delete-answer') !!}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $answer['id'] }}">
                                    <button>
                                        <i class='icon-trash-empty'></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <br>
        <br>
        <form action="{!! route('add-answer') !!}" method="POST">
            @csrf
            <div>
                <label for="question">Вопрос</label>
                <input type="text" name="question" id="question">
            </div>
            <div>
                <label for="answer">Ответ</label>
                <input type="text" name="answer" id="answer">
            </div>
            <div>
                <label for="method">Метод</label>
                <input type="text" name="method" id="method">
            </div>
            <br>
            <div>
                <input type="submit" value="Добавить" class="button">
            </div>
        </form>
    </div>
@endsection
