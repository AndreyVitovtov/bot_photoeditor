<?php

namespace App\models\buttons;

use App\models\Book;
use App\models\BotUsers;
use App\models\Heading;
use App\models\Khatma;
use App\models\Page;
use App\models\Quran;
use App\models\Recipe;
use App\models\SettingsButtons;

class ButtonsViber {
    private $btnBg;
    private $btnSize;
    private $fontColor;

    public function __construct() {
        $viewButtons = SettingsButtons::getView();
        $this->btnBg = $viewButtons->background;
        $this->fontColor = $viewButtons->color_text;
        $this->btnSize = $viewButtons->size_text;
    }

    private function button($columns, $rows, $actionBody, $text, $custom = [], $silent = "false") {
        $btnBg = isset($custom['btnBg']) ? $custom['btnBg'] : $this->btnBg;
        $fontColor = isset($custom['fontColor']) ? $custom['fontColor'] : $this->fontColor;
        $btnSize = isset($custom['btnSize']) ? $custom['btnSize'] : $this->btnSize;

        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => 'reply',
            'ActionBody' => $actionBody,
            'BgColor' => $btnBg,
            'Silent' => $silent,
            'Text' => '<font color="'.$fontColor.'" size="'.$btnSize.'">'.$text.'</font>',
            'TextSize' => 'large',
            'TextVAlign' => 'middle',
			'TextHAlign' => 'center',
        ];
    }

    private function button_url($columns, $rows, $url, $text, $silent = "true") {
        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => 'open-url',
            'ActionBody' => $url,
            'OpenURLType' => 'internal',
            'BgColor' => $this->btnBg,
            'Silent' => $silent,
            'Text' => '<font color="'.$this->fontColor.'" size="'.$this->btnSize.'">'.$text.'</font>',
            'TextSize' => 'large'
        ];
    }

    private function button_img($columns, $rows, $actionType, $actionBody, $image, $text = "") {
        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => $actionType,
            'ActionBody' => $actionBody,
            'Image' => $image,
            'Text' => $text,
            'TextVAlign' => 'middle',
			'TextHAlign' => 'center'
        ];
    }

    public function start() {
        return [
            $this->button(6, 1, 'start', '{start}')
        ];
    }

    public function main_menu($userId) {
        $user = BotUsers::find($userId);

        if($user->access == '1') {
            if($user->access_free == '1') {
                return [
                    $this->button(6, 1, 'process_photo', '{process_photo}'),
                    $this->button(6, 1, 'paid_access', '{paid_access}'),
                    $this->button(6, 1, 'contacts', '{contacts}'),
                    $this->button(6, 1, 'group', '{group}'),
                    $this->button(6, 1, 'languages', '{languages}'),
                ];
            }
            else {
                return [
                    $this->button(6, 1, 'process_photo', '{process_photo}'),
                    $this->button(6, 1, 'contacts', '{contacts}'),
                    $this->button(6, 1, 'group', '{group}'),
                    $this->button(6, 1, 'languages', '{languages}'),
                ];
            }
        }
        else {
            return [
                $this->button(6, 1, 'process_photo', '{process_photo}'),
                $this->button(6, 1, 'free_access', '{free_access}'),
                $this->button(6, 1, 'paid_access', '{paid_access}'),
                $this->button(6, 1, 'contacts', '{contacts}'),
                $this->button(6, 1, 'group', '{group}'),
                $this->button(6, 1, 'languages', '{languages}'),
            ];
        }
    }

    public function back() {
        return [
            $this->button(6, 1, 'back', '{back}')
        ];
    }

    public function filters($page = 1) {
        $countButtons = 6;
        $filtersAll = json_decode(file_get_contents(public_path()."/json/_dict.json"), true);
        $filters = array_chunk($filtersAll, $countButtons);

        $buttons = [];
        foreach($filters[$page-1] as $filter) {
            $buttons[] = $this->button_img(
                6,
                6,
                'reply',
                'apply_filter__'.$filter['id'],
                $filter['image_link']
            );
            $buttons[] = $this->button(
                6,
                1,
                'apply_filter__'.$filter['id'],
                '{apply}',
                [
                    'btnBg' => '#ff7e3e',
                    'fontColor' => '#ffffff',
                    'btnSize' => '20'
                ]
            );
        }

        return $buttons;
    }

    public function moreBack($page = 1) {
        $countButtons = 6;
        $nextPage = $page+1;
        $count = count(json_decode(file_get_contents(public_path()."/json/_dict.json"), true));

        if($count > $countButtons) {
            if(ceil($count / $countButtons) > $page) {
                return [
                    $this->button(6, 1, 'filters__' . $nextPage, '{more}'),
                    $this->button(6, 1, 'back', '{back}')
                ];
            }
        }
        return $this->back();
    }

    public function contacts() {
        return [
            $this->button(6, 1, 'general', '{contacts_general}'),
            $this->button(6, 1, 'access', '{contacts_access}'),
            $this->button(6, 1, 'advertising', '{contacts_advertising}'),
            $this->button(6, 1, 'offers', '{contacts_offers}'),
        ];
    }

    public function languages() {

    }

    private function pagesButtons($res, $method, $name = 'name', $page = '1') {
        $res = array_slice($res, (($page - 1) * 42), 42);
        $buttons = [];
        foreach($res as $r) {
            $buttons[] = $this->button(6, 1, $method.'__'.$r->id, $r->$name);
        }

        return $buttons;
    }
}
