
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactsTypeSeeder extends Seeder {

    /**
     * Run the admin_chatlive.contacts_type seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('contacts_type')->insert(
            ["id" => "1","type" => "general"]
        );

        DB::table('contacts_type')->insert(
            ["id" => "2","type" => "access"]
        );

        DB::table('contacts_type')->insert(
            ["id" => "3","type" => "advertising"]
        );

        DB::table('contacts_type')->insert(
            ["id" => "4","type" => "offers"]
        );


    }
}
