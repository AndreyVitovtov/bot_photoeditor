<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Admin\Contacts;
use App\models\BotUsers;
use App\models\buttons\ButtonsFacebook;
use App\models\buttons\ButtonsViber;
use App\models\buttons\ButtonsTelegram;
use App\models\buttons\InlineButtons;
use App\models\ContactsModel;
use App\models\ContactsType;
use App\models\Language;
use App\models\PhotoEditor;
use App\models\ProcessPhoto;
use App\models\RefSystem;
use App\models\Statistics;
use App\models\Transliterate;
use App\models\AdminChat;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RequestHandler extends BaseRequestHandler {

    private $messenger;

    public function __construct() {
        $headers = getallheaders();
        if(isset($_SERVER['HTTP_X_VIBER_CONTENT_SIGNATURE'])) {
            $this->messenger = "Viber";
        }
        elseif(isset($headers['Facebook-Api-Version'])) {
            $this->messenger = "Facebook";
        }
        else {
            $this->messenger = "Telegram";
        }

        define("MESSENGER", $this->messenger);
        parent::__construct();

        if($this->messenger == "Facebook") {
            $this->mark_seen();
            $this->typing_on();
            sleep(rand(1, 2));
        }
    }

    public function getMessenger() {
        return $this->messenger;
    }

    public function buttons() {
        if($this->messenger == "Viber") {
            return new ButtonsViber();
        }
        elseif($this->messenger == "Telegram") {
            return new ButtonsTelegram();
        }
        elseif($this->messenger == "Facebook") {
            return new ButtonsFacebook();
        }
    }

    public function index() {
        file_put_contents(public_path("json/request.json"), $this->getRequest());

        if(MESSENGER == "Telegram") {
            if(!$this->isUserSubscribedToChannel()) {
                return $this->send("{subscribed_to_channel}", [
                    'inlineButtons' => InlineButtons::SubscribedToChannel(),
                    'hideKeyboard' => true
                ]);
            }
        }

        if($this->getType() == "started") {
            $this->setUserId();

            $context = $this->getBot()->getContext();
            if($context) {
                $context = str_replace(" ", "+", $context);
                if($this->messenger == "Viber" && substr($context, -2) != "==") {
                    $context .= "==";
                }

                $this->startRef($context);
            }

            $this->send("{greeting}", [
                'buttons' => $this->buttons()->start()
            ]);
        }
        else {
            $this->callMethodIfExists();
        }

        return response ('OK', 200)->header('Content-Type', 'text/plain');


//TODO: ДОБАВИТЬ WEBHOOK FACEBOOK MESSENGER
//        $verify_token = "31ad48b8b8b266e8f653de34252e44a0"; //Маркер подтверждения
//        if (!empty($_REQUEST['hub_mode']) && $_REQUEST['hub_mode'] == 'subscribe' && $_REQUEST['hub_verify_token'] == $verify_token) {
//            echo $_REQUEST['hub_challenge'];
//        }
    }

    public function start($params = null) {

        $this->delInteraction();

        $this->setUserStart();

        //FACEBOOK REFERRALS
        if(MESSENGER == "Facebook") {
            $chat = $this->getBot()->getRef();
            if($chat != null) {
                $this->startRef($chat);
            }
        }

        $this->send("{welcome}", [
            'buttons' => $this->buttons()->main_menu($this->getUserId())
        ]);
    }

    private function isUserSubscribedToChannel() {
        $res = $this->getBot()->getChatMember($this->getChat(), CHANNEL_SUBSCRIPTION_ID);
        $res = json_decode($res);
        if(isset($res->result->status)) {
            if($res->result->status == "member" || $res->result->status == "creator") return true;
            return false;
        }
        return false;
    }

    public function i_subscribed() {
        $this->send('{main_menu}', [
            'buttons' => $this->buttons()->main_menu($this->getUserId())
        ]);
    }

    public function process_photo() {
        if($this->ability_process_photos()) {
            $this->send('{send_photo}', [
                'buttons' => $this->buttons()->back(),
                'input' => 'regular'
            ]);

            $this->setInteraction('process_photo_send_photo');
        }
    }

    private function deletePhoto() {
        if(MESSENGER == "Viber") {
            $params = json_decode($this->getInteraction()['params']);
            if(isset($params->photo)) {
                if(file_exists(public_path().'/photo/'.$params->photo)) {
                    unlink(public_path().'/photo/'.$params->photo);
                }
            }
        }
    }

    private function ability_process_photos() {
        $countProcessPhoto = ProcessPhoto::where('users_id', $this->getUserId())
            ->where('date', date('Y-m-d'))
            ->count();
        $user = BotUsers::find($this->getUserId());
        if($user->access == '0') {
            if($countProcessPhoto >= COUNT_PHOTO_EDIT) {
                $this->send('{have_exhausted_the_opportunity_to_get_access}', [
                    'buttons' => $this->buttons()->main_menu($this->getUserId())
                ]);

                $this->deletePhoto();

                return false;
            }
        }
        elseif($user->access == '1' && $user->access_free == '0') {
            if($countProcessPhoto >= COUNT_PHOTO_EDIT_PAID_ACCESS) {
                $this->send('{have_exhausted_the_ability_to_process_photos}', [
                    'buttons' => $this->buttons()->main_menu($this->getUserId())
                ]);

                $this->deletePhoto();

                return false;
            }
        }
        elseif($user->access == '1' && $user->access_free == '1') {
            if($countProcessPhoto >= COUNT_PHOTO_EDIT_FREE_ACCESS) {
                $this->send('{have_exhausted_the_ability_to_process_photos_get_paid_access}', [
                    'buttons' => $this->buttons()->main_menu($this->getUserId())
                ]);

                $this->deletePhoto();

                return false;
            }
        }

        return true;
    }

    public function process_photo_send_photo() {
        if(MESSENGER == "Telegram") {
            if($this->getType() == 'photo') {
                $photo = $this->getFilePath();

                $res = $this->send('{select_filter}', [
                    'buttons' => $this->buttons()->moreBack()
                ]);

                $this->sendFiltersTelegram();

                $this->setInteraction('', [
                    'photo' => $photo,
                    'messageId' => $this->getIdSendMessage($res)
                ]);
            }
            else {
                $this->process_photo();
            }
        }
        elseif(MESSENGER == 'Viber') {
            if($this->getType() == "picture") {
                $photo = $this->getFilePath();

                $ext = explode('.', explode('?', $photo)[0]);
                $ext = end($ext);
                $fileName =  md5(time().$this->getChat()).".".$ext;

                if(!copy($photo, public_path().'/photo/'.$fileName)) {
                    $this->send('{error}', [
                        'buttons' => $this->buttons()->main_menu($this->getUserId())
                    ]);
                    return;
                }

                $photo = url('/photo/'.$fileName);

                $this->send('{select_filter}', [
                    'buttons' => $this->buttons()->back()
                ]);

                $this->sendCarusel([
                    'richMedia' => $this->buttons()->filters(),
                   'buttons' => $this->buttons()->moreBack()
                ]);

                $this->setInteraction('', [
                    'photo' => $photo
                ]);
            }
        }
    }

    private function sendFiltersTelegram($page = 0) {
        $filtersAll = json_decode(file_get_contents(public_path().'/json/_dict.json'), true);
        $filters = array_chunk($filtersAll, 5);
        foreach ($filters[$page] as $filter) {
            $button = [[
                "text" => '{apply}',
                "callback_data" => 'apply_filter__'.$filter['id']
            ]];

            $this->sendPhoto($filter['image_link'], '', [
                'inlineButtons' => [$button]
            ]);
        }
    }

    public function apply_filter($id) {
        if(! $this->ability_process_photos()) return;

        $params = json_decode($this->getInteraction()['params']);
        if(!isset($params->photo)) {
            $this->unknownTeam();
        }

        if(MESSENGER == "Telegram") {
            $this->getBot()->sendChatAction($this->getChat(), 'upload_photo');
        }

        $photoEditor = new PhotoEditor($params->photo);

        $res = $photoEditor->ApplyFilter($id);

        $pp = new ProcessPhoto();
        $pp->users_id = $this->getUserId();
        $pp->date = date('Y-m-d');
        $pp->time = date('H:i:s');
        $pp->save();

        if(substr($res, 0, 4) == "http") {
            if(MESSENGER == "Telegram") {
                $this->sendPhoto($res, '', [
                    'buttons' => $this->buttons()->processAnotherPhoto()
                ]);

//                $res = $this->send('{select_filter}', [
//                    'buttons' => $this->buttons()->moreBack()
//                ]);
//
//                $this->sendFiltersTelegram();
//
//                $this->setInteraction('', [
//                    'photo' => $params->photo
//                ]);
            }
            elseif(MESSENGER == "Viber") {
                $this->sendImage($res, null, [
                    'buttons' => $this->buttons()->processAnotherPhoto()
                ]);

//                $this->sendCarusel([
//                    'richMedia' => $this->buttons()->filters(),
//                    'buttons' => $this->buttons()->moreBack()
//                ]);
            }
        }
        else {
            $this->send($res, [
                'buttons' => $this->buttons()->back()
            ]);
        }
    }

    public function process_another_photo() {
        $this->process_photo();
    }

    public function more() {
        $params = json_decode($this->getInteraction()['params'], true);

//        $params = json_decode($this->getInteraction()['params']);
        if(!isset($params['page'])) {
            $page = 1;
        }
        else {
            $page = $params['page'] + 1;
        }
        $this->filters($page);

        $params['page'] = $page;
        $this->setInteraction('', $params);
    }

    public function filters($page) {
        if(MESSENGER == "Telegram") {
            $this->send('{select_filter}', [
                'buttons' => $this->buttons()->moreBack($page)
            ]);
            $this->sendFiltersTelegram($page);
        }
        elseif(MESSENGER == "Viber") {
            $this->sendCarusel([
                'richMedia' => $this->buttons()->filters($page),
                'buttons' => $this->buttons()->moreBack($page)
            ]);
        }
    }

    public function free_access() {
        $this->send("{free_access}", [
            'buttons' => $this->buttons()->main_menu($this->getUserId())
        ]);

        if(MESSENGER == "Telegram") {
            $this->send('https://t.me/'.NAME_TELEGRAM_BOT."?start=".$this->getChat(), [
                'buttons' => $this->buttons()->main_menu($this->getUserId())
            ]);
        }
        elseif(MESSENGER == "Viber") {
            $this->send('viber://pa?chatURI='.NAME_VIBER_BOT.'&context='.$this->getChat(), [
                'buttons' => $this->buttons()->main_menu($this->getUserId())
            ]);
        }
    }

    public function languages() {
        if(MESSENGER == "Viber") {
            $languages = Language::all();
            $count = $languages->count()+1;
            $this->send("{choose_language}", [
                'buttons' => $this->buttons()->main_menu($this->getUserId())
            ]);
            if(empty($languages->toArray())) {
                return;
            }
            $this->sendCarusel([
                'rows' => $count < 7 ? $count : 7,
                'richMedia' => $this->buttons()->languages($languages),
                'buttons' => $this->buttons()->back()
            ]);
        }
        elseif(MESSENGER == "Telegram") {
            $this->send("{choose_language}", [
                'inlineButtons' => InlineButtons::languages()
            ]);
        }
    }

    public function lang($code) {
        $user = BotUsers::find($this->getUserId());
        $user->language = $code;
        $user->save();
        $this->send('{language_saved}', [
            'buttons' => $this->buttons()->main_menu($this->getUserId())
        ]);
    }

    public function contacts() {
        $this->setInteraction('contacts_select_topic');

        $this->send("{send_support_message}", [
            'buttons' => $this->buttons()->back()
        ]);

        if(MESSENGER == "Facebook") {
            $this->send("{select_topic}", [
                'keyboard' => ButtonsFacebook::contacts()
            ]);
        }
        elseif(MESSENGER == "Telegram") {
            $this->send("{select_topic}", [
                'inlineButtons' => InlineButtons::contacts()
            ]);
        }
        else {
            $this->send("{select_topic}", [
                'buttons' => $this->buttons()->back()
            ]);
            $this->sendCarusel([
                'rows' => 4,
                'richMedia' => $this->buttons()->contacts(),
                'buttons' => $this->buttons()->back()
            ]);
        }
    }

    public function contacts_select_topic() {
        $topic = $this->getBot()->getMessage();
        if($topic == "general" ||
            $topic == "access" ||
            $topic == "advertising" ||
            $topic == "offers") {
            $this->send("{send_message}", [
                'buttons' => $this->buttons()->back(),
                'input' => 'regular'
            ]);
            $this->delInteraction();
            $this->setInteraction('contacts_send_message', [
                'topic' => $topic
            ]);
        }
        else {
            $this->contacts();
        }
    }

    public function contacts_send_message($params) {
        $contactsType = ContactsType::where('type', $params['topic'])->first();
        $contacts = new ContactsModel();
        $contacts->contacts_type_id = $contactsType->id;
        $contacts->users_id = $this->getUserId();
        $contacts->text = $this->getBot()->getMessage();
        $contacts->date = date("Y-m-d");
        $contacts->time = date("H:i:s");
        $contacts->save();

        $this->send("{message_sending}", [
            'buttons' => $this->buttons()->main_menu($this->getUserId())
        ]);
        $this->delInteraction();
    }

    public function main_menu() {
        $this->delInteraction();
        $this->send("{main_menu}", [
            'buttons' => $this->buttons()->main_menu($this->getUserId())
        ]);
    }

    public function back() {
        if(MESSENGER == "Viber") {
            $params = json_decode($this->getInteraction()['params']);
            if(isset($params->photo)) {
                if(file_exists(public_path().'/photo/'.$params->photo)) {
                    unlink(public_path().'/photo/'.$params->photo);
                }
            }
        }
//        $this->delMessage();
        $this->delInteraction();

        $this->send("{main_menu}", [
            'buttons' => $this->buttons()->main_menu($this->getUserId())
        ]);
        exit;
    }

    public function group() {
        if(MESSENGER == "Telegram") {
            $this->send("{group}", [
                'inlineButtons' => InlineButtons::group()
            ]);
        }
    }





    public function performAnActionRef($referrerId) {
        $this->userAccess($referrerId);
//      $this->send("REF SYSTEM ".$chat);
    }

    public function userAccess($id) {
        $count = RefSystem::where('referrer', $id)->count();

        if($count == COUNT_INVITES_ACCESS) {
            $user = BotUsers::find($id);
            $user->access = '1';
            $user->access_free = '1';
            $user->save();

            $this->sendTo($user->chat, "{got_free_access}", [
                'buttons' => $this->buttons()->main_menu($id)
            ]);
        }
    }


}
