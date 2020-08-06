<?php
    $main = json_decode(file_get_contents(public_path("json/main.json")));
    foreach($main as $name => $value) {
        define(mb_strtoupper($name), $value);
    }
