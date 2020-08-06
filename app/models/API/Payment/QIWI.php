<?php


namespace App\models\API\Payment;


use App\models\Curl;
use App\models\PerformAction;
use Exception;

class QIWI {
    private $token;
    private $webhookId;
    private $publicKey;
    private $headers;
    private $request;

    public function __construct($token, $webhookId = null, $publicKey = null) {
        $this->token = $token;
        if($webhookId != null) {
            $this->webhookId = $webhookId;
        }

        if($publicKey != null) {
            $this->publicKey = $publicKey;
        }

        $this->headers = [
            'Authorization: Bearer '.$this->token
        ];
        $this->request = null;
    }

    private function getReqParams() {
        //Make sure that it is a POST request.
        if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0) {
            throw new Exception('Request method must be POST!');
        }

        //Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));

        //Attempt to decode the incoming RAW post data from JSON.
        $decoded = json_decode($content, true);
        $this->request = $decoded;

        //If json_decode failed, the JSON is invalid.
        if(!is_array($decoded)){
            throw new Exception('Received content contained invalid JSON!');
        }

        //Check if test
        if($decoded['test'] == 'true') {
            PerformAction::pay(null, 'test');
            throw new Exception('Test!');
        }

        // Строка параметров
        $reqparams = $decoded['payment']['sum']['currency'].
            '|'.$decoded['payment']['sum']['amount'].
            '|'.$decoded['payment']['type'].
            '|'.$decoded['payment']['account'].
            '|'.$decoded['payment']['txnId'];
        // Подпись из запроса
        foreach ($decoded as $name=>$value) {
            if ($name == 'hash') {
                $SIGN_REQ = $value;
            }
        }

        return [$reqparams, $SIGN_REQ];
    }

    public function signatureVerification($Request = null, $NOTIFY_PWD = null) {
        // Список параметров и подпись

        if($Request == null) $Request = $this->getReqParams();
        if($NOTIFY_PWD == null) $NOTIFY_PWD = json_decode($this->getKey())->key;

// Base64 encoded ключ для уведомлений webhook (метод /hook/{hookId}/key)

//        $NOTIFY_PWD = "AGIv/0hT8R4WbQU4m4a5WHIScvblWXX/duI5T/4bnN0=";

// Вычисляем хэш SHA-256 строки параметров и шифруем с ключом для уведомлений

        $reqres = hash_hmac("sha256", $Request[0], base64_decode($NOTIFY_PWD));

// Проверка подписи запроса

        if(hash_equals($reqres, $Request[1])) return true;

        return false;
    }

    public function handler() {
        $content = trim(file_get_contents("php://input"));
        file_put_contents(public_path()."/json/qiwi_pay.json", $content);
        if($this->signatureVerification($this->getReqParams())) {
            $error = array('response' => 'OK');

            $params = $this->getReqParams();

            $request = $this->request;

            $comment = explode('__', $request['payment']['comment']);

            PerformAction::pay($comment[0], $request['payment']['sum']['amount'], $comment[1]);
        }
        else {
            $error = array('response' => 'error');
        }

        header('Content-Type: application/json');
        $jsonres = json_encode($error);
        echo $jsonres;
    }

    public function getWebhook() {
        $url = "https://edge.qiwi.com/payment-notifier/v1/hooks/active";
        $curl = new Curl();
        $res = $curl->GET($url, $this->headers);
        return $res;
    }

    public function deleteWebhook() {
        $url = "https://edge.qiwi.com/payment-notifier/v1/hooks/".$this->webhookId;
        $curl = new Curl();
        $res = $curl->DELETE($url, $this->headers);
        return $res;
    }

    public function setWebhook($url) {
        $url = "https://edge.qiwi.com/payment-notifier/v1/hooks?hookType=1&param=".urlencode($url)."&txnType=0";
        $curl = new Curl();
        $res = $curl->PUT($url, $this->headers);
        return $res;
    }

    public function newKey() {
        $url = "https://edge.qiwi.com/payment-notifier/v1/hooks/".$this->webhookId."/newkey";
        $curl = new Curl();
        $res = $curl->POST($url, null, $this->headers);
        return $res;
    }

    public function getKey() {
        $url = "https://edge.qiwi.com/payment-notifier/v1/hooks/".$this->webhookId."/key";
        $curl = new Curl();
        $res = $curl->GET($url, $this->headers);
        return $res;
    }

    public function testWebhook() {
        $url = "https://edge.qiwi.com/payment-notifier/v1/hooks/test";
        $curl = new Curl();
        $res = $curl->GET($url, $this->headers);
        return $res;
    }

    private function createInvoice($amount, $comment) {
        $url = "https://edge.qiwi.com/checkout-api/invoice/create";
        $curl = new Curl();
        $res = $curl->POST($url, json_encode([
            'amount' => $amount,
            'comment' => $comment,
            'customers' => [],
            'public_key' => $this->publicKey
        ]), [
            'Content-Type: application/json'
        ]);
        return $res;
    }

    public function getUrlInvoice($amount, $comment, $successUrl = "") {
        $invoice = json_decode($this->createInvoice($amount, $comment), true);
        if(isset($invoice['invoice_uid'])) {
            $link = "https://oplata.qiwi.com/form/?invoice_uid=".$invoice['invoice_uid'];
            if($successUrl != null) {
                $link .= "&successUrl=". urlencode($successUrl);
            }
            return $link;
        }
        return null;
    }
}
