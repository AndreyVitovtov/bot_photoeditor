
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

        DB::table('admin_chatlive.settings_buttons')->insert(
            ["id" => "1","name" => "start","text" => "U3RhcnQg8J+agA==","menu" => "Старт","menu_us" => "Start"]
        );
    }
}
