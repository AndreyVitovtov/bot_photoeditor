<?php

namespace app\models\API;

class FacebookMessenger {
		private $token;
		private $request;

		public function __construct($token) {
			$this->token = $token;
            $this->request = json_decode(file_get_contents('php://input'));
		}

        function getId(): ?string {
            return $this->request->entry[0]->messaging[0]->sender->id;
        }

//getWebhookUpdates
		public function getWebhookUpdates() {
			$response = file_get_contents('php://input');

			$res = json_decode($response, true);

			return [
				'id' => $res['entry'][0]['id'],
				'time' => $res['entry'][0]['time'],
				'senderId' => $res['entry'][0]['messaging'][0]['sender']['id'],
				'recipientId' => $res['entry'][0]['messaging'][0]['recipient']['id'],
				'timestamp' => $res['entry'][0]['messaging'][0]['timestamp'],
				'payload' => $res['entry'][0]['messaging'][0]['message']['quick_reply']['payload'],
				'mid' => $res['entry'][0]['messaging'][0]['message']['mid'],
				'seq' => $res['entry'][0]['messaging'][0]['message']['seq'],
				'text' => $res['entry'][0]['messaging'][0]['message']['text'],
				'payloadStart' => $res['entry'][0]['messaging'][0]['postback']['payload'],
				'titleStart' => $res['entry'][0]['messaging'][0]['postback']['title']
			];
		}

        public function getRef() {
		    if(isset($this->request->entry[0]->messaging[0]->postback->referral->ref)) {
		        return $this->request->entry[0]->messaging[0]->postback->referral->ref;
            }
            return null;
		}

//userInfo
		public function userInfo($chat) {
			$url = "https://graph.facebook.com/".$chat."?fields=first_name,last_name,profile_pic,locale&access_token=".$this->token;

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$json = curl_exec($ch);
			curl_close($ch);

			return $json;
		}

//getName
    public function getName($chat) {
        $json = $this->userInfo($chat);
        $obj = json_decode($json);
        return [
            'chat' => $obj->id,
            'first_name' => $obj->first_name,
            'last_name' => $obj->last_name,
            'username' => $obj->first_name." ".$obj->last_name
        ];
    }

//sendMessage
		public function sendMessage(?string $chat, string $message, ?array $params = []): string {
			/*//Быстрые ответы
			$keyboard = [
				[
					"content_type" => "text",
					"title" => "Да",
					"payload" => "yes"
				],
				[
					"content_type" => "text",
					"title" => "Нет",
					"payload" => "no"
				]
			];
			*/

			if(!empty($params['keyboard'])) {
                $keyboard = $params['keyboard'];
            }
			else {
                $keyboard = null;
            }

			$data = [
				'recipient' => [
					'id' => $chat
				],
				'message' => [
					'text' => $message,
					'quick_replies' => $keyboard
				]
			];

			return $this->makeRequest($data);
		}

//sendImage
		public function sendImage($id, $img_url) {
			$data = [
				'recipient' => [
					'id' => $id
				],
				'message' => [
					'attachment' => [
						'type' => 'image',
						'payload' => [
							'url' => $img_url
						]
					]
				]
			];

			return $this->makeRequest($data);
		}

//sendButton
		public function sendButton($id, $text, $buttons) {
			/*
			//URL кнопки
			$buttons = [
				array(
				  'type' => 'web_url',
				  'url' => $url,
				  'title' => 'Текст кнопки',
				)
			];

			//Кнопки
			$buttons = [
				array(
					'type' => 'postback',
					'title' => 'Текст кнопки',
					'payload' => 'Это придет на Webhook'
				),
				array(
					'type' => 'postback',
					'title' => 'Текст кнопки',
					'payload' => 'Это придет на Webhook'
				)
			];
			*/

			$data = [
				'recipient' => [
					'id' => $id
				],
				'message' => [
					'attachment' => [
						'type' => 'template',
						'payload' => [
							'template_type' => 'button',
							'text' => $text,
							'buttons' => $buttons
						]
					]
				]
			];

			return $this->makeRequest($data);
		}

//sendTemplate
		public function sendTemplateGeneric($id, $title, $img_url, $subtitle, $buttons) {

			$elements = [
				[
					'title' => $title,
                    'image_url' => $img_url,
					'subtitle' => $subtitle,
					'buttons' => $buttons
				]
			];

			$data = [
				'recipient' => [
					'id' => $id
				],
				'message' => [
					'attachment' => [
						'type' => 'template',
						'payload' => [
							'template_type' => 'generic',
							'image_aspect_ratio' => 'square',
							'elements' => $elements
						]
					]
				]
			];

			return $this->makeRequest($data);
		}

