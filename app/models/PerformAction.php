<?php


namespace App\models;

class PerformAction {
    public static function pay($id, $amount, $context) {
        $user = BotUsers::find($id);
        $user->access = '1';
        $user->save();

        $message = new Message();
        $message->send($user->messenger, $user->chat, '{full_access_granted}');
    }
}
