<?php
    namespace App\Http\Controllers\Bot;

//    use App\Http\Controllers\Bot\Traits\FacebookMessengerMethods;
//    use App\Http\Controllers\Bot\Traits\TelegramMethods;
    use App\Http\Controllers\Bot\Traits\Universal;
//    use App\Http\Controllers\Bot\Traits\ViberMethods;
    use App\models\Answer;
    use App\models\API\Telegram;
    use App\models\API\Viber;
    use App\models\API\FacebookMessenger;
    use App\models\BotUsers;
    use App\models\Interaction;
    use App\models\RefSystem;
    use App\models\Visit;
    use Exception;


    abstract class BaseRequestHandler {
        private $bot;
        private $chat = null;
        private $userId = null;
        private $type;
        private $messenger;
        private $user = null;

        // TODO: [DEV] USE TRAITS BASE REQUEST HANDLER
        use Universal;

        public function getType() {
            return $this->type;
        }

        public function getRequest(): ?string {
            return $this->bot->getRequest();
        }

        public function getBot() {
            return $this->bot;
        }

        public function getChat(): string {
            return $this->chat;
        }

        public function getUserId(): int {
            if($this->userId) {
                return $this->userId;
            }
            else {
                $this->setUserId();
                return $this->userId;
            }
        }

        public function getUser() {
            if($this->user == null) {
                return BotUsers::find($this->getUserId());
            }
            return $this->user;
        }

        public function callMethodIfExists(): void {
            $nameCommand = $this->getMethodName();
            if($nameCommand == null) return;
            if(substr($nameCommand, 0, 4) == "http" || substr($nameCommand, 0, 5) == "viber") return;

            if(method_exists($this, $nameCommand)) {
                try {
                    $this->$nameCommand();
                }
                catch (Exception $e){
                    file_put_contents("error.txt", $e->getFile()."\n ".$e->getLine()."\n ".$e->getMessage()."\n\n", FILE_APPEND);
                }
            }
            else {
                //TODO: START REFERRALS
                if(substr($nameCommand, 0, 5) == "start" && $nameCommand != "start") {
                    $r = explode(" ", $nameCommand);
                    if(!empty($r[1])) {
                        $this->startRef($r[1]);
                        return;
                    }
                }

                if(strpos($nameCommand, "__")) {
                    $arr = explode("__", $nameCommand);
                    $nameCommand = $arr[0];
                    $params = $arr[1];
                    if(strpos($params, "_")) {
                        $params = explode("_", $params);
                    }
                }
                if(method_exists($this, $nameCommand)) {
                    try {
                        $this->$nameCommand($params);
                    }
                    catch (Exception $e){
                        file_put_contents(public_path("error.txt"), $e->getFile()."\n ".$e->getLine()."\n ".$e->getMessage()."\n\n", FILE_APPEND);
                    }
                }
                else {
                    $command = $this->getCommandFromMessage($nameCommand);
                    if($command['command']) {
                        $nameCommand = $command['command'];
                        $params = $command['params'];
                        if(method_exists($this, $nameCommand)) {
                            try {
                                $this->$nameCommand($params);
                            }
                            catch (Exception $e){
                                file_put_contents(public_path("error.txt"), $e->getFile()."\n ".$e->getLine()."\n ".$e->getMessage()."\n\n", FILE_APPEND);
                            }
                        }
                        else {
                            /* UNKNOWN */
                            $this->unknownTeam();
                        }
                    }
                    else {
                        /* INTERACTION */
                        $interaction = $this->getInteraction();
                        if($interaction != null) {
                            if(!empty($interaction['params'])) {
                                $params = json_decode($interaction['params'], true);
                            }

                            if(!empty($interaction['command'])) {
                                $method = $interaction['command'];
                                if(method_exists($this, $method)) {
                                    try {
                                        $this->$method($params);
                                    }
                                    catch (Exception $e){
                                        file_put_contents(public_path("error.txt"), $e->getFile()."\n ".$e->getLine()."\n ".$e->getMessage()."\n\n", FILE_APPEND);
                                    }
                                }
                            }
                            else {
                                /* UNKNOWN */
                                $this->unknownTeam();
                            }
                        }
                        else {
                            /* ANSWERS */
                            $a = Answer::toAnswerIfExistQuestion($nameCommand);
                            if($a !== null) {
                                if(method_exists($this, $a->method)) {
                                    $method = $a->method;
                                    $this->$method();
                                }
                                $this->send($a->answer);
                                return;
                            }

                            /* UNKNOWN */
                            $this->unknownTeam();
                        }
                    }
                }
            }
        }

        private function getCommandFromMessage($message) {
            $user = $this->getUser();
            $pathButtons = public_path()."/json/buttons.json";
            if($user->language != '0') {
                if(file_exists(public_path()."/json/buttons_".$user->language.".json")) {
                    $pathButtons = public_path()."/json/buttons_".$user->language.".json";
                }
            }
            $textsButtons = json_decode(file_get_contents($pathButtons), true);
            $command = array_search($message, $textsButtons);
            $params = [];
            if(strpos($command, "__")) {
                $arr = explode("__", $command);
                $command = $arr[0];
                $params = $arr[1];
                if(strpos($params, "_")) {
                    $params = explode("_", $params);
                }
            }
            return [
                'command' => $command,
                'params' => $params
            ];
        }

        public function send(string $message, array $params = [], array $n = []): string {
            $message = $this->valueSubstitution($message, "pages", $n);
            $params = $this->valueSubstitutionArray($params, $n);
            return $this->bot->sendMessage($this->chat, $message, $params);
        }

        public function sendTo(string $chat, string $message, array $params = [], array $n = []): string {
            $message = $this->valueSubstitution($message, "pages", $n);
            $params = $this->valueSubstitutionArray($params, $n);
            return $this->bot->sendMessage($chat, $message, $params);
        }

        public function sendCarusel($params, array $n = []): string {
            $params = $this->valueSubstitutionArray($params, $n);
            if(!isset($params['columns'])) {
                $params['columns'] = 6;
            }
            if(!isset($params['rows'])) {
                $params['rows'] = 7;
            }
            $richMedia = [
                'Type' => 'rich_media',
                'ButtonsGroupColumns' => $params['columns'],
                'ButtonsGroupRows' => $params['rows'],
                'BgColor' => '#FFFFFF',
                'Buttons' => $params['richMedia']
            ];
            return $this->bot->sendCarusel($this->chat, $richMedia, $params['buttons']);
        }

        private function valueSubstitution($str, $type, $n = []) {
            $user = BotUsers::find($this->getUserId());
            if($user->language != '0') {
                if(file_exists(public_path()."/json/".$type."_".$user->language.".json")) {
                    $type = $type."_".$user->language;
                }
            }
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

        public function sendPhoto($img, $message = null, $params = [], $n = []) {
            if($message != null) {
                $message = $this->valueSubstitution($message, "pages", $n);
            }
            $params = $this->valueSubstitutionArray($params, $n);
            return $this->bot->sendPhoto($this->chat, $img, $message, $params);
        }

        public function sendImage($img, $message = null, $params = [], $n = []) {
            $message = $this->valueSubstitution($message, "pages", $n);
            $params = $this->valueSubstitutionArray($params, $n);
            return $this->bot->sendImage($this->chat, $img, $message, $params);
        }

        public function deleteMessage($messageId, $chat = null) {
            $chat = empty($chat) ? $this->chat : $chat;
            return $this->bot->deleteMessage($chat, $messageId);
        }

        public function getIdSendMessage($res) {
            $res = json_decode($res);
            return $res->result->message_id;
        }

        public function delInteraction(): void {
            $interaction = new Interaction();
            $interaction->where('users_id', $this->getUserId())->delete();
        }

        public function setInteraction(string $command, array $params = []): void {
            $this->delInteraction();
            $interaction = new Interaction();
            $interaction->users_id = $this->getUserId();
            $interaction->command = $command;
            $interaction->params = json_encode($params);
            $interaction->save();
        }

        public function getInteraction(): ? array {
            $interaction = new Interaction();
            $res = $interaction->where('users_id', $this->getUserId())->get()->toArray();
            if(empty($res[0])) return null;
            return $res[0];
        }

        public function getCallbackQueryId() {
            $request = json_decode($this->getRequest());
            return $request->callback_query->id;

        }

        public function answerCallbackQuery($text) {
            $callbackQueryId = $this->getCallbackQueryId();
            return $this->bot->answerCallbackQuery($callbackQueryId, $text);
        }

        public function editMessage($messageId, $message, $inlineKeyboard = null, $n = []) {
            $message = $this->valueSubstitution($message, "pages", $n);
            $inlineKeyboard = $this->valueSubstitutionArray($inlineKeyboard, $n);
            return $this->getBot()->editMessageText($this->getChat(), $messageId, $message, $inlineKeyboard);
        }

        public function editMessageMedia($messageId, $media, $caption = '', $inlineKeyboard = null, $n = []) {
            $inlineKeyboard = $this->valueSubstitutionArray($inlineKeyboard, $n);
            return $this->getBot()->editMessageMedia($this->getChat(), $messageId, $media, $caption, $inlineKeyboard);
        }

        public function startRef($chat) {
            try {
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
            catch (Exception $e) {
                file_put_contents(public_path()."/refError.txt", "referral ".$referral."\nreferrer ".$referrer->id."\n".$e->getMessage()."\n\n", FILE_APPEND);
                echo $e->getMessage();
            }

            $this->performAnActionRef($referrer->id);

            if(MESSENGER == "Telegram") {
                $this->start();
            }
        }

        public function setUserStart() {
            $botUsers = BotUsers::find($this->getUserId());
            $botUsers->update(['start' => "1"]);
        }

        public function delMessage() {
            $params = json_decode($this->getInteraction()['params']);
            if(isset($params->messageId)) {
                $this->deleteMessage($params->messageId, $this->getChat());
            }
            $this->delInteraction();
        }

        public function typing_on() {
            return $this->bot->senderAction($this->chat, 'typing_on');
        }

        public function typing_off() {
            return $this->bot->senderAction($this->chat, 'typing_off');
        }

        public function mark_seen() {
            return $this->bot->senderAction($this->chat, 'mark_seen');
        }

        public function sendButton($message, $buttons, $n = []) {
            $message = $this->valueSubstitution($message, "pages", $n);
            $buttons = $this->valueSubstitutionArray($buttons, $n);
            return $this->bot->sendButton($this->chat, $message, $buttons);
        }

        public function getMessage() {
            return $this->getBot()->getMessage();
        }
    }
