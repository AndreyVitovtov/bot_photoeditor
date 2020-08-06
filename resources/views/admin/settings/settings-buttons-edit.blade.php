@extends("admin.template")

@section("title")
    @lang('pages.settings_edit_button')
@endsection

@section("h3")
    <h3>@lang('pages.settings_edit_button')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/settings.css')}}">

    <form action="/admin/settings/buttons/save" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{ $button->id }}">
        <div class="settings">
            <div>
                <label for="text">@lang('pages.settings_edit_button_text')</label>
                <input type="text" name="text" value="{{ base64_decode($button->text) }}" id="text">
            </div>
            <input type="hidden" name="lang" value="{{ $lang }}">
            <div class="block_buttons">
                <input type="submit" value="@lang('pages.settings_edit_button_save')" class="button">
            </div>
        </div>
    </form>
@endsection
