<?php


namespace App\models;

use App\models\buttons\ButtonsTelegram;
use App\models\buttons\ButtonsViber;
use Illuminate\Support\Facades\DB;

class Mailing {

    public $pathTask;
    public $countUsers;
    public $chatPathTask;

    public function __construct() {
        $this->pathTask = public_path()."/json/mailing_task.json";
        $this->chatPathTask = public_path()."/json/mailing_task_chat.json";
        $this->countUsers = 1800;
    }

    public function send(): string {
        if(! file_exists($this->pathTask)) return json_encode([
            'status' => 'fail',
            'message' => 'No task'
        ]);

        $taskJson = file_get_contents($this->pathTask);
        $task = json_decode($taskJson);

        if($task->performed == "true") return json_encode([
            'status' => 'fail',
            'message' => 'Mailing performed'
        ]);

        if($task->chat_holders == "all") {
            $users = DB::select("
                    SELECT id, chat, messenger 
                    FROM users 
                    WHERE messenger LIKE '".$task->messenger."' 
                    AND country LIKE '".$task->country."'
                    LIMIT ".$this->countUsers."
                    OFFSET ".$task->start
            );
        }
        elseif($task->chat_holders == "yes") {
            $users = DB::select("
                    SELECT id, chat, messenger
                        FROM users u
                        JOIN chats c ON c.users_id = u.id
                        WHERE u.messenger LIKE '".$task->messenger."'
                        AND u.country LIKE '".$task->country."'
                        LIMIT ".$this->countUsers."
                        OFFSET ".$task->start
            );
        }
        elseif($task->chat_holders == "no") {
            $db = DB::select("
                    SELECT id, chat, messenger 
                    FROM users u
                    WHERE u.id NOT IN (
                        SELECT id FROM chats
                    ) AND u.messenger LIKE '".$task->messenger."'
                      AND u.country LIKE '".$task->country."'
                    LIMIT ".$this->countUsers."
                    OFFSET ".$task->start
            );
        }

        $task->performed = "true";
        if($task->count <= $this->countUsers) {
            $task->start = $task->count;
        }
        else {
            $task->start += $this->countUsers;
        }
        file_put_contents($this->pathTask, json_encode($task));

        if(empty($users)) {
            unlink($this->pathTask);
            if(isset($task->img)) {
                $imgArr = explode("/", $task->img);
                $imgName = end($imgArr);
                unlink(public_path().'/img/'.$imgName);
            }
            return json_encode([
                'status' => 'fail',
                'message' => 'No users for mailing'
            ]);
        }

        $usersChank = array_chunk($users, COUNT_MAILING);

        $handle = fopen(public_path()."/txt/log.txt", "a");

        $buttonsViber = new ButtonsViber();
        $buttonsTelegram = new ButtonsTelegram();

        foreach($usersChank as $uc) {
            $data = [];
            foreach($uc as $user) {
                if($task->type == "text") {
                    if($user->messenger == "Telegram") {
                        $mainMenuTelegram = $buttonsTelegram->main_menu($user->id);
                        $mainMenuTelegram = $this->valueSubstitutionArray($mainMenuTelegram);

                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://api.telegram.org/bot".TELEGRAM_TOKEN."/sendMessage",
                            'params' => [
                                'text' => $task->text,
                                'chat_id' => $user->chat,
                                'parse_mode' => 'HTML',
                                'disable_web_page_preview' => true,
                                'reply_markup' => [
                                    'keyboard' => $mainMenuTelegram,
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => false,
                                    'parse_mode' => 'HTML',
                                    'selective' => true
                                ]
                            ]
                        ];
                    }
                    elseif($user->messenger == "Viber") {
                        $mainMenuViber = $buttonsViber->main_menu($user->id);
                        $mainMenuViber = $this->valueSubstitutionArray($mainMenuViber);

                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://chatapi.viber.com/pa/send_message",
                            'params' => [
                                'receiver' => $user->chat,
                                'min_api_version' => 7,
                                'type' => 'text',
                                'text' => $task->text,
                                'keyboard' => [
                                    'Type' => 'keyboard',
                                    'InputFieldState' => 'hidden',
                                    'DefaultHeight' => 'false',
                                    'Buttons' => $mainMenuViber
                                ]
                            ]
                        ];
                    }
                    elseif($user->messenger == "Facebook") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://graph.facebook.com/v3.2/me/messages?access_token=".FACEBOOK_TOKEN,
                            'params' => [
                                'recipient' => [
                                    'id' => $user->chat
                                ],
                                'message' => [
                                    'text' => $task->text
                                ]
                            ]
                        ];
                    }
                }
                elseif($task->type == "img") {
                    $data[] = [
                        'key' => $user->chat,
                        'params' => [
                            'chat_id' => $user->chat,
                            'photo' => $task->img,
                            'caption' => $task->text,
                            'parse_mode' => "Markdown"
                        ]
                    ];
                }
            }

            $res = $this->multiCurl($data);
            print_r($res);

            if(!is_array($res['response'])) {
                json_encode([
                    'status' => 'error',
                    'message' => 'No response',
                    'response' => json_encode($res)
                ]);
            }

            foreach($res['response'] as $key => $response) {
                fwrite($handle, $key."=>".$response."\n");
            }
            unset($data);
            sleep(SLEEP_MAILING);
        }

        fclose($handle);


        $task->performed = "false";
        file_put_contents($this->pathTask, json_encode($task));


        return json_encode([
            'status' => 'success',
            'message' => 'Mailing finished',
            'response' => json_encode($res)
        ]);
    }

    public function chatUsersSend() {
        if(! file_exists($this->chatPathTask)) return json_encode([
            'status' => 'fail',
            'message' => 'No task'
        ]);

        $taskJson = file_get_contents($this->chatPathTask);
        $task = json_decode($taskJson);

        if($task->performed == "true") return json_encode([
            'status' => 'fail',
            'message' => 'Mailing performed'
        ]);

        if($task->chat == "all") {
            $users = UsersChats::distinct('chat')->limit($this->countUsers)->offset($task->start)->get();
        }
        else {
            $chat = Chat::find($task->chat);
            $users = $chat->users->limit($this->countUsers)->offset($task->start)->get();
        }

        $task->performed = "true";
        if($task->count <= $this->countUsers) {
            $task->start = $task->count;
        }
        else {
            $task->start += $this->countUsers;
        }
        file_put_contents($this->chatPathTask, json_encode($task));

        if(empty($users->toArray())) {
            unlink($this->pathTask);
            if(isset($task->img)) {
                $imgArr = explode("/", $task->img);
                $imgName = end($imgArr);
                unlink(public_path().'/img/'.$imgName);
            }
            return json_encode([
                'status' => 'fail',
                'message' => 'No users for mailing'
            ]);
        }

        $usersChank = array_chunk($users, COUNT_MAILING);

        $handle = fopen(public_path()."/txt/log_chat.txt", "a");

        $buttonsViber = new ButtonsViber();
        $buttonsTelegram = new ButtonsTelegram();

        foreach($usersChank as $uc) {
            $data = [];
            foreach($uc as $user) {
                if($task->type == "text") {
                    if($user->messenger == "Telegram") {
                        $mainMenuTelegram = $buttonsTelegram->main_menu($user->id);
                        $mainMenuTelegram = $this->valueSubstitutionArray($mainMenuTelegram);

                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://api.telegram.org/bot".TELEGRAM_CHAT_TOKEN."/sendMessage",
                            'params' => [
                                'text' => $task->text,
                                'chat_id' => $user->chat,
                                'parse_mode' => 'HTML',
                                'disable_web_page_preview' => true,
                                'reply_markup' => [
                                    'keyboard' => $mainMenuTelegram,
                                    'resize_keyboard' => true,
                                    'one_time_keyboard' => false,
                                    'parse_mode' => 'HTML',
                                    'selective' => true
                                ]
                            ]
                        ];
                    }
                    elseif($user->messenger == "Viber") {
                        $mainMenuViber = $buttonsViber->main_menu($user->id);
                        $mainMenuViber = $this->valueSubstitutionArray($mainMenuViber);

                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://chatapi.viber.com/pa/send_message",
                            'params' => [
                                'receiver' => $user->chat,
                                'min_api_version' => 7,
                                'type' => 'text',
                                'text' => $task->text,
                                'keyboard' => [
                                    'Type' => 'keyboard',
                                    'InputFieldState' => 'hidden',
                                    'DefaultHeight' => 'false',
                                    'Buttons' => $mainMenuViber
                                ]
                            ]
                        ];
                    }
                    elseif($user->messenger == "Facebook") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://graph.facebook.com/v3.2/me/messages?access_token=".FACEBOOK_TOKEN,
                            'params' => [
                                'recipient' => [
                                    'id' => $user->chat
                                ],
                                'message' => [
                                    'text' => $task->text
                                ]
                            ]
                        ];
                    }
                }
                elseif($task->type == "img") {
                    $data[] = [
                        'key' => $user->chat,
                        'params' => [
                            'chat_id' => $user->chat,
                            'photo' => $task->img,
                            'caption' => $task->text,
                            'parse_mode' => "Markdown"
                        ]
                    ];
                }
            }

            $res = $this->multiCurl($data, VIBER_CHAT_TOKEN);
            print_r($res);

            if(!is_array($res['response'])) {
                json_encode([
                    'status' => 'error',
                    'message' => 'No response',
                    'response' => json_encode($res)
                ]);
            }

            foreach($res['response'] as $key => $response) {
                fwrite($handle, $key."=>".$response."\n");
            }
            unset($data);
            sleep(SLEEP_MAILING);
        }

        fclose($handle);


        $task->performed = "false";
        file_put_contents($this->pathTask, json_encode($task));


        return json_encode([
            'status' => 'success',
            'message' => 'Mailing finished',
            'response' => json_encode($res)
        ]);
    }

    public function chatSend() {
        $task = MailingChat::where('active', '1')->first();

        if($task == null) return json_encode([
            'status' => 'fail',
            'message' => 'No task'
        ]);

        if($task->performed == 1) return json_encode([
            'status' => 'fail',
            'message' => 'Mailing performed'
        ]);

        $users = DB::table('users_chats')
            ->join(
                'users_chats_has_chats',
                'users_chats.id',
                '=',
                'users_chats_has_chats.users_chats_id'
            )->join(
                'chats',
                'users_chats_has_chats.chats_id',
                '=',
                'chats.id'
            )->where(
                'chats.id',
                $task->chats_id
            )->offset($task->_offset)
            ->limit($this->countUsers)
            ->get([
                'users_chats.id',
                'users_chats.chat',
                'users_chats.messenger'
            ]);

        $task->performed = '1';

        if($task->_offset <= $this->countUsers) {
            $task->_offset = $users->count();
        }
        else {
            $task->_offset += $this->countUsers;
        }

        $task->save();
        if(empty($users->toArray())) {
//            MailingChat::where('id', $task->id)->delete();
            MailingChat::where('id', $task->id)->update([
                'active' => '0'
            ]);
            $chat = Chat::find($task->chats_id);
            $user = BotUsers::find($chat->users_id);
            $mailing = new Message();
            $mailing->send($user->messenger, $user->chat, "{mailing_completed}");
//            if(isset($task->img)) {
//                $imgArr = explode("/", $task->img);
//                $imgName = end($imgArr);
//                unlink(public_path().'/img/'.$imgName);
//            }
            return json_encode([
                'status' => 'fail',
                'message' => 'No users for mailing'
            ]);
        }
        $usersArray = $users->toArray();
        $usersChank = array_chunk($usersArray, COUNT_MAILING);

        $handle = fopen(public_path()."/txt/chat_log.txt", "a");

        $task->type = "text";

        foreach($usersChank as $uc) {
            $data = [];
            foreach($uc as $user) {
                if($task->type == "text") {
                    if($user->messenger == "Telegram") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://api.telegram.org/bot".TELEGRAM_CHAT_TOKEN."/sendMessage",
                            'params' => [
                                'text' => $task->message,
                                'chat_id' => $user->chat,
                                'parse_mode' => 'HTML',
                                'disable_web_page_preview' => true
                            ]
                        ];
                    }
                    elseif($user->messenger == "Viber") {
                        $data[] = [
                            'key' => $user->chat,
                            'messenger' => $user->messenger,
                            'url' => "https://chatapi.viber.com/pa/send_message",
                            'params' => [
                                'receiver' => $user->chat,
                                'min_api_version' => 7,
                                'type' => 'text',
                                'text' => $task->message
                            ]
                        ];
                    }
                }
//                elseif($task->type == "img") {
//                    $data[] = [
//                        'key' => $user->chat,
//                        'params' => [
//                            'chat_id' => $user->chat,
//                            'photo' => $task->img,
//                            'caption' => $task->text,
//                            'parse_mode' => "Markdown"
//                        ]
//                    ];
//                }
            }

            $res = $this->multiCurl($data, $viberToken = VIBER_CHAT_TOKEN);
            print_r($res);

            if(isset($res['response']) && !is_array($res['response'])) {
                json_encode([
                    'status' => 'error',
                    'message' => 'No response',
                    'response' => json_encode($res)
                ]);
            }

            foreach($res['response'] as $key => $response) {
                fwrite($handle, $key."=>".$response."\n");
            }
            unset($data);
            sleep(SLEEP_MAILING);
        }

        fclose($handle);

        unset($task->type);
        $task->performed = "false";
        $task->save();


        return json_encode([
            'status' => 'success',
            'message' => 'Mailing finished',
            'response' => json_encode($res)
        ]);
    }

    public function multiCurl($data, $viberToken = VIBER_TOKEN) {
        $mh = curl_multi_init();
        $connectionArray = [];

        foreach($data as $item) {
            $key = $item['key'];
            $data_string = json_encode($item['params']);

            $ch = curl_init($item['url']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            if($item['messenger'] == "Viber") {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . mb_strlen($data_string),
                    'X-Viber-Auth-Token: ' . $viberToken
                ]);
            }
            else {
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Content-Length: ' . mb_strlen($data_string)
                ]);
            }

            curl_multi_add_handle($mh, $ch);
            $connectionArray[$key] = $ch;
        }
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        }
        while($running > 0);

        $responseEmpty = [];
        $content = [];
        $httpCode = [];

        foreach($connectionArray as $key => $ch) {
            $content[$key] = curl_multi_getcontent($ch);

            if(empty(curl_multi_getcontent($ch))) {
                $responseEmpty[] = $key;
            }

            $getinfo = curl_getinfo($ch);
            $httpCode[$key] = $getinfo['http_code'];
//            $url[$key] = $getinfo['url'];
            curl_multi_remove_handle($mh, $ch);
        }

        curl_multi_close($mh);

        $result = [
            "status" => !empty($content) ? "success" : "error",
            "httpCode" => $httpCode,
//            "url" => $url,
            "response" => $content
        ];

        if(!empty($responseEmpty)) {
            $result['responseEmpty'] = $responseEmpty;
        }

        return $result;
    }

    private function valueSubstitution($str, $type, $n = []) {
        if(preg_match_all('/{([^}]*)}/', $str, $matches)) {
            $textName = file_get_contents(public_path("json/{$type}.json"));
            $textName = json_decode($textName, true);

            foreach($matches[1] as $word) {
                if(!empty($textName[$word])) {
                    $text = $textName[$word];
                    $str = str_replace("{".$word."}", stripcslashes($text), $str);
                }
            }
        }
        if(preg_match_all('/{{([^}]*)}}/', $str, $matches)) {
            foreach($matches[1] as $word) {
                if(isset($n[$word])) {
                    $str = str_replace("{{".$word."}}", $n[$word], $str);
                }
            }
        }
        return $str;
    }

    private function valueSubstitutionArray($array, $n = []) {
        $new_array = [];
        foreach($array as $key => $item) {
            if(is_array($item)) {
                $new_array[$key] = $this->valueSubstitutionArray($item, $n);
            }
            else {
                $new_array[$key] = $this->valueSubstitution($item, "buttons", $n);
            }
        }

        return $new_array;
    }
}
