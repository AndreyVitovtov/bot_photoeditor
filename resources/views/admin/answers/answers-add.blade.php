@extends("admin.template")

@section("title")
    @lang('pages.answers_add')
@endsection

@section("h3")
    <h3>@lang('pages.answers_add')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/answers.css')}}">

    <form action="/admin/answers/save" method="POST">
        @csrf
        <div class="answers">
            <div>
                <label for="question">@lang('pages.answers_add_option_question')</label>
            </div>
            <div>
                <input type="text" name="question" id="question">
            </div>
            <div>
                <label for="answer">@lang('pages.answers_add_answer_bot')</label>
            </div>
            <div>
                <input type="text" name="answer" id="answer">
            </div>
            <div class="block_buttons">
                <input type="submit" value="@lang('pages.answers_add_send')" class="button">
            </div>
        </div>
    </form>
@endsection
