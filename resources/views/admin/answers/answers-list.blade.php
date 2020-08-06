@extends("admin.template")

@section("title")
    @lang('pages.answers')
@endsection

@section("h3")
    <h3>@lang('pages.answers')</h3>
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
                        @lang('pages.answers_question')
                    </td>
                    <td>
                        @lang('pages.answers_answer')
                    </td>
                    <td>
                        @lang('pages.answers_actions')
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
                        <td class="actions">
                            <div>
                                <form action="/admin/answers/edit" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $answer['id'] }}">
                                    <button>
                                        <i class='icon-pen'></i>
                                    </button>
                                </form>

                                <form action="/admin/answers/delete" method="POST">
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
    </div>
@endsection
