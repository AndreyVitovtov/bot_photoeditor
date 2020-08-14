<?php

namespace app\models\API;

class Telegram {
    protected $token;
    private $request = null;

    public function __construct($token) {
        $this->request = json_decode(file_get_contents('php://input'));
        $this->token = $token;
    }
    public function getId(): ?string {
        if(isset($this->request->message->chat->id)) {
            return $this->request->message->chat->id;
        }
        elseif(isset($this->request->callback_query->message->chat->id)) {
            return $this->request->callback_query->message->chat->id;
        }
        elseif(isset($this->request->channel_post->chat->id)) {
            return $this->request->channel_post->chat->id;
        }
        else {
            return null;
        }
    }

    public function getName(): ?array {
        if(isset($this->request->message->from->username)) {
            if(isset($this->request->message->chat->last_name)) {
                $last_name = $this->request->message->chat->last_name;
            }
            else {
                $last_name = "no name";
            }

            if(isset($this->request->message->chat->first_name)) {
                $first_name = $this->request->message->chat->first_name;
            }
            else {
                $first_name = "no name";
            }

            return [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $this->request->message->chat->username
            ];
        }
        elseif(isset($this->request->callback_query->message->chat->username)) {
            if(isset($this->request->callback_query->message->chat->last_name)) {
                $last_name = $this->request->callback_query->message->chat->last_name;
            }
            else {
                $last_name = "no name";
            }

            if(isset($this->request->callback_query->message->chat->first_name)) {
                $first_name = $this->request->callback_query->message->chat->first_name;
            }
            else {
                $first_name = "no name";
            }

            return [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $this->request->callback_query->message->chat->username
            ];
        }
        else {
            return null;
        }
    }

    public function getRequest(): ?string {
        return json_encode($this->request);
    }

    public function getMessage(): ?string {
        if(isset($this->request->message->text)) {
            return $this->request->message->text;
        }
        elseif(isset($this->request->callback_query->data)) {
            return $this->request->callback_query->data;
        }
        else {
            return null;
        }
    }

    public function sendMessage(?string $chat, string $content, array $params = []): string {
        if(empty($params['parse_mode'])) {
            $parse_mode = 'HTML';
        }
        $data = [
            'text' => $content,
            'chat_id' => $chat,
            'parse_mode' => $parse_mode,
            'disable_web_page_preview' => true
        ];

        if(!empty($params['buttons']))  {
            $data['reply_markup'] = [
                'keyboard' => $params['buttons'],
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'parse_mode' => 'HTML',
                'selective' => true
            ];
        }

        if(!empty($params['inlineButtons'])) {
            if(isset($data['reply_markup'])) {
                $data['reply_markup']['inline_keyboard'] = $params['inlineButtons'];
                $data['reply_markup']['resize_keyboard'] = true;
            }
            else {
                $data['reply_markup'] = [
                    'inline_keyboard' => $params['inlineButtons'],
                    'resize_keyboard' => true
                ];
            }
        }

        if(!empty($params['hideKeyboard'])) {
            if(isset($data['reply_markup'])) {
                $data['reply_markup']['hide_keyboard'] = $params['hideKeyboard'];
            }
            else {
                $data['reply_markup'] = [
                    'hide_keyboard' => $params['hideKeyboard']
                ];
            }
        }
        return $this->makeRequest('sendMessage', $data);
    }

