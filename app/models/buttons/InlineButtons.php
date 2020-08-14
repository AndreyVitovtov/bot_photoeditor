<?php

namespace App\models\buttons;

use App\models\BotUsers;
use App\models\Heading;
use App\models\Khatma;
use App\models\Language;
use App\models\Page;
use App\models\Quran;
use App\models\Recipe;
use Illuminate\Database\Eloquent\Collection;

class InlineButtons {
    public static function termsOfUse() {
        return [
            [
                [
                    "text" => "Условия использования",
                    "callback_data" => "termsOfUse"
                ]
            ],
            [
                [
                    "text" => "Принимаю",
                    "callback_data" => "confirming"
                ]
            ]
        ];
    }

    public static function contacts() {
        return [
            [
                [
                    "text" => "{contacts_general}",
                    "callback_data" => "general"
                ], [
                    "text" => "{contacts_access}",
                    "callback_data" => "access"
                ]
            ], [
                [
                    "text" => "{contacts_advertising}",
                    "callback_data" => "advertising"
                ], [
                    "text" => "{contacts_offers}",
                    "callback_data" => "offers"
                ]
            ]
        ];
    }

    public static function SubscribedToChannel() {
        return [[[
            "text" => "{subscribed}",
            "url" => "https://t.me/".CHANNEL_SUBSCRIPTION_NAME
        ]],
        [[
            "text" => "{i_subscribed}",
            "callback_data" => "i_subscribed"
        ]]];
    }

    public static function filters($page = 1) {
        $filtersAll = json_decode(file_get_contents(public_path().'/json/dict.json'), true);
        $buttons = [];
        $filters = array_chunk($filtersAll, 8);
        foreach ($filters[$page - 1] as $id => $filter) {
            $buttons[] = [[
                "text" => ucfirst($filter['description']),
                "callback_data" => 'apply_filter__'.$id
            ]];
        }

        $nextPage = $page+1;
        $prevPage = $page-1;

        if($page == 1) {
            $buttons[] = [[
                "text" => "{next}",
                "callback_data" => 'filters__'.$nextPage
            ]];
        }
        elseif($page == ceil(count($filtersAll) / 8)) {
            $buttons[] = [[
                "text" => "{prev}",
                "callback_data" => 'filters__'.$prevPage
            ]];
        }
        else {
            $buttons[] = [[
                "text" => "{prev}",
                "callback_data" => 'filters__'.$prevPage
            ], [
                "text" => "{next}",
                "callback_data" => 'filters__'.$nextPage
            ]];
        }
        return $buttons;
    }

    public static function group() {
        return [[[
            "text" => "{group}",
            "url" => 'https://t.me/'.GROUP_TELEGRAM
        ]]];
    }

    public static function languages() {
        $buttons = [];
        $buttons[] = [[
            'text' => DEFAULT_LANGUAGE,
            'callback_data' => 'lang__0'
        ]];
        $languages = Language::all();
        foreach($languages as $l) {
            $buttons[] = [[
                'text' => base64_decode($l->emoji).' '.$l->name,
                'callback_data' => 'lang__'.$l->code
            ]];
        }
        return $buttons;
    }

    public static function share($url) {
        return [[[
            "text" => "{to_share}",
            "url" => "https://telegram.me/share/url?url=$url"
        ]]];
    }

    public static function paidAccess($userId) {
        return [[[
            'text' => '{payment}',
            'url' => url("/payment/method/telegram/$userId/".PAID_ACCESS_COST."/paid_access")
        ]]];
    }

    public static function FiltersTelegram(int $id, int $count) {
        $prev = $id-1;
        $next = $id+1;
        if($id != 0) {
            if($id == $count-1) {
                return [[[
                    'text' => '{apply}',
                    'callback_data' => 'apply_filter__'.$id
                ]], [[
                    'text' => '{prev}',
                    'callback_data' => 'updateFiltersTelegram__'.$prev
                ]]];
            }
            else {
                return [[[
                    'text' => '{apply}',
                    'callback_data' => 'apply_filter__'.$id
                ]], [[
                    'text' => '{prev}',
                    'callback_data' => 'updateFiltersTelegram__'.$prev
                ],[
                    'text' => '{next}',
                    'callback_data' => 'updateFiltersTelegram__'.$next
                ]]];
            }
        }
        else {
            return [[[
                        'text' => '{apply}',
                        'callback_data' => 'apply_filter__'.$id
                    ]], [[
                        'text' => '{next}',
                        'callback_data' => 'updateFiltersTelegram__'.$next
                    ]]];
        }

    }

    private static function pagesButtons($model, $methodPages, $method, $name = 'name', $page = '1') {
        $count = $model::count();

        $obj = $model::offset(($page - 1) * COUNT_BTN_PAGE_TGM)->limit(COUNT_BTN_PAGE_TGM)->get();

        $buttons = [];

        foreach($obj as $o) {
            $buttons[] = [
                'text' => $o->$name,
                'callback_data' => $method.'__'.$o->id
            ];
        }

        $countPage = ceil($count / COUNT_BTN_PAGE_TGM);

        $nextPage = (int) $page+1;
        $prewPage = (int) $page-1;

        $buttons = array_chunk($buttons, COUNT_BTN_STR_TGM);

        if($countPage > 1) {
            if($page == 1) {
                $buttons[] = [[
                    'text' => '{next_buttons}',
                    'callback_data' => $methodPages.'__'.$nextPage
                ]];
            }
            elseif($page == $countPage) {
                $buttons[] = [[
                    'text' => '{prew_buttons}',
                    'callback_data' => $methodPages.'__'.$prewPage
                ]];
            }
            else {
                $buttons[] = [[
                    'text' => '{prew_buttons}',
                    'callback_data' => $methodPages.'__'.$prewPage
                ],
                [
                    'text' => '{next_buttons}',
                    'callback_data' => $methodPages.'__'.$nextPage
                ]];
            }
        }

        return $buttons;
    }
}
