@extends("admin.template")

@section("title")
@lang('pages.filters')
@endsection

@section("h3")
<h3>@lang('pages.filters')</h3>
@endsection

@section("main")
<link rel="stylesheet" href="{{asset('css/filters.css')}}">

    <div class="filters">
        <div>
            <form action="{{ route('filters-save') }}" method="POST">
                @csrf
                <table>
                    <tr>
                        <td></td>
                        <td>ID</td>
                        <td>Text code</td>
                        <td>Image link</td>
                        <td>Description</td>
                    </tr>
                    @foreach($filters as $id => $filter)
                        <tr>
                            <td>
                                <input type="checkbox" name="filters[]" value="{{ $id }}"
                                @if(isset($_filters[$id]))
                                    checked
                                @endif
                                >
                            </td>
                            <td>{{ $id }}</td>
                            <td>{{ $filter->text_code }}</td>
                            <td><a href="{{$filter->image_link}}" class="link" target="_blank">{{ $filter->image_link }}</a></td>
                            <td>{{ $filter->description }}</td>
                        </tr>
                    @endforeach
                </table>
                <br>
                <input type="submit" value="@lang('pages.filters_save')" class="button">
            </form>
        </div>
    </div>
@endsection
