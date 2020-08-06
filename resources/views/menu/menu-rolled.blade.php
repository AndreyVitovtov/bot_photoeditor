<span class="rolled-hidden">
    <li class="item-menu" data-item="{{ $nameItem }}-hidden">
        <div>
            <div>
                <i class="{{ $icon }}"></i> <span>@lang('menu.'.$name)</span>
            </div>
            <div class="item-menu-open">
                <i class="icon-left-open-mini"></i>
            </div>
        </div>
    </li>
    <li class="{{ $nameItem }}-hidden hidden menu-hidden">
        <div class="title">{{ $name }}</div>
        @foreach($items as $item)
            <a href="{{ $item['url'] }}">
                <div class="item {{ $item['menu'] }}">
                        <i class="icon-record-outline"></i> @lang('menu.'.$item['name'])
                </div>
            </a>
        @endforeach
    </li>
</span>
