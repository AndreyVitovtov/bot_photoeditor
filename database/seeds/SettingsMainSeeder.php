
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

        DB::table('admin_chatlive.settings_main')->insert(
            ["id" => "1","prefix" => "viber_token","name" => "Viber token:","name_us" => "Viber token:","value" => "4bbd3c010967dd97-ffddf41c9d6dfabe-fd1a5c2ff793191c","type" => "text"]
        );

    }
}