    public function sendPhoto($chat, $imgUrl, $caption = null, $params = []) {
        $data = [
            'chat_id' => $chat,
            'photo' => $imgUrl,
            'caption' => $caption,
            'parse_mode' => "Markdown"
        ];

        if(!empty($params['buttons']))  {
            $data['reply_markup'] = json_encode([
                'keyboard' => $params['buttons'],
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'parse_mode' => 'HTML',
                'selective' => true
            ]);
        }

        if(!empty($params['inlineButtons'])) {
            $data['reply_markup'] = json_encode([
                'inline_keyboard' => $params['inlineButtons'],
                'resize_keyboard' => false
            ]);
        }

        $bot_url    = "https://api.telegram.org/bot".$this->token."/sendPhoto";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: multipart/form-data"
        ]);
        curl_setopt($ch, CURLOPT_URL, $bot_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function deleteMessage($chat, $messageId) {
        return $this->makeRequest('deleteMessage', [
            'chat_id' => $chat,
            'message_id' => $messageId
        ]);
    }

    public function answerCallbackQuery($callback_query_id, $text) {
        return $this->makeRequest('answerCallbackQuery', [
            'callback_query_id' => $callback_query_id,
            'text' => $text,
            'show_alert' => 'true'
        ]);
    }

    public function setWebhook(string $uri): string {
        return $this->makeRequest('setWebhook', [
            'url' => $uri
        ]);
    }

    public function getWebhook(): string {
        return $this->makeRequest('getWebhookInfo', []);
    }

    public function sendChatAction($chat, $action = "typing") {
// typing for text messages,
// upload_photo for photos,
// record_video or upload_video for videos,
// record_audio or upload_audio for audio files,
// upload_document for general files,
// find_location for location data,
// record_video_note or upload_video_note for video notes
        return $this->makeRequest('sendChatAction', [
            'chat_id' => $chat,
            'action' => $action
        ]);
    }

    public function getFilePath($fileId) {
        $filePath = file_get_contents("https://api.telegram.org/bot$this->token/getFile?file_id=$fileId");
        $filePath = json_decode($filePath, true);
        $filePath = $filePath['result']['file_path'];
        return "https://api.telegram.org/file/bot$this->token/$filePath";
    }

    private function makeRequest($method, $data) {
        $url = "https://api.telegram.org/bot".$this->token . "/" . $method;
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function editMessageText($chat, $messageId, $text, $inlineKeyboard = null) {
        $parse_mode = 'HTML';

        $data = [
            'text' => $text,
            'chat_id' => $chat,
            'message_id' => $messageId,
            'parse_mode' => $parse_mode,
            'disable_web_page_preview' => true
        ];

        if($inlineKeyboard != null) {
            $data['reply_markup'] = [
                'inline_keyboard' => $inlineKeyboard,
                'resize_keyboard' => true
            ];
        }
        return $this->makeRequest('editMessageText', $data);
    }

    public function editMessageMedia($chat, $messageId, $media, $caption = '', $inlineKeyboard = null) {
        $data = [
            'chat_id' => $chat,
            'message_id' => $messageId,
            'media' => [
                'type' => 'photo',
		        'media' => $media
            ],
            'caption' => $caption,
            'parse_mode' => "Markdown"
        ];

        if($inlineKeyboard != null) {
            $data['reply_markup'] = [
                'inline_keyboard' => $inlineKeyboard,
                'resize_keyboard' => false
            ];
        }

        return $this->makeRequest('editMessageMedia', $data);
    }

    public function sendDocument($chat, $document, $caption = "", $params = []) {
        $data = [
            'chat_id' => $chat,
            'document' => $document,
            'caption' => $caption,
            'parse_mode' => "Markdown"
        ];

        if(!empty($params['inlineButtons'])) {
            $data['reply_markup'] = json_encode([
                'inline_keyboard' => $params['inlineButtons'],
                'resize_keyboard' => false
            ]);
        }

        if(!empty($params['buttons']))  {
            $data['reply_markup'] = [
                'keyboard' => $params['buttons'],
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'parse_mode' => 'HTML',
                'selective' => true
            ];
        }

        return $this->makeRequest('sendDocument', $data);
    }

    public function sendFile($chat, $url, $fileName = null, $caption = "") {
        if($fileName == null) {
            $fileName = basename($url);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);
        file_put_contents(basename($url), $html);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL =>  'https://api.telegram.org/bot'.$this->token.'/sendDocument?caption='.$caption.'&chat_id='.$chat,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: multipart/form-data'
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'document' => curl_file_create(basename($url), mime_content_type(basename($url)), $fileName)
            ]
        ]);
        $data = curl_exec($curl);
        curl_close($curl);

        return $data;
    }

    public function getType() {
        $obj = json_decode($this->getRequest());
        $arrProperties = $this->getProperties($obj);
        return $this->getTypeReq($arrProperties);
    }

    private function getProperties($obj, $names = []): array {
        if(is_object($obj) || is_array($obj)) foreach ($obj as $name => $el) {
            $names[$name] = $name;
            if (is_object($el) || is_array($el)) {
                $names = $this->getProperties($el, $names);
            }
        }
        return $names;
    }

    private function getTypeReq($arrProperties = null):? string {
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

    public function forwardMessage($chat, $from_chat_id, $message_id) {
        $data = array(
            'chat_id' => $chat,
            'from_chat_id' => $from_chat_id,
            'message_id' => $message_id
        );

        return $this->makeRequest('forwardMessage', $data);
    }

    public function sendLocation($chat, $lat, $lng) {
        $data = array(
            'chat_id' => $chat,
            'latitude' => $lat,
            'longitude' => $lng
        );

        return $this->makeRequest('sendLocation', $data);
    }

    public function sendSticker($chat, $idSticker) {
        $data = array(
            'chat_id' => $chat,
            'sticker' => $idSticker
        );

        return $this->makeRequest('sendSticker', $data);
    }

    public function sendContact($chat, $phone, $name, $params = []) {
        $data = [
            'chat_id' => $chat,
            'phone_number' => $phone,
            'first_name' => $name
        ];

        if(!empty($params['inlineButtons'])) {
            $data['reply_markup'] = json_encode([
                'inline_keyboard' => $params['inlineButtons'],
                'resize_keyboard' => false
            ]);
        }

        if(!empty($params['buttons']))  {
            $data['reply_markup'] = [
                'keyboard' => $params['buttons'],
                'resize_keyboard' => true,
                'one_time_keyboard' => false,
                'parse_mode' => 'HTML',
                'selective' => true
            ];
        }

        return $this->makeRequest('sendContact', $data);
    }

    public function editMessageReplyMarkup($chat, $messageId, $reply_markup = null) {
        return $this->makeRequest('editMessageReplyMarkup', [
            'chat_id' => $chat,
            'message_id' => $messageId,
            'reply_markup' => $reply_markup
        ]);
    }

    public function getChatMember($idUser, $chat) {
        return $this->makeRequest('getChatMember', [
            'chat_id' => $chat,
            'user_id' => $idUser
        ]);
    }

    public function payment($data) {
        $data = [
            "chat_id" => $data['chat'],
            "title" => $data['title'],
            "description" => $data['description'],
            "payload" => $data['payload'],
            "provider_token" => $data['provider_token'],
            "start_parameter" => $data['start_parameter'],//foo
            "currency" => $data['currency'],//UAH
            "prices" => [
                [
                    'label' => $data['price']['label'],
                    'amount' => $data['price']['amount']//100 - 1грн.
                ]
            ],
            //Необязательные
            "need_name" => true,
            //"need_email" => "",
            "need_phone_number" => true
            //"reply_markup" => $reply_markup
        ];

        return $this->makeRequest('sendInvoice', $data);
    }

    public function answerPreCheckoutQuery($pre_checkout_query_id, $ok = true, $error_message = null) {
        $data = [
            "pre_checkout_query_id" => $pre_checkout_query_id,
            "ok" => $ok,
            "error_message" => $error_message
        ];

        return $this->makeRequest('answerPreCheckoutQuery', $data);
    }
}
