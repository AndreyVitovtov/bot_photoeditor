<?php


namespace App\Services;


use App\models\BotUsers;
use App\models\RefSystem;

class RefServices {

    public function add($chat) {
        $referral = $this->getUserId();
        $referrer = BotUsers::where('chat', $chat)->first();

        if($referrer->id != $referral) {
            RefSystem::insert([
                'referrer' => $referrer->id,
                'referral' => $referral,
                'date' => date("Y-m-d"),
                'time' => date("H:i:s")
            ]);
        }
    }


}
