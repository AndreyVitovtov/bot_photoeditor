<?php


namespace App\models\buttons;


use App\models\BotUsers;

class ButtonsTelegram {

    public static function main_menu($userId) {
//        $user = BotUsers::find($userId);

        return [
            ["{process_photo}"],
            ["{free_access}"],
            ["{paid_access}"],
            ["{contacts}"],
            ["{group}"],
            ["{languages}"]
        ];
    }

    public static function start()
    {
        return [
            ["start"]
        ];
    }

    public function back() {
        return [
            ["{back}"]
        ];
    }
}
