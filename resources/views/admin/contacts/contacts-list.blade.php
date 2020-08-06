@extends("admin.template")

@section("title")
    @lang('pages.contacts')
@endsection

@section("h3")
    <h3>@lang('pages.contacts')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/contacts.css')}}">

    <div class="contacts">
        <div>
            <table>
                <tr>
                    <td>
                        <input type="checkbox" name="check_all" id="check_all">
                    </td>
                    <td>
                        №
                    </td>
                    <td>
                        @lang('pages.contacts_user')
                    </td>
                    <td>
                        @lang('pages.contacts_message')
                    </td>
                    <td>
                        @lang('pages.contacts_date')
                    </td>
                    <td>
                        @lang('pages.contacts_actions')
                    </td>
                </tr>
                @foreach($contacts as $contact)
                    <tr>
                        <td>
                            <input type="checkbox" name="offer[]" value="{{ $contact->id }}" class="checkbox">
                        </td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="/admin/users/profile/{{ $contact->users->id }}">{{ $contact->users->username }}</a>
                        </td>
                        <td>
                            {{ $contact->text }}
                        </td>
                        <td>
                            {{ $contact->date }} {{ $contact->time }}
                        </td>
                        <td class="actions">
                            <div>
                                <form action="/admin/contacts/answer" method="POST" id="form-answer-{{ $contact->id }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $contact->id }}">
                                    <button form="form-answer-{{ $contact->id }}">
                                        <i class='icon-comment-6'></i>
                                    </button>
                                </form>

                                <form action="{{ route('contacts-delete') }}" method="POST" id="form-delete-{{ $contact->id }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $contact->id }}">
                                    <button form="form-delete-{{ $contact->id }}">
                                        <i class='icon-trash-empty'></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $contacts->links() }}
            <br>
            <div>
                <form action="{{ route('contacts-delete-check') }}" method="POST" id="form-delete-check">
                    @csrf
                    <input type="hidden" name="data" class="data-form-delete-check">
                    <input type="hidden" name="type" value="{{ $type }}">
                    <button type="submit" id="form-delete-check-submit" class="button">@lang('pages.contacts_delete_checked')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
