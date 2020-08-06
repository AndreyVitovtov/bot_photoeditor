<?php

namespace App\models;

class Curl {
    public function multiCurl($data, $url, $headers) {
        $mh = curl_multi_init();
        $connectionArray = [];

        foreach($data as $item) {
            $key = $item['key'];
            $data_string = json_encode($item['params']);
            $headers[] = 'Content-Length: ' . strlen($data_string);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_multi_add_handle($mh, $ch);
            $connectionArray[$key] = $ch;
        }
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        }
        while($running > 0);

        $responseEmpty = [];
        $content = [];
        $httpCode = [];

        foreach($connectionArray as $key => $ch) {
            $content[$key] = curl_multi_getcontent($ch);

            if(empty(curl_multi_getcontent($ch))) {
                $responseEmpty[] = $key;
            }

            $getinfo = curl_getinfo($ch);
            $httpCode[$key] = $getinfo['http_code'];
//            $url[$key] = $getinfo['url'];
            curl_multi_remove_handle($mh, $ch);
        }

        curl_multi_close($mh);

        $result = [
            "status" => !empty($content) ? "success" : "error",
            "httpCode" => $httpCode,
            "url" => $url,
            "response" => $content
        ];

        if(!empty($responseEmpty)) {
            $result['responseEmpty'] = $responseEmpty;
        }

        return $result;
    }

    public function POST($url, $params, $headers = null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($headers == null) {
            $headers = [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($params)
            ];
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    public function GET($url, $headers = []) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    public function PUT($url, $headers = null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }

    public function DELETE($url, $headers) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
}
