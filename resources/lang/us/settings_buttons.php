<?php

use App\models\SettingsButtons;

$settingsButtons = SettingsButtons::all();
$data = [];
foreach($settingsButtons as $sb) {
    $data[$sb->name] = $sb->menu_us;
}

return $data;
