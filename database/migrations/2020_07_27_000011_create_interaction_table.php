<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInteractionTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'interaction';

    /**
     * Run the migrations.
     * @table interaction
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('users_id');
            $table->string('command')->nullable();
            $table->text('params')->nullable();

            $table->index(["users_id"], 'fk_interaction_users1_idx');


            $table->foreign('users_id', 'fk_interaction_users1_idx')
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
