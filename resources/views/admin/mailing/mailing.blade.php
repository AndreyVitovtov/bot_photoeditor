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
        <form action="/admin/mailing/send" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label>@lang('pages.mailing_select_country')</label>
            </div>
            <div>
                <select name="country" class="country_mailing">
                    <option value="%">@lang('pages.mailing_all')</option>
                    @foreach($countries as $key => $country)
                        <option value="{{ $key }}">{{ $country }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label>@lang('pages.mailing_text_message')</label>
            </div>
            <div>
                <textarea name="text" {{ $disable }}></textarea>
            </div>
            <div>
                <label>@lang('pages.mailing_messenger')</label>
            </div>
            <div>
                <input type="radio" name="messenger" value="%" id="all_messenger" checked>
                <label for="all_messenger">@lang('pages.mailing_all')</label>

                <input type="radio" name="messenger" value="Viber" id="viber">
                <label for="viber">Viber</label>

                <input type="radio" name="messenger" value="Telegram" id="telegram">
                <label for="telegram">Telegram</label>
            </div>
            <br>
            <div>
                <label>@lang('pages.mailing_access')</label>
            </div>
            <div>
                <input type="radio" name="access" value="all" id="access_all" checked>
                <label for="access_all">@lang('pages.mailing_all')</label>

                <input type="radio" name="access" value="paid" id="access_paid">
                <label for="access_paid">@lang('pages.mailing_paid')</label>

                <input type="radio" name="access" value="free" id="access_free">
                <label for="access_free">@lang('pages.mailing_free')</label>

                <input type="radio" name="access" value="no" id="access_no">
                <label for="access_no">@lang('pages.mailing_no')</label>
            </div>
            <div class="block_buttons">
                <button class="button">@lang('pages.mailing_send')</button>
                <div>
                    <a href="/admin/mailing/analize">
                        <div class="button">
                            @lang('pages.mailing_analize')
                        </div>
                    </a>
                    <a href="/admin/mailing/log">
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
                        <form action="/admin/mailing/cancel" method="POST">
                            @csrf
                            <button class="button">@lang('pages.mailing_cancel')</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection



