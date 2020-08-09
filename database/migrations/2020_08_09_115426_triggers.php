<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Triggers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        DB::unprepared(
//            'CREATE DEFINER = CURRENT_USER TRIGGER `process_photos_AFTER_INSERT` AFTER INSERT ON `process_photos` FOR EACH ROW
//            BEGIN
//                UPDATE SET
//            END'
//        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
