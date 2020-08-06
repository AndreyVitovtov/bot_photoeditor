@extends("admin.template")

@section("title")
    @lang('pages.settings_button')
@endsection

@section("h3")
    <h3>@lang('pages.settings_button')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/settings.css')}}">

    <div class="settings settings_buttons">
        <form action="{!! route('save-view-buttons') !!}" method="POST">
            <div>
                <div>
                    <div>
                        <label for="background_button">@lang('pages.settings_button_bg_color')</label>
                    </div>
                    <div>
                        <label for="color_text_button">@lang('pages.settings_button_font_color')</label>
                    </div>
                    <div>
                        <label for="size_text_button">@lang('pages.settings_button_font_size')</label>
                    </div>
                </div>
                <div>
                    <div>
                        <input type="color" value="{{ $viewButtons->background }}" name="background" id="background_button">
                    </div>
                    <div>
                        <input type="color" value="{{ $viewButtons->color_text }}" name="color_text" id="color_text_button">
                    </div>
                    <div>
                        <input type="number" value="{{ $viewButtons->size_text }}" name="size_text" id="size_text_button">
                    </div>
                </div>
            </div>
            <input type="submit" value="@lang('pages.settings_button_save')" class="button">
            @csrf
        </form>
        <br>
        <br>
        <div>
            <form action="{{ route('buttons-go-lang') }}">
                <select name="lang" class="language-go">
                    <option value="0">{{ DEFAULT_LANGUAGE }}</option>
                    @foreach($languages as $lang)
                        <option value="{{ $lang->code }}"
                            @if($lang->code === $l)
                                selected
                            @endif
                        >{{ base64_decode($lang->emoji) }} {{ $lang->name }}</option>
                    @endforeach
                </select>
                <br>
                <br>
                <input type="submit" value="@lang('pages.languages_go')" class="button">
            </form>
        </div>
        <br>
        <div>
            <table border="1">
                <tr class="head">
                    <td>
                        №
                    </td>
                    <td>
                        @lang('pages.settings_button_text')
                    </td>
                    <td>
                        @lang('pages.settings_button_menu')
                    </td>
                    <td>
                        @lang('pages.settings_button_edit')
                    </td>
                </tr>
                @foreach($fields as $field)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ base64_decode($field->text) }}</td>
{{--                        <td>{{ $field['menu'] }}</td>--}}
                        <td>@lang('settings_buttons.'.$field->name)</td>
                        <td class="actions">
                            <div>
                                <form action="/admin/settings/buttons/edit" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $field->id }}">
                                    <input type="hidden" name="lang" value="{{ $l }}">
                                    <button>
                                        <i class='icon-pen'></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $fields->links() }}
        </div>
    </div>
@endsection
