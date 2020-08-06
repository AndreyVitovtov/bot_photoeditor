<?php

namespace app\models\API;

	class Viber {
        private $token;
        private $request = null;

        public function __construct($token)
        {
            $this->request = json_decode(file_get_contents('php://input'));
            $this->token = $token;
        }

        public function getContext() {
            if(isset($this->request->context)) {
                return $this->request->context;
            }
            return null;
        }

        public function getId(): ?string
        {
            if (isset($this->request->sender->id)) {
                return $this->request->sender->id;
            } elseif (isset($this->request->user->id)) {
                return $this->request->user->id;
            } else {
                return null;
            }
        }

        public function getName(): ?array
        {
            if (isset($this->request->sender->name)) {
                return [
                    'first_name' => $this->request->sender->name,
                    'last_name' => $this->request->sender->name,
                    'username' => $this->request->sender->name
                ];
            } elseif (isset($this->request->user->name)) {
                return [
                    'first_name' => $this->request->user->name,
                    'last_name' => $this->request->user->name,
                    'username' => $this->request->user->name
                ];
            } else {
                return null;
            }
        }

        public function getCountry():? string {
            if(isset($this->request->user->country)) {
                return $this->request->user->country;
            }
            elseif(isset($this->request->sender->country)) {
                return $this->request->sender->country;
            }
            else {
                return "";
            }
        }

        public function getRequest(): ?string {
            return json_encode($this->request);
        }

        public function getMessage(): ?string {
            if(isset($this->request->message->text)) {
                return $this->request->message->text;
            }
            return null;
        }

        public function getTypeMessage() {
            if(isset($this->request->message->type)) {
                $type = $this->request->message->type;
            }
            else {
                $type = null;
            }

            return $type;
        }

        public function getAvatar(): ?string {
            if(isset($this->request->sender->avatar)) {
                return $this->request->sender->avatar;
            }
            elseif(isset($this->request->user->avatar)) {
                return $this->request->user->avatar;
            }
            return null;
        }

        public function sendMessage(?string $chat, string $message, array $params = []): string {
            if (!empty($params['buttons'])) {
                $buttons = $params['buttons'];

                if (empty($params['input'])) {
                    $InputFieldState = "hidden";
                } else {
                    $InputFieldState = $params['input'];
                }

                $data = [
                    'receiver' => $chat,
                    'min_api_version' => 7,
                    'type' => 'text',
                    'text' => $message,
                    'keyboard' => [
                        'Type' => 'keyboard',
                        'InputFieldState' => $InputFieldState,
                        'DefaultHeight' => 'false',
                        'Buttons' => $buttons
                    ]
                ];
            } else {
                $data = [
                    'receiver' => $chat,
                    'min_api_version' => 7,
                    'type' => 'text',
                    'text' => $message
                ];
            }

            return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
        }

        public function sendContact(?string $chat, string $name, string $phone, array $params = [])
        {
            if (!empty($params['buttons'])) {
                $buttons = $params['buttons'];

                if (empty($params['input'])) {
                    $InputFieldState = "hidden";
                } else {
                    $InputFieldState = $params['input'];
                }

                $data = [
                    'receiver' => $chat,
                    'min_api_version' => 1,
                    'type' => 'contact',
                    'contact' => [
                        'name' => $name,
                        'phone_number' => $phone
                    ],
                    'keyboard' => [
                        'Type' => 'keyboard',
                        'InputFieldState' => $InputFieldState,
                        'DefaultHeight' => 'false',
                        'Buttons' => $buttons
                    ]
                ];
            } else {
                $data = [
                    'receiver' => $chat,
                    'min_api_version' => 1,
                    'type' => 'contact',
                    'contact' => [
                        'name' => $name,
                        'phone_number' => $phone
                    ]
                ];
            }

            return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
        }

        public function sendImage(?string $chat, string $image, ?string $text = null, array $params = [])
        {
            if (empty($params['input'])) {
                $InputFieldState = "hidden";
            } else {
                $InputFieldState = $params['input'];
            }

            if (!empty($params['buttons'])) {
                $buttons = $params['buttons'];

                $data = [
                    'receiver' => $chat,
                    'min_api_version' => 7,
                    'type' => 'picture',
                    'text' => $text,
                    'media' => $image,
                    'keyboard' => [
                        'Type' => 'keyboard',
                        'InputFieldState' => $InputFieldState,
                        'DefaultHeight' => 'false',
                        'Buttons' => $buttons
                    ]
                ];
            } else {
                $data = [
                    'receiver' => $chat,
                    'min_api_version' => 7,
                    'type' => 'picture',
                    'text' => $text,
                    'media' => $image
                ];
            }
            return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
        }

        public function sendFile(?string $chat, string $media, $file_name, $size, array $params = []) {
            if (empty($params['input'])) {
                $InputFieldState = "hidden";
            } else {
                $InputFieldState = $params['input'];
            }

            if (!empty($params['buttons'])) {
                $buttons = $params['buttons'];

                $data = [
                    'receiver' => $chat,
                    'min_api_version' => 1,
                    'tracking_data' => 'tracking data',
                    'type' => 'file',
                    'media' => $media,
                    'file_name' => $file_name,
                    'size' => $size,
                    'keyboard' => [
                        'Type' => 'keyboard',
                        'InputFieldState' => $InputFieldState,
                        'DefaultHeight' => 'false',
                        'Buttons' => $buttons
                    ]
                ];
            } else {
                $data = [
                    'receiver' => $chat,
                    'min_api_version' => 1,
                    'tracking_data' => 'tracking data',
                    'type' => 'file',
                    'media' => $media,
                    'file_name' => $file_name,
                    'size' => $size
                ];
            }
            return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
        }

        public function sendCarusel($chat, $rich_media, $buttons)
        {
            $data = [
                'receiver' => $chat,
                'type' => 'rich_media',
                'min_api_version' => 7,
                'keyboard' => [
                    "Type" => "keyboard",
                    'InputFieldState' => 'hidden',
                    "DefaultHeight" => 'false',
                    "Buttons" => $buttons
                ],
                'rich_media' => $rich_media
            ];
            return $this->makeRequest("https://chatapi.viber.com/pa/send_message", $data);
        }

        public function sendMessageBroadcast(array $arrId, string $message, array $params = []) {
            if (!empty($params['buttons'])) {
                $buttons = $params['buttons'];

                if (empty($params['input'])) {
                    $InputFieldState = "hidden";
                } else {
                    $InputFieldState = $params['input'];
                }

                $data = [
                    'min_api_version' => 7,
                    'type' => 'text',
                    'text' => $message,
                    'broadcast_list' => [
                        implode('","', $arrId)
                    ],
                    'keyboard' => [
                        'Type' => 'keyboard',
                        'InputFieldState' => $InputFieldState,
                        'DefaultHeight' => 'false',
                        'Buttons' => $buttons
                    ]
                ];
            } else {
                $data = [
                    'min_api_version' => 7,
                    'type' => 'text',
                    'text' => $message,
                    'broadcast_list' => [
                        implode('","', $arrId)
                    ]
                ];
            }
            return $this->makeRequest("https://chatapi.viber.com/pa/broadcast_message", $data);
        }

        public function getUserInfo($chat) {
            $data = array(
                'id' => $chat
            );

            return $this->makeRequest("https://chatapi.viber.com/pa/get_user_details", $data);
        }

        public function setWebhook($url) {
            $data = [
                "url" => $url,
                "event_types" => [
                    //"delivered",
                    //"seen",
                    "failed",
                    "subscribed",
                    "unsubscribed",
                    "conversation_started"
                ],
                "send_name" => 'true',
                "send_photo" => 'true'
            ];

            return $this->makeRequest("https://chatapi.viber.com/pa/set_webhook", $data);
        }

        public function getWebhook(): string {
            return "";
        }

        private function makeRequest($url, $data) {
            $data_string = json_encode($data);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Cache-Control: no-cache',
                    'Content-Type: application/JSON',
                    'X-Viber-Auth-Token: ' . $this->token
                ]
            );
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

    }

