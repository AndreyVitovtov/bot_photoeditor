@extends("admin.template")

@section("title")
    @lang('pages.settings_main')
@endsection

@section("h3")
    <h3>@lang('pages.settings_main')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/settings.css')}}">

    <form action="/admin/settings/main/save" method="POST">
        @csrf
        <div class="settings">
            @foreach($fields as $field)
                <div>
{{--                    <label for="{{ $field['prefix'] }}">{{ $field['name'] }}</label>--}}
                    <label for="{{ $field['prefix'] }}">@lang('settings_main.'.$field['prefix'])</label>
                    <input type="{{ $field['type'] }}" name="input[{{ $field['id'] }}]" value="{{ $field['value'] }}">
                </div>
            @endforeach
            <div class="block_buttons">
                <input type="submit" value="@lang('pages.settings_main_save')" class="button">
            </div>
        </div>
    </form>
@endsection
