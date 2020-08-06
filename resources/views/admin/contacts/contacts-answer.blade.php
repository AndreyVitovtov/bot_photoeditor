@extends("admin.template")

@section("title")
    @lang('pages.contacts_answer')
@endsection

@section("h3")
    <h3>@lang('pages.contacts_answer')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/contacts.css')}}">

    <div class="contacts">
        <div>
            <form action="{{ route('contacts-answer-send') }}" method="POST">
                @csrf
                <input type="hidden" name="chat" value="{{ $contact->users->chat }}">
                <input type="hidden" name="messenger" value="{{ $contact->users->messenger }}">
                <input type="hidden" name="type" value="{{ $contact->type->type }}">
                {{ $contact->text }}
                <br>
                <textarea name="text" placeholder="@lang('pages.contacts_answer_message')"></textarea>
                <br>
                <input type="submit" value="@lang('pages.contacts_answer_send')" class="button">
            </form>
        </div>
    </div>
@endsection
