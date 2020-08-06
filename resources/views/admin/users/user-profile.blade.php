@extends("admin.template")

@section("title")
    @lang('pages.users_user_profile') "{{ $profile->username }}"
@endsection

@section("h3")
    <h3>@lang('pages.users_user_profile') "{{ $profile->username }}"</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/users.css')}}">

    <style>
        main .users label.access:after {
            content: '@lang('pages.users_user_no')';
            text-align: center;
            line-height: 26px;
            color: #fff;
            position: absolute;
            width: 35px;
            height: 30px;
            background-color: #808080;
            border-radius: 5px 0 0 5px;
            border: solid 1px #808080;
            box-sizing: border-box;
            left: -1px;
            top: -2px;
        }

        main .users #access:checked + label.access:before {
            content: '@lang('pages.users_user_yes')';
            text-align: center;
            line-height: 26px;
            color: #fff;
            position: absolute;
            width: 35px;
            height: 30px;
            background-color: #3c8dbc;
            border-radius: 0 5px 5px 0;
            border: solid 1px #3c8dbc;
            box-sizing: border-box;
            right: -2px;
            top: -2px;
        }
    </style>

    <div class="users">
        <table>
            <tr>
            </tr>
            <tr>
                <td>@lang('pages.users_username')</td>
                <td>{{ $profile->username }}</td>
            </tr>
            <tr>
                <td>ID Chat:</td>
                <td>{{ $profile->chat }}</td>
            </tr>
            <tr>
                <td>@lang('pages.users_date')</td>
                <td>{{ $profile->date }} {{ $profile->time }}</td>
            </tr>
        </table>
        <br>
        <hr>
        <br>
{{--        <div style="border: solid 1px #d1d1d1; background-color: #f4f4f4; padding: 5px;">--}}
{{--            <div>--}}
{{--                <label>--}}
{{--                    @lang('pages.users_user_access')--}}
{{--                </label>--}}
{{--            </div>--}}
{{--            <div>--}}
{{--                <form action="{{ route('user-access') }}" method="POST" id="access-form">--}}
{{--                    @csrf--}}
{{--                    <input type="hidden" name="id" value="{{ $profile->id }}">--}}
{{--                    <input type="checkbox"--}}
{{--                           @if($profile->access == '1')--}}
{{--                           checked--}}
{{--                           @endif--}}
{{--                           id="access" name="access">--}}
{{--                    <label for="access" class="access">--}}
{{--                    </label>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}


        <div>
            <form action="{{ route('user-send-message') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $profile->id }}">
                <div>
                    <label for="message">@lang('pages.users_message')</label>
                </div>
                <div>
                    <textarea name="message" id="message"></textarea>
                </div>
                <br>
                <div>
                    <input type="submit" value="@lang('pages.users_send')" class="button">
                </div>
            </form>
        </div>
        <br>
        <hr>
        <br>
        <div>
            <form action="{{ route('user-count-chat') }}" method="POST">
                <input type="hidden" name="user_id" value="{{ $profile->id }}">
                @csrf
                <div>
                    <label for="count_chat">@lang('pages.users_count_chats')</label>
                </div>
                <div>
                    <input type="number" name="count" id="count_chat">
                </div>
                <br>
                <div>
                    <input type="submit" value="@lang('pages.users_add')" class="button">
                </div>
            </form>
        </div>
        <br>
        <hr>
        <br>
        <div>
            <form action="{{ route('user-count-mailing') }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ $profile->id }}">
                <div>
                    <label for="select_chat">@lang('pages.users_select_chat')</label>
                </div>
                <div>
                    <select name="chat" id="select_chat">
                        @foreach($profile->chats as $chat)
                            <option value="{{ $chat->id }}">{{ $chat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="count_mailing">@lang('pages.users_count_mailing')</label>
                </div>
                <div>
                    <input type="number" name="count" id="count_mailing">
                </div>
                <br>
                <div>
                    <input type="submit" value="@lang('pages.users_add')" class="button">
                </div>
            </form>
        </div>



    </div>

    <script>
        $('#access').change(function() {
            $('#access-form').submit();
        });
    </script>
@endsection
