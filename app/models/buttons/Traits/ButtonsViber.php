<?php


namespace App\models\buttons\Traits;


use App\models\SettingsButtons;

trait ButtonsViber {

    private $btnBg;
    private $btnSize;
    private $fontColor;

    public function __construct() {
        $viewButtons = SettingsButtons::getView();
        $this->btnBg = $viewButtons->background;
        $this->fontColor = $viewButtons->color_text;
        $this->btnSize = $viewButtons->size_text;
    }

    private function button($columns, $rows, $actionBody, $text, $silent = "false") {
        return [
            'Columns' => $columns,
            'Rows' => $rows,
            'ActionType' => 'reply',
            'ActionBody' => $actionBody,
            'BgColor' => $this->btnBg,
            'Silent' => $silent,
            'Text' => '<font color="'.$this->fontColor.'" size="'.$this->btnSize.'">'.$text.'</font>',
            'TextSize' => 'large'
        ];
    }

//    private function button_url($columns, $rows, $url, $text, $silent = "true") {
//        return [
//            'Columns' => $columns,
//            'Rows' => $rows,
//            'ActionType' => 'open-url',
//            'ActionBody' => $url,
//            'OpenURLType' => 'internal',
//            'BgColor' => $this->btnBg,
//            'Silent' => $silent,
//            'Text' => '<font color="'.$this->fontColor.'" size="'.$this->btnSize.'">'.$text.'</font>',
//            'TextSize' => 'large'
//        ];
//    }

    public function main_menu() {
        return [
            $this->button(6, 1, 'main_menu', '{main_menu}')
        ];
    }
}
