@extends("admin.template")

@section("title")
    @lang('pages.languages_list')
@endsection

@section("h3")
    <h3>@lang('pages.languages_list')</h3>
@endsection

@section("main")
    <link rel="stylesheet" href="{{asset('css/languages.css')}}">

    <table>
        <tr>
            <td>
                №
            </td>
            <td>
                @lang('pages.languages_name')
            </td>
            <td>
                @lang('pages.languages_code')
            </td>
            <td>
                @lang('pages.languages_emoji')
            </td>
            <td>
                @lang('pages.languages_delete')
            </td>
        </tr>
        @foreach($languages as $lang)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ $lang->name }}
                </td>
                <td>
                    {{ $lang->code }}
                </td>
                <td>
                    {{ base64_decode($lang->emoji) }}
                </td>
                <td class="actions">
                    <div>
                        <form action="{{ route('languages-delete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $lang->id }}">
                            <button>
                                <i class='icon-trash-empty'></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
