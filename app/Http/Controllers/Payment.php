<?php


namespace App\Http\Controllers;


use App\models\API\Payment\PayPal\PayPal;
use App\models\API\Payment\QIWI;
use App\models\API\Payment\WebMoney;
use App\models\API\Payment\YandexMoney;
use App\models\BotUsers;
use App\models\PaymentData;

class Payment extends Controller {
    private $paymentData;

    public function __construct() {
        $this->paymentData = PaymentData::first();
    }

    public function qiwiHandler() {
        $qiwi = new QIWI(
            $this->paymentData->qiwi_token,
            $this->paymentData->qiwi_webhook_id,
            $this->paymentData->qiwi_public_key);
        $qiwi->handler();
    }

    public function yandexHandler() {
        $ym = new YandexMoney(
            $this->paymentData->yandex_money_secret_key,
            $this->paymentData->yandex_money_wallet);
        $ym->handler();
    }

    public function webmoneyHandler() {
        $wm = new WebMoney(
            $this->paymentData->webmoney_secret_key,
            $this->paymentData->webmoney_wallet
        );
        $wm->handler();
    }

    public function paypalHandler() {
        $paypal = new PayPal("facilitator", "RUB");
        $paypal->handler();
    }
}
