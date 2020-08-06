@extends("admin.template")

@section("title")
    @lang('pages.answers_edit')
@endsection

@section("h3")
    <h3>@lang('pages.answers_edit')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/answers.css')}}">

    <form action="/admin/answers/save" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $answer->id }}">
        <div class="answers">
            <div>
                <label for="question">@lang('pages.answers_add_option_question')</label>
                <input type="text" name="question" value="{{ $answer->question }}" id="question">
            </div>
            <div>
                <label for="answer">@lang('pages.answers_add_answer_bot')</label>
                <input type="text" name="answer" value="{{ $answer->answer }}" id="answer">
            </div>
            <div class="block_buttons">
                <input type="submit" value="@lang('pages.answers_edit_save')" class="button">
            </div>
        </div>
    </form>
@endsection
