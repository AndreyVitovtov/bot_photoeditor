<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title") Payment Bot</title>
    <link rel="stylesheet" href="{{asset('css/payment.css')}}">
    <link rel="stylesheet" href="{{asset('css/fontello.css')}}">
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    <link rel="shortcut icon" href="{{asset('img/payment.ico')}}" type="image/x-icon">
    <meta name="theme-color" content="@yield('color')">
</head>
<body>
    <div class="wrapper @yield('messenger')">
        <header>
            {{ $texts->payment_header }}
        </header>
        <main>
            @yield("h3")
            <div class="content">
                @yield("main")
            </div>
        </main>
        <footer>
            Copyright © 2020 <a href="https://vitovtov.info" target="_blank">vitovtov.info</a>
        </footer>
    </div>
</body>
</html>
