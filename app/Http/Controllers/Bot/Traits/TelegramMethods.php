<?php


namespace App\Http\Controllers\Bot\Traits;


use App\models\API\Telegram;
use App\models\buttons\Buttons;
use App\models\Visit;

trait TelegramMethods {

    public function __construct() {
        $this->bot = new Telegram(TELEGRAM_TOKEN);
        $this->chat = $this->bot->getId();

        if(! $this->chat) {
            return response ('OK', 200)
                ->header('Content-Type', 'text/plain');
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
            'callback_query' => 'callback_query',
            'channel_post' => 'channel_post',
            'text' => 'text',
            'document' => 'document',
            'photo' => 'photo',
            'bot_command' => 'entities'
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

        if(empty($request)) return null;

        if($this->type == "text") {
            $data = [
                'message_id' => $request->message->message_id,
                'text' => $request->message->text
            ];
        }
        elseif($this->type == "document") {
            $data = [
                'message_id' => $request->message->message_id,
                'file_name' => $request->message->document->file_name,
                'mime_type' => $request->message->document->mime_type,
                'file_id' => $request->message->document->file_id,
                'file_unique_id' => $request->message->document->file_unique_id,
                'file_size' => $request->message->document->file_size,
                'thumb' => [
                    'file_id' => $request->message->document->thumb->file_id,
                    'file_unique_id' => $request->message->document->thumb->file_unique_id,
                    'file_size' => $request->message->document->thumb->file_size,
                    'width' => $request->message->document->thumb->width,
                    'height' => $request->message->document->thumb->height
                ]
            ];
        }
        elseif($this->type == "photo") {
            $data = [
                'message_id' => $request->message->message_id,
                'photo' => [
                    [
                        'file_id' => $request->message->photo[0]->file_id,
                        'file_unique_id' => $request->message->photo[0]->file_unique_id,
                        'file_size' => $request->message->photo[0]->file_size,
                        'width' => $request->message->photo[0]->width,
                        'height' => $request->message->photo[0]->height
                    ],
                    [
                        'file_id' => $request->message->photo[1]->file_id,
                        'file_unique_id' => $request->message->photo[1]->file_unique_id,
                        'file_size' => $request->message->photo[1]->file_size,
                        'width' => $request->message->photo[1]->width,
                        'height' => $request->message->photo[1]->height
                    ]
                ]
            ];
        }
        elseif($this->type == "callback_query") {
            $data = [
                'message_id' => $request->callback_query->message->message_id,
                'data' => $request->callback_query->data
            ];
        }
        elseif($this->type == "bot_command") {
            $data = [
                'message_id' => $request->callback_query->message->message_id,
                'text' => $request->callback_query->message->text
            ];
        }
        elseif($this->type == "channel_post") {
            $data = [
                'message_id' => $request->channel_post->message_id,
                'text' => $request->channel_post->text
            ];
        }
        else {
            $data = [
                'message_id' => $request->message->message_id,
                'data' => null
            ];
        }

        return $data;
    }

    public function getMethodName(): ?string {
        $data = $this->getDataByType();
        if($this->type == "text" || $this->type == "bot_command") {
            if(strpos($data['text'], "@")) {
                return trim(explode('@', $data['text'])[0], "/");
            }
            return trim($data['text'], "/");
        }
        elseif($this->type == "callback_query") {
            return trim($data['data'], "/");
        }
        elseif($this->type == "channel_post") {
            return trim($data['text'], "/");
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
