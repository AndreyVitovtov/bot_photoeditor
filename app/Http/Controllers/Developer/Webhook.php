<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Http\Requests\SetWebhookRequest;
use App\models\API\Viber;
use App\models\API\Telegram;

class Webhook extends Controller {
    public function index() {
        //TODO: [DEV] GET WEBHOOK
        $viber = new Viber(VIBER_TOKEN);
        $webhook =  $viber->getWebhook();
        if(!empty($webhook)) {
            $webhook = json_decode($webhook);
            if(isset($webhook->result->url)) {
                $uri = $webhook->result->url;
            }
            else {
                $uri ="";
            }
        }
        else {
            $uri = "";
        }
        $view = view('developer.webhook.webhook');
        $view->uri = $uri;
        $view->menuItem = "webhook";
        return $view;
    }

    public function setWebhook(SetWebhookRequest $request) {
        //TODO: [DEV] SET WEBHOOK
        $uri = $request->post('uri');
        $viber = new Viber(VIBER_TOKEN);
        $viber->setWebhook($uri);
        return redirect()->to('developer/webhook');
    }
}
