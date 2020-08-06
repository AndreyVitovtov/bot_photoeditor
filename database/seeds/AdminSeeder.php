
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder {

    /**
     * Run the admin_chatlive.admin seeds.
     *
     * @return void
     */
    public function run() {

        DB::table('admin_chatlive.admin')->insert(
            ["id" => "1","login" => "admin","password" => '$2y$10$eYxRUgU2XiJH3MN86XfTweKFmL3HJDuu2vhSnZ7D61TkgJDV7QIsq',"name" => "Administrator","language" => "us"]
        );


    }
}
