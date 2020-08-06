<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralSystemTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'referral_system';

    /**
     * Run the migrations.
     * @table referral_system
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('referrer');
            $table->unsignedInteger('referral');
            $table->date('date');
            $table->time('time');

            $table->index(["referral"], 'fk_referral_system_users2_idx');

            $table->index(["referrer"], 'fk_referral_system_users1_idx');


            $table->foreign('referrer', 'fk_referral_system_users1_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('referral', 'fk_referral_system_users2_idx')
                ->references('id')->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
