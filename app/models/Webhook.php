<?php


namespace App\models;


use App\models\API\Telegram;
use App\models\API\Viber;

class Webhook {
    public function set() {
        $uri = "https://".$_SERVER['HTTP_HOST']."/bot/index";

        if(defined("VIBER_TOKEN")) {
            $viber = new Viber(VIBER_TOKEN);
            $viber->setWebhook($uri);
        }

        if(defined("TELEGRAM_TOKEN")) {
            $telegram = new Telegram(TELEGRAM_TOKEN);
            $telegram->setWebhook($uri);
        }
    }

    public function setChatBot() {
        if(defined("VIBER_CHAT_TOKEN")) {
            $uri = "https://".$_SERVER['HTTP_HOST']."/bot/chat";

            $viber = new Viber(VIBER_CHAT_TOKEN);
            $viber->setWebhook($uri);
        }

        if(defined("TELEGRAM_CHAT_TOKEN")) {
            $uri = "https://".$_SERVER['HTTP_HOST']."/bot/chat";

            $telegram = new Telegram(TELEGRAM_CHAT_TOKEN);
            dd($telegram->setWebhook($uri));
        }
    }
}
