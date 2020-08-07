
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsButtonsSeeder extends Seeder {

    /**
     * Run the admin_chatlive.settings_buttons seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('settings_buttons')->insert(
            [
                ["name" => "start","text" => "U3RhcnQg8J+agA==","menu" => "Старт","menu_us" => "Start"],
                ["name" => "back","text" => "8J+UmSDQndCw0LfQsNC0","menu" => "back","menu_us" => "back"],
                ["name" => "contacts","text" => "4pyJINCf0L7QtNC00LXRgNC20LrQsA==","menu" => "Главное меню","menu_us" => "Main menu"],
                ["name" => "contacts_general","text" => "0J7QsdGJ0LjQtSDQstC+0L/RgNC+0YHRiw==","menu" => "Поддержка","menu_us" => "Support"],
                ["name" => "contacts_access","text" => "0JTQvtGB0YLRg9C/INC4INC+0L/Qu9Cw0YLQsA==","menu" => "Поддержка","menu_us" => "Support"],
                ["name" => "contacts_advertising","text" => "0KDQtdC60LvQsNC80LA=","menu" => "Поддержка","menu_us" => "Support"],
                ["name" => "contacts_offers","text" => "0J/RgNC10LTQu9C+0LbQtdC90LjRjw==","menu" => "Поддержка","menu_us" => "Support"],
                ["name" => "languages","text" => "8J+RhSDQr9C30YvQutC4","menu" => "Главное меню","menu_us" => "Main menu"],
                ["name" => "payment","text" => "8J+SuCDQntC/0LvQsNGC0LA=","menu" => "Оплата","menu_us" => "Payment"],
            ]
        );
    }
}
