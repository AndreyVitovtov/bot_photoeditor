
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsMainSeeder extends Seeder {

    /**
     * Run the admin_chatlive.settings_main seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('settings_main')->insert(
            [
                ["prefix" => "viber_token","name" => "Viber token:","name_us" => "Viber token:","value" => "","type" => "text"],
                ["prefix" => "telegram_token","name" => "Telegram token:","name_us" => "Telegram token:","value" => "","type" => "text"],
                ["prefix" => "name_viber_bot","name" => "Название Viber бота:","name_us" => "Viber bot name:","value" => "","type" => "text"],
                ["prefix" => "name_telegram_bot","name" => "Название Telegram бота:","name_us" => "Telegram bot name:","value" => "","type" => "text"],
                ["prefix" => "default_language","name" => "Язык по умолчанию:","name_us" => "Default language:","value" => "🇷🇺 Русский","type" => "text"],
                ["prefix" => "count_mailing","name" => "По сколько сообщений рассылать за один раз:","name_us" => "How many messages to send at one time:","value" => "200","type" => "number"],
                ["prefix" => "sleep_mailing","name" => "Задержка между рассылками, секунд:","name_us" => "Delay between mailings, seconds:","value" => "2","type" => "number"],
            ]
        );

    }
}
