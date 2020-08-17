<?php


namespace App\models\buttons;


use App\models\BotUsers;

class ButtonsTelegram {

    public static function main_menu($userId) {
        $user = BotUsers::find($userId);

        if($user->access == '1') {
            if($user->access_free == '1') {
                return [
                    ["{process_photo}"],
                    ["{paid_access}"],
                    ["{contacts}"],
                    ["{group}"],
                    ["{languages}"]
                ];
            }
            else {
                return [
                    ["{process_photo}"],
                    ["{contacts}"],
                    ["{group}"],
                    ["{languages}"]
                ];
            }
        }
        else {
            return [
                ["{process_photo}"],
                ["{free_access}"],
                ["{paid_access}"],
                ["{contacts}"],
                ["{group}"],
                ["{languages}"]
            ];
        }
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

    public function moreBack($page = 1) {
        $countButtons = 6;
        $nextPage = $page+1;
        $count = count(json_decode(file_get_contents(public_path()."/json/_dict.json"), true));

        if($count > $countButtons) {
            if(ceil($count / $countButtons) > $page) {
                return [
                    ["{more}"],
                    ["{back}"]
                ];
            }
        }
        return $this->back();
    }

    public function processAnotherPhoto() {
        return [
            ["{process_another_photo}"],
            ["{back}"]
        ];
    }
}
