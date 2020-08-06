<?php


namespace App\models\API\Payment;


use App\models\PerformAction;

class WebMoney {
    private $secret;
    private $wallet;

    public function __construct($secret, $wallet) {
        $this->secret = $secret;
        $this->wallet = $wallet;
    }

    public function handler() {
        $content = trim(file_get_contents("php://input"));
        file_put_contents(public_path()."/json/wm_pay.txt", $content);

        $res = $this->getReqParams();
        if($res == null) {
            $this->returnError();
        }
        else {
            if(!$this->secretСheck($res)) {
                $this->returnError();
            }
            else {
                if($this->verificationSignature($res)) {
                    $params = explode("__", $res['LMI_PAYMENT_DESC']);
                    PerformAction::pay($params[0], $res['amount'], $params[1]);

                    header('Content-Type: application/json');
                    echo json_encode([
                        'response' => 'OK'
                    ]);
                }
                else {
                    $this->returnError();
                }
            }
        }
    }

    private function returnError() {
        header('Content-Type: application/json');
        echo json_encode([
            'response' => 'error'
        ]);
    }

    private function verificationSignature($res) {
        $string = $res['LMI_PAYEE_PURSE'].
            $res['LMI_PAYMENT_AMOUNT'].
            $res['LMI_PAYMENT_NO'].
            $res['LMI_MODE'].
            $res['LMI_SYS_INVS_NO'].
            $res['LMI_SYS_TRANS_NO'].
            $res['LMI_SYS_TRANS_DATE'].
            $res['LMI_SECRET_KEY'].
            $res['LMI_PAYER_PURSE'].
            $res['LMI_PAYER_WM'];
        $hash = hash('SHA256', $string);

        if(strtoupper($hash) == $res['LMI_HASH']) {
            return true;
        }
        else {
            return false;
        }
    }

    private function secretСheck($res) {
        if(isset($res['LMI_SECRET_KEY'])) {
            if($res['LMI_SECRET_KEY'] == $this->secret) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    private function getReqParams() {
        $res = file_get_contents("php://input");
        if($res == "") return null;
        parse_str($res, $res);
        return $res;
    }

    public function getForm($amount, $description, $paymentNo, $id, $buttonName = "PAY") {
        return '<form method="POST" action="https://merchant.webmoney.ru/lmi/payment_utf.asp" accept-charset="utf-8">
            <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="'.$amount.'">
            <input type="hidden" name="LMI_PAYMENT_DESC" value="'.$description.'">
            <input type="hidden" name="LMI_PAYMENT_NO" value="'.$paymentNo.'">
            <input type="hidden" name="LMI_PAYEE_PURSE" value="'.$this->wallet.'">
            <input type="hidden" name="LMI_SIM_MODE" value="0">
            <input type="hidden" name="LMI_MODE" value="1">
            <input type="hidden" name="id" value="'.$id.'">
            <input type="hidden" name="amount" value="'.$amount.'">
            <input type="submit" value="'.$buttonName.'" class="button">
        </form>';
    }
}
