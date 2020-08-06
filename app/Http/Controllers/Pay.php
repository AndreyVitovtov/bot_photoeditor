<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\models\API\Payment\PayPal\PayPal;
use App\models\API\Payment\QIWI;
use App\models\API\Payment\WebMoney;
use App\models\API\Payment\YandexMoney;
use App\models\BotUsers;
use App\models\Message;
use App\models\PaymentData;
use Exception;
use Illuminate\Http\Request;

class Pay extends Controller {
    public function method($messenger, $id, $amount = null, $purpose = null) {
        $view = view("payment.index");
        $view->id = $id;
        $view->messenger = $messenger;
        $view->amount = $amount;
        $view->purpose = $purpose;
        $user = $user = BotUsers::find($id);
        if($user->language == '0') {
            $view->texts = json_decode(file_get_contents(public_path()."/json/pages.json"));
        }
        else {
            $view->texts = json_decode(file_get_contents(public_path()."/json/pages_".$user->language.".json"));
        }
        return $view;
    }

    public function invoice(InvoiceRequest $request) {
        $view = view("payment.invoice");
        $user = BotUsers::find($request->post('id'));
        if($user->language == '0') {
            $view->texts = json_decode(file_get_contents(public_path()."/json/pages.json"));
        }
        else {
            $view->texts = json_decode(file_get_contents(public_path()."/json/pages_".$user->language.".json"));
        }
        $payData = null;
        $paySystem = null;
        $currency = $view->texts->payment_currency;
        $description = $request->post('purpose') != null ? $request->post('purpose') : "Платный доступ";

        $paymentData = PaymentData::first();

        if($request->post('pay_system') == "qiwi") {
            $qiwi = new QIWI($paymentData->qiwi_token, $paymentData->qiwi_webhook_id, $paymentData->qiwi_public_key);
            $payData = $qiwi->getUrlInvoice(
                $request->post('amount'),
                $request->post('id')."__".$description
            );
            $payData = "<a href='$payData' class='button'>{$view->texts->payment_pay}</a>";
            $paySystem = "QIWI";
        }
        elseif($request->post('pay_system') == "yandex") {
            $ym = new YandexMoney($paymentData->yandex_money_secret_key, $paymentData->yandex_money_wallet);
            $payData = $ym->getUrlInvoice(
                $request->post('amount'),
                $request->post('id')."__".$description,
                $request->post('id'),
                url("/payment/yandex_money/success")
            );
            $payData = "<a href='$payData' class='button'>{$view->texts->payment_pay}</a>";
            $paySystem = "Yandex Money";
        }
        elseif($request->post('pay_system') == "webmoney") {
            $wm = new WebMoney($paymentData->webmoney_secret_key, $paymentData->webmoney_wallet);
            $payData = $wm->getForm(
                $request->post('amount'),
                $request->post('id')."__".$description,
                time(),
                $request->post('id'),
                $view->texts->payment_pay
            );
            $paySystem = "WebMoney";
        }
        elseif($request->post('pay_system') == "paypal") {
            $paypal = new PayPal();
            $payData = $paypal->getLink(
                $request->post('amount'),
                $description,
                $request->post('id'),
                url('/payment/paypal/success')
            );
            $payData = "<a href='$payData' class='button'>{$view->texts->payment_pay}</a>";
            $paySystem = "PayPal";
        }

        $view->messenger = $request->post('messenger');
        $view->username = $user->username;
        $view->email = $request->post('email');
        $view->phone = $request->post('phone');
        $view->paySystem = $paySystem;
        $view->description = $description;
        $view->amount = $request->post('amount');
        $view->currency = $currency;
        $view->payData = $payData;
        return $view;
    }
}