    public function sendFile($chat, $url) {
        $data = [
            'recipient' => [
                'id' => $chat
            ],
            'message' => [
                'attachment' => [
                    'type' => 'file',
                    'payload' => [
                        'url' => $url,
                        'is_reusable' => 'true'
                    ]
                ]
            ]
        ];

        return $this->makeRequest($data);
    }

//senderAction
		public function senderAction($id, $action) {
			//$action = 'typing_off';

			$data = [
				'recipient' => [
					'id' => $id
				],
				'sender_action' => $action
			];

			return $this->makeRequest($data);
		}

//makeRequest
		private function makeRequest($data) {
			$url = "https://graph.facebook.com/v3.2/me/messages?access_token=".$this->token;

			$data_string = json_encode($data);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
				'Content-Length: ' . strlen($data_string)
                ]
			);
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}

//setWebhook
		public function setWebhook(): string {
			$verify_token = "EAAGLEx4zcr4BABGQRuizFmSmxdEZCKcLZBcuuKE";
			if(!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token)
			{
				return $_REQUEST['hub_challenge'];
			}
		}

//getStarted
		public function getStarted() {
			$data = [
				'get_started' => [
					'payload' => 'start'
				]
			];

			$data_string = json_encode($data);

			$url = "https://graph.facebook.com/v3.2/me/messenger_profile?access_token=".$this->token;

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}

//addMenu
		public function addMenu() {
				$data = [
				'persistent_menu' => [
					[
						'locale' => 'default',
						'composer_input_disabled' => false,
						'call_to_actions' =>
						[
							[
								'title' => 'Книги 📖',
								'type' => 'nested',
								'call_to_actions' => [
									[
										'title' => '🔍 Поиск книги 📖',
										'type' => 'postback',
										'payload' => 'search_book'
									],
									[
										'title' => '📚 Добавить книгу',
										'type' => 'postback',
										'payload' => 'add_book'
									]
								]
							],
							[
								'title' => '🔐 Доступ',
								'type' => 'nested',
								'call_to_actions' => [
									[
										'title' => '💰 Платный доступ',
										'type' => 'postback',
										'payload' => 'paid_access'
									],
									[
										'title' => '🆓 Бесплатный доступ',
										'type' => 'postback',
										'payload' => 'free_access'
									]
								]
							],
                            [
                                'title' => '💬 Поддержка',
                                'type' => 'nested',
                                'call_to_actions' => [
                                    [
                                        'title' => '📈 Статистика',
                                        'type' => 'postback',
                                        'payload' => 'statistics'
                                    ],
                                    [
                                        'title' => '✉ Контакты',
                                        'type' => 'postback',
                                        'payload' => 'contacts'
                                    ],
                                    [
                                        'title' => '💬 Группа',
                                        'type' => 'web_url',
                                        'url' => 'https://all.vitovtov.info/admin'
                                    ]
                                ]
                            ]
						]
					]
				]
			];

			$url = "https://graph.facebook.com/v3.2/me/messenger_profile?access_token=".$this->token;

			$data_string = json_encode($data);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}

//greeting
		public function greeting($text) {
			$data = [
				'greeting' => [
					[
						'locale' => 'default',
						'text' => $text
					]
				]
			];

			$url = "https://graph.facebook.com/v3.2/me/messenger_profile?access_token=".$this->token;

			$data_string = json_encode($data);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
			$response = curl_exec($ch);
			curl_close($ch);
			return $response;
		}

    public function getRequest(): ?string {
        return json_encode($this->request);
    }

    public function getMessage(): ?string {
        $request = $this->request;
        if(isset($request->entry[0]->messaging[0]->message->quick_reply->payload)) {
            return $request->entry[0]->messaging[0]->message->quick_reply->payload;
        }
        else if(isset($request->entry[0]->messaging[0]->message->text)) {
            return $request->entry[0]->messaging[0]->message->text;
        }
        else {
            return null;
        }
    }
}
?>
