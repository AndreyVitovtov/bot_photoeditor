<?php


namespace App\models\API\Payment;


use App\models\PerformAction;

class YandexMoney {
//    const SECRET = "ASbiXBPRhMx3SRA54cSK4bEI";

    private $secret;
    private $wallet;

    public function __construct($secret, $wallet) {
        $this->secret = $secret;
        $this->wallet = $wallet;
    }

    public function handler() {
        $request = $this->getRequest();
        if($request == null) {
            $response = ['response' => 'error'];
        }
        else {
            if($this->authenticationAndIntegrity($request)) {
                $params = explode("__", $request['label']);
                PerformAction::pay($params[0], $request['withdraw_amount'], $params[1]);
                $response = ['response' => 'OK'];
            }
            else {
                $response = ['response' => 'error'];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    public function getUrlInvoice($amount, $description, $comment, $successUrl) {
        return "https://money.yandex.ru/quickpay/confirm.xml?receiver=".$this->wallet."&quickpay-form=shop&targets=".$description."&paymentType=PC&sum=".$amount."&comment=".$description."&successURL=".$successUrl."&details=".$description."&label=".$description;
    }

    private function getRequest() {
        $content_url = trim(file_get_contents("php://input"));
        parse_str($content_url, $content);
        file_put_contents(public_path()."/json/ym_pay.txt", $content_url);
        if(count($content) == 0) return null;

        return $content;
    }

    private function  authenticationAndIntegrity($request){
        $str = $request['notification_type'].
            "&".$request['operation_id'].
            "&".$request['amount'].
            "&".$request['currency'].
            "&".$request['datetime'].
            "&".$request['sender'].
            "&".$request['codepro'].
            "&".$this->secret.
            "&".$request['label'];

        $sha1 = hash("sha1", $str);

        if($request['sha1_hash'] == $sha1) {
            return true;
        }
        else {
            return false;
        }
    }
}
