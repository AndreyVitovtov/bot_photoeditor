<?php


namespace App\models;

class PerformAction {
    public static function pay($id, $amount, $context) {
        if($context == "create_chat") {
            $paymentCreateChat = new PaymentCreateChat();
            $paymentCreateChat->users_id = $id;
            $paymentCreateChat->amount = $amount;
            $paymentCreateChat->count = COUNT_CHATS_FOR_ONE_PAYMENT;
            $paymentCreateChat->date = date("Y-m-d");
            $paymentCreateChat->time = date("H:i:s");
            $paymentCreateChat->save();

            $mess = new Message();
            $user = BotUsers::find($id);
            $mess->send($user->messenger, $user->chat, "{chat_creation_fee_received}");
        }
        elseif($context = "mailing_chat") {
            $paymentMailingChat = new PaymentMailingChat();
            $paymentMailingChat->chats_id = $id;
            $paymentMailingChat->type = 'paid';
            $paymentMailingChat->amount = $amount;
            $paymentMailingChat->count = COUNT_MAILING_FOR_ONE_PAYMENT;
            $paymentMailingChat->date = date('Y-m-d');
            $paymentMailingChat->date = date('H:i:s');
            $paymentMailingChat->save();

            $mess = new Message();
            $chat = Chat::find($id);
            $user = BotUsers::find($chat->users_id);
            $mess->send($user->messenger, $user->chat, "{payment_for_mailing_received}");
        }
    }
}
