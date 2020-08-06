<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
<script src="{{ asset('js/app.js') }}"></script>
    <script>
//        let userID = {{ \Illuminate\Support\Facades\Auth::guard('chat')->id() }};
  let      userID = 1;

        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: `${window.location.hostname}`
        });

        window.Echo.channel(`TestChannel`)
            .listen('.App\\Events\\TestEvent', (message) => {
                if (message.userID == userID) {
                    realtime(message);
                }
            })

        function realtime(message) {
            // console.log(message);
            alert(message.payload);
        }
    </script>
</body>
</html>
