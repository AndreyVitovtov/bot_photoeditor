<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title") | Разработка</title>
    <link rel="stylesheet" href="{{asset('css/main.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontello.css')}}">
    <link rel="stylesheet" href="{{asset('css/developer.css')}}">
    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('js/admin-panel/common.js')}}"></script>
    <script src="{{asset('js/admin-panel/drawChart.js')}}"></script>
    <script src="{{asset('https://www.gstatic.com/charts/loader.js')}}"></script>
</head>
<body>
<header>
    <section class="left-panel">
        Панель
    </section>
    <section class="right-panel">
        <nav class="open-menu mob-hidden">
            <i class="icon-menu"></i>
        </nav>
        <nav class="open-menu-mob pc-hidden">
            <i class="icon-menu"></i>
        </nav>
        <nav class="open-user-menu">
            <img src="{{asset('img/avatar5.png')}}" alt="avatar">
            <span>
					Разработчик
				</span>
        </nav>
        <div class="dropdown-menu">
            <img src="{{asset('img/avatar5.png')}}" alt="avatar">
            <div class="title">
                <div>
                    Разработчик
                </div>
                <div>
                    //TODO:: LOGIN
                </div>
            </div>
            <div class="dropdown-menu-nav">
                <div>
                    <button class="button user-settings">Настройки</button>
                </div>
                <div>
                    <button data-go="exit" class="button">Выйти</button>
                </div>
            </div>
        </div>
    </section>
</header>
<main>
    <section class="sidebar no-active">
        <div class="user-panel">
            <img src="{{asset('img/avatar5.png')}}" alt="avatar">
            <span>
					Разработчик
				</span>
        </div>
        <ul class="sidebar-menu">
            <li class="header">Меню</li>
            @component('menu.menu-item', [
                'name' => 'developer_admin_panel',
                'icon' => 'icon-user-3',
                'menu' => 'admin',
                'url' => '/admin'])
            @endcomponent
{{--            @component('menu.menu-item', [--}}
{{--                'name' => 'developer_webhook',--}}
{{--                'icon' => 'icon-wind-1',--}}
{{--                'menu' => 'webhook',--}}
{{--                'url' => '/developer/webhook'])--}}
{{--            @endcomponent--}}
            @component('menu.menu-item', [
                'name' => 'request',
                'icon' => 'icon-code-2',
                'menu' => 'request',
                'url' => '/request'])
            @endcomponent
            @component('menu.menu-item', [
                'name' => 'developer_answers',
                'icon' => 'icon-help-1',
                'menu' => 'answers',
                'url' => '/developer/answers'])
            @endcomponent
            @component('menu.menu-rolled', [
                'nameItem' => 'payment',
                'icon' => 'icon-money-2',
                'name' => 'developer_pay',
                'items' => [[
                        'name' => 'developer_qiwi',
                        'menu' => 'payqiwi',
                        'url' => '/developer/payment/qiwi'
                    ],[
                       'name' => 'developer_yandex_noney',
                       'menu' => 'payyandex',
                       'url' => '/developer/payment/yandex'
                    ],[
                       'name' => 'developer_webmoney',
                       'menu' => 'paywebmoney',
                       'url' => '/developer/payment/webmoney'
                    ],[
                       'name' => 'developer_paypal',
                       'menu' => 'paypaypal',
                       'url' => '/developer/payment/paypal'
                    ]
                ]])
            @endcomponent
            @component('menu.menu-rolled', [
                'nameItem' => 'settings',
                'icon' => 'icon-cog-alt',
                'name' => 'developer_settings',
                'items' => [[
                        'name' => 'developer_settings_main',
                        'menu' => 'settingsmain',
                        'url' => '/developer/settings/main'
                    ],[
                       'name' => 'developer_settings_pages',
                       'menu' => 'settingspages',
                       'url' => '/developer/settings/pages'
                    ],[
                       'name' => 'developer_settings_buttons',
                       'menu' => 'settingsbuttons',
                       'url' => '/developer/settings/buttons'
                    ]
                ]])
            @endcomponent
        </ul>
    </section>
    <section class="content">
        <div class="container">
            @yield("h3")
            <div>
                @yield("main")
            </div>
        </div>
        <footer>
            Copyright © 2020 <a href="https://vitovtov.info" target="_blank">vitovtov.info</a>
        </footer>
    </section>
</main>

@if(isset($menuItem))
    <script>
        $('.item-menu').removeClass('active');
        $('main section.sidebar .menu-hidden div').removeClass('active');
        $('.{{ $menuItem }}').addClass('active');
        $('.{{ $menuItem }}').parents('span.rolled-hidden').children('li.item-menu').addClass('active');
        $('.{{ $menuItem }}').parents('li').addClass('menu-active');
        $('.{{ $menuItem }}').parents('li').toggle();
    </script>
@endif
</body>
</html>
