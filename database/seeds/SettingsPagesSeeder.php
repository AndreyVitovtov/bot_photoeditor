
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

        DB::table('admin_chatlive.settings_pages')->insert(
            ["id" => "1","name" => "greeting","text" => "0J3QsNC20LzQuNGC0LUgU3RhcnQg8J+agCDQtNC70Y8g0L/RgNC+0LTQvtC70LbQtdC90LjRjw==","description" => "Приветствие","description_us" => "Greeting"]
        );

    }
}
