<?php


namespace App\Http\Controllers\Bot\Traits;


use app\models\API\FacebookMessenger;
use App\models\buttons\Buttons;

trait FacebookMessengerMethods {

    public function __construct() {
        $this->bot = new FacebookMessenger(FACEBOOK_MESSENGER_TOKEN);
        $this->chat = $this->bot->getId();

        if(! $this->chat) {
            return response ('OK', 200)->header('Content-Type', 'text/plain');
        }

        $this->setType();

        /* COUNT VISITS */
        $visit = new Visit();
        $visit->add(date("Y-m-d"), $this->getUserId());
    }


    private function setType() {
        $obj = json_decode($this->getRequest());
        $arrProperties = $this->getProperties($obj);
        $this->type = $this->getTypeReq($arrProperties);
    }

    private function getTypeReq($arrProperties): string {
        $rules = [
            'postback' => 'postback',
            'quick_reply' => 'quick_reply',
            'message' => 'message'
        ];

        foreach($rules as $type => $rule) {
            if(array_key_exists($rule, $arrProperties)) return $type;
        }
        return 'other';
    }

    private function getProperties($obj, $names = []): array {
        if(is_object($obj)) foreach ($obj as $name => $el) {
            $names[$name] = $name;
            if (is_object($el) || is_array($el)) {
                $names = $this->getProperties($el, $names);
            }
        }
        return $names;
    }

    public function getDataByType() {
        $request = json_decode($this->getRequest());

        if($this->type == "quick_reply") {
            $data = [
                'message_id' => $request->entry[0]->id,
                'text' => $request->entry[0]->messaging[0]->message->quick_reply->payload
            ];
        }
        elseif($this->type == "payload") {
            $data = [
                'message_id' => $request->entry[0]->id,
                'text' => $request->entry[0]->messaging[0]->message->text
            ];
        }
        elseif($this->type == "message") {
            $data = [
                'message_id' => $request->entry[0]->id,
                'text' => $request->entry[0]->messaging[0]->message->text
            ];
        }
        elseif($this->type == "postback") {
            $data = [
                'message_id' => $request->entry[0]->id,
                'text' => $request->entry[0]->messaging[0]->postback->payload
            ];
        }
        else {
            $data = [
                'message_id' => $request->entry[0]->id,
                'data' => null
            ];
        }

        return $data;
    }

    private function getMethodName(): ?string {
        $data = $this->getDataByType();
        if($this->type == "message" || $this->type == "postback" || $this->type == "quick_reply" || $this->type == "payload") {
            $name = trim(trim($data['text'], "/"));
        }

        if($name == "Начать" || $name == "начать") {
            $name = "start";
        }

        if(isset($name)) {
            if($rname = $this->getCommandFromMessage($name)) {
                return $rname;
            }
            else {
                if(!empty($name)) {
                    return $name;
                }
                return null;
            }
        }
        else {
            return null;
        }
    }

    private function getCommandFromMessage(string $message): ?string {
        $json = file_get_contents(public_path("buttons.json"));
        $array = json_decode($json, true);
        if($key = array_search($message, $array)) {
            return $key;
        }
        else {
            return null;
        }
    }

    public function unknownTeam() {
        $this->send("{unknown_team}", [
            'buttons' => Buttons::main_menu()
        ]);
    }
}
