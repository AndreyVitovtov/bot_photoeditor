
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsPagesSeeder extends Seeder {

    /**
     * Run the admin_chatlive.settings_pages seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('settings_pages')->insert(
            [
                ["name" => "greeting","text" => "0J3QsNC20LzQuNGC0LUgU3RhcnQg8J+agCDQtNC70Y8g0L/RgNC+0LTQvtC70LbQtdC90LjRjw==","description" => "Приветствие","description_us" => "Greeting"],
                ["name" => "welcome","text" => "0J/RgNC40LLQtdGC0YHRgtCy0LjQtSDQsdC+0YLQsA==","description" => "Приветствие","description_us" => "Greeting"],
                ["name" => "main_menu","text" => "0JPQu9Cw0LLQvdC+0LUg0LzQtdC90Y4=","description" => "Главное меню","description_us" => "Main menu"],
                ["name" => "unknown_team","text" => "0J3QtdC40LfQstC10YHRgtC90LDRjyDQutC+0LzQsNC90LTQsCDwn5qn","description" => "Неизвестная команда","description_us" => "Unknown team"],
                ["name" => "error","text" => "0KfRgtC+INGC0L4g0L/QvtGI0LvQviDQvdC1INGC0LDQuigoINCf0L7Qv9GA0L7QsdGD0LnRgtC1INC/0L7Qt9C20LVcblxue3tlcnJvcn19","description" => "Ошибка","description_us" => "Error"],
                ["name" => "send_support_message","text" => "0J7RgtC/0YDQsNCy0LjRgtGMINGB0L7QvtCx0YnQtdC90LjQtSDQsiDQv9C+0LTQtNC10YDQttC60YM=","description" => "Отправить сообщение в поддержку","description_us" => "Send a message to support"],
                ["name" => "select_topic","text" => "0JLRi9Cx0LXRgNC40YLQtSDRgtC10LzRgzo=","description" => "Выберите тему","description_us" => "Choose a topic"],
                ["name" => "send_message","text" => "0J7RgtC/0YDQsNCy0YzRgtC1INGB0L7QvtCx0YnQtdC90LjQtQ==","description" => "Отправьте сообщение","description_us" => "Send a message"],
                ["name" => "message_sending","text" => "0KHQvtC+0LHRidC10L3QuNC1INC+0YLQv9GA0LDQstC70LXQvdC+","description" => "Сообщение отправлено","description_us" => "Message sent"],
                ["name" => "choose_language","text" => "0JLRi9Cx0LXRgNC40YLQtSDRj9C30YvQug==","description" => "Выберите язык","description_us" => "Choose language"],
                ["name" => "language_saved","text" => "0K/Qt9GL0Log0YHQvtGF0YDQsNC90LXQvQ==","description" => "Язык сохранен","description_us" => "Language saved"],
                ["name" => "payment_header","text" => "0J7Qv9C70LDRgtCw","description" => "Шапка страницы Оплата","description_us" => "Payment page header"],
                ["name" => "payment_title","text" => "0JLRi9Cx0LXRgNC40YLQtSDRgdC/0L7RgdC+0LEg0L7Qv9C70LDRgtGLOg==","description" => "Заголовок страницы Оплата","description_us" => "Payment page title"],
                ["name" => "payment_sum","text" => "0KHRg9C80LzQsDo=","description" => "Сумма, страница Оплата","description_us" => "Amount, page Payment"],
                ["name" => "payment_next","text" => "0JTQsNC70LXQtQ==","description" => "Далее, страница Оплата","description_us" => "Next, the Payment page"],
                ["name" => "payment_details","text" => "0JTQsNC90L3Ri9C1INC00LvRjyDQvtC/0LvQsNGC0Ys6","description" => "Данные для оплаты, страница Оплата","description_us" => "Payment data, page Payment"],
                ["name" => "payment_method","text" => "0KHQv9C+0YHQvtCxINC+0L/Qu9Cw0YLRizo=","description" => "Способ оплаты, страница Оплата","description_us" => "Payment method, page Payment"],
                ["name" => "payment_purpose","text" => "0J3QsNC30L3QsNGH0LXQvdC40LU6","description" => "Назначение, страница Оплата","description_us" => "Purpose, page Payment"],
                ["name" => "payment_pay","text" => "0J7Qv9C70LDRgtC40YLRjA==","description" => "Оплатить, страница Оплата","description_us" => "Pay, page Payment"],
                ["name" => "payment_currency","text" => "UlVC","description" => "Валюта, страница Оплата","description_us" => "Currency, page Payment"],
            ]
        );
    }
}
