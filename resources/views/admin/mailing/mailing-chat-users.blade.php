@extends("admin.template")

@section("title")
    @lang('pages.mailing')
@endsection

@section("h3")
    <h3>@lang('pages.mailing')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/mailing.css')}}">

    <div class="mailing">
        <form action="{{ route('mailing-send-chat') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="select_chat">@lang('pages.mailing_select_chat')</label>
            </div>
            <div>
                <select name="chat" id="select_chat">
                    <option value="all">@lang('pages.mailing_all')</option>
                    @foreach($chats as $chat)
                        <option value="{{ $chat->id }}">{{ $chat->name }} ({{ $chat->creator->username }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>@lang('pages.mailing_text_message')</label>
            </div>
            <div>
                <textarea name="text" {{ $disable }}></textarea>
            </div>
            <div class="block_buttons">
                <button class="button">@lang('pages.mailing_send')</button>
                <div>
                    <a href="/admin/mailing/chat/analize">
                        <div class="button">
                            @lang('pages.mailing_analize')
                        </div>
                    </a>
                    <a href="/admin/mailing/chat/log">
                        <div class="button">
                            @lang('pages.mailing_log')
                        </div>
                    </a>
                </div>
            </div>
        </form>
        <div>
            @if(is_array($task))
                <div class="mailing-task">
                    @lang('pages.mailing_created') {{ $task['create'] }}
                    <br>
                    @lang('pages.mailing_sending') ≈
                    @if($task['start'] > $task['count'])
                        {{ $task['count'] }}
                    @else
                        {{ $task['start'] }}
                    @endif
                    @lang('pages.mailing_of') {{ $task['count'] }}
                    <div>
                        <form action="/admin/mailing/chat/cancel" method="POST">
                            @csrf
                            <button class="button">@lang('pages.mailing_cancel')</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection



