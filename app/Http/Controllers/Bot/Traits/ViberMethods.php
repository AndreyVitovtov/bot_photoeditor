<?php

namespace App\Http\Controllers\Bot\Traits;


use App\models\API\Viber;
use App\models\BotUsers;
use App\models\buttons\Buttons;
use App\models\Visit;

trait ViberMethods {

    public function __construct() {
        $this->bot = new Viber(VIBER_TOKEN);
        $this->chat = $this->bot->getId();

        if(! $this->chat) return response ('OK', 200)
            ->header('Content-Type', 'text/plain');

        $this->setType();

        /* COUNT VISITS */
        $visit = new Visit();
        $visit->add(date("Y-m-d"), $this->getUserId());
    }

    public function setUserId(): void {
        $botUsers = new BotUsers();
        $res = $botUsers->where('chat', $this->chat)->first();
        if(empty($res)) {
            $name = $this->bot->getName();
            if(empty($name['first_name'])) {
                $name['first_name'] = "test";
            }
            if(empty($name['last_name'])) {
                $name['last_name'] = "test";
            }
            if(empty($name['username'])) {
                $name['username'] = "test";
            }

            $botUsers->chat = $this->chat;
            $botUsers->first_name = $name['first_name'];
            $botUsers->last_name = $name['last_name'];
            $botUsers->username = $name['username'];
            $botUsers->country = $this->bot->getCountry();
            $botUsers->date = date("Y-m-d");
            $botUsers->time = date("H:i:s");
            $botUsers->save();
            $userId = $botUsers->id;
        }
        else {
            $userId = $res['id'];
        }

        $this->userId = $userId;
    }

    private function setType() {
        $this->type = $this->getTypeReq();
    }

    private function getTypeReq():? string {
        $req = json_decode($this->getRequest());
        if(isset($req->message->type)) {
            return $req->message->type; //text, picture
        }
        return null;
    }

    public function getDataByType() {
        $request = json_decode($this->getRequest());

        if(empty($request)) return null;

        if($this->type == "text") {
            $data = [
                'message_id' => $request->message_token,
                'text' => $request->message->text
            ];
        }
        elseif($this->type == "picture") {
            $data = [
                'message_id' => $request->message->message_id,
                'picture' => [
                    [
                        'media' => $request->message->media,
                        'thumbnail' => $request->message->thumbnail,
                        'file_name' => $request->message->file_name
                    ]
                ]
            ];
        }
        else {
            $data = [
                'message_id' => $request->message_token,
                'data' => null
            ];
        }

        return $data;
    }

    public function getMethodName(): ?string {
        $data = $this->getDataByType();
        if($this->type == "text") {
            return trim($data['text']);
        }
        else {
            return null;
        }
    }

    public function unknownTeam() {
        $buttons = new Buttons();
        $this->send("{unknown_team}", [
            'buttons' => $buttons->main_menu(),
            'input' => 'regular'
        ]);
    }
}
