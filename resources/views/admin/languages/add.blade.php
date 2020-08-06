@extends("admin.template")

@section("title")
    @lang('pages.languages_add')
@endsection

@section("h3")
    <h3>@lang('pages.languages_add')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/languages.css')}}">

        <form action="{{ route('languages-add-save') }}" method="POST">
            @csrf
            <div class="lang">
                <div>
                    <label for="name">@lang('pages.languages_name')</label>
                </div>
                <div>
                    <input type="text" name="name" id="name">
                </div>
                <div>
                    <label for="code">@lang('pages.languages_code')</label>
                </div>
                <div>
                    <input type="text" name="code" id="code">
                </div>
                <div>
                    <label for="emoji">@lang('pages.languages_emoji')</label>
                </div>
                <div>
                    <input type="text" name="emoji" id="emoji">
                </div>
                <div class="block_buttons">
                    <input type="submit" value="@lang('pages.languages_add_button')" class="button">
                </div>
            </div>
        </form>

@endsection
