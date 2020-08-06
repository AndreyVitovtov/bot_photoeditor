<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsMainTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'settings_main';

    /**
     * Run the migrations.
     * @table settings_main
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('prefix');
            $table->string('name');
            $table->string('name_us')->nullable();
            $table->text('value')->nullable();
            $table->string('type');
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
