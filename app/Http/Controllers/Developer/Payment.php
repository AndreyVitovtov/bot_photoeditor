<?php


namespace App\Http\Controllers\Developer;


use App\Http\Controllers\Controller;
use App\models\API\Payment\QIWI;
use App\models\PaymentData;
use Illuminate\Http\Request;

class Payment extends Controller {
    public function qiwi() {
        $view = view('developer.payment.qiwi-index');
        $view->menuItem = "payqiwi";
        $paymentData = PaymentData::first();
        if($paymentData != null) {
            $view->token = $paymentData->qiwi_token;
            $view->webhookId = $paymentData->qiwi_webhook_id;
            $view->publicKey = $paymentData->qiwi_public_key;
        }

        return $view;
    }

    public function yandex() {
        $view = view('developer.payment.yandex-index');
        $view->menuItem = "payyandex";
        $paymentData = PaymentData::first();
        if($paymentData != null) {
            $view->secret = $paymentData->yandex_money_secret_key;
            $view->wallet = $paymentData->yandex_money_wallet;
        }
        return $view;
    }

    public function webmoney() {
        $view = view('developer.payment.webmoney-index');
        $view->menuItem = "paywebmoney";
        $paymentData = PaymentData::first();
        if($paymentData != null) {
            $view->secret = $paymentData->webmoney_secret_key;
            $view->wallet = $paymentData->webmoney_wallet;
        }
        return $view;
    }

    public function paypal() {
        $view = view('developer.payment.paypal-index');
        $view->menuItem = "paypaypal";
        $paymentData = PaymentData::first();
        if($paymentData != null) {
            $view->facilitator = $paymentData->paypal_facilitator;
        }
        return $view;
    }

    public function qiwiSave(Request $request) {
        $token = trim($request->post('token'));
        $paymentData = PaymentData::first();
        if($paymentData == null) {
            $paymentData = new PaymentData();
        }

        $qiwi = new QIWI($token);
        $res = json_decode($qiwi->setWebhook(url('/payment/qiwi/handler')));

        if(isset($res->errorCode)) {
            if($res->errorCode == "hook.already.exists") {
                $res = json_decode($qiwi->getWebhook());
                if(isset($res->hookId)) {
                    $qiwi = new QIWI($token, $res->hookId);
                    $qiwi->deleteWebhook();
                    $res = json_decode($qiwi->setWebhook(url('/payment/qiwi/handler')));
                    $webhookId = $res->hookId;
                }
            }
        }
        else {
            $webhookId = $res->hookId;
        }
        $paymentData->qiwi_token = $token;
        $paymentData->qiwi_webhook_id = $webhookId;
        $paymentData->qiwi_public_key = trim($request->post('publicKey'));
        $paymentData->save();
        return redirect()->to(route('qiwi'));
    }

    public function yandexSave(Request $request) {
        $paymentData = PaymentData::first();
        if($paymentData == null) {
            $paymentData = new PaymentData();
        }
        $paymentData->yandex_money_secret_key = $request->post('secret');
        $paymentData->yandex_money_wallet = $request->post('wallet');
        $paymentData->save();
        return redirect()->to(route('yandex'));
    }

    public function webmoneySave(Request $request) {
        $paymentData = PaymentData::first();
        if($paymentData == null) {
            $paymentData = new PaymentData();
        }
        $paymentData->webmoney_wallet = $request->wallet;
        $paymentData->webmoney_secret_key = $request->secret;
        $paymentData->save();
        return redirect()->to(route('webmoney'));
    }

    public function paypalSave(Request $request) {
        $paymentData = PaymentData::first();
        if($paymentData == null) {
            $paymentData = new PaymentData();
        }
        $paymentData->paypal_facilitator = $request->facilitator;
        $paymentData->save();
        return redirect()->to(route('paypal'));
    }
}
