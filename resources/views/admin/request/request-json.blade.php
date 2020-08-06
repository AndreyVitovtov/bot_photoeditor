<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request</title>
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>

    <style>
        body {
            font-size: 17px;
            color: rgba(117, 117, 117, 0.69);
        }

        .key {
            color: #2a9055;
            font-weight: bold;
        }

        .val {
            color: #1d68a7;
        }

        textarea {
            width: 50%;
            height: 100px;
        }

        a {
            color: #ff6942;
        }

        .button {
            display: inline-block;
            padding: 8px 12px;
            background-color: #3c8dbc;
            border: solid 1px #367fa9;
            color: #fff;
            border-radius: 2px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #337ab7;
            color: #fff;
        }
    </style>
</head>
<body>
<code></code>
<br>
<br>
<textarea id="json"></textarea>
<br>
<button onclick="Copy()" class="button">Copy text</button>
<button onclick="location.reload();" class="button">Refresh</button>

@php
    $jsonFull = $json;

    function addClass($array, $data = []) {
        foreach($array as $key => $arr) {
            $key = '<span class="key">'.$key.'</span>';

            if(is_array($arr)) {
                $data[$key] = addClass($arr);
            }
            else {
                if(substr($arr, 0, 4) == "http") {
                    $arr = '<a href="'.$arr.'" target="_blank">'.$arr.'</a>';
                }
                $data[$key] = '<span class="val">'.$arr.'</span>';
            }
        }
        return $data;
    }

    $array = json_decode($json, true);

    $array = addClass($array);

    $json = json_encode($array);

    $json = str_replace("{", "{<br>", $json);
    $json = str_replace("}", "<br>}", $json);
    $json = str_replace(",", ",<br>", $json);
    $json = str_replace(':"', ':&nbsp;"', $json);
    $json = str_replace(':{', ':&nbsp;{', $json);
    $json = str_replace('<br>"', '<br>&nbsp;&nbsp;&nbsp;"', $json);
    $json = str_replace('https: //', 'https://', $json);
@endphp

<script>
    let json = '{!! $json !!}';
    let jsonFull = '{!! $jsonFull !!}';
    $('code').html(json);
    $('textarea').val(jsonFull);

    function Copy() {
        /* Get the text field */
        var copyText = document.getElementById("json");

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");
    }
</script>
</body>
</html>
