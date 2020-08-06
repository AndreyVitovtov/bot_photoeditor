@extends("developer.template")

@section("title")
    Редактировать ответ
@endsection

@section("h3")
    <h3>Редактировать ответ</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/answers.css')}}">

    <form action="{!! route('save-answer') !!}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $answer->id }}">
        <div class="answers">
            <div>
                <label for="question">Вариант вопроса, который может задать пользователь</label>
                <input type="text" name="question" value="{{ $answer->question }}" id="question">
            </div>
            <div>
                <label for="answer">Ответ бота</label>
                <input type="text" name="answer" value="{{ $answer->answer }}" id="answer">
            </div>
            <div>
                <label for="method">Метод</label>
                <input type="text" name="method" value="{{ $answer->method }}" id="method">
            </div>
            <div class="block_buttons">
                <input type="submit" value="Сохранить" class="button">
            </div>
        </div>
    </form>
@endsection
