<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'contacts';

    /**
     * Run the migrations.
     * @table contacts
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('users_id');
            $table->unsignedInteger('contacts_type_id');
            $table->text('text');
            $table->date('date');
            $table->time('time');

            $table->index(["users_id"], 'fk_contacts_users1_idx');

            $table->index(["contacts_type_id"], 'fk_contacts_contacts_type1_idx');


            $table->foreign('users_id', 'fk_contacts_users1_idx')
                ->references('id')->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('contacts_type_id', 'fk_contacts_contacts_type1_idx')
                ->references('id')->on('contacts_type')
                ->onDelete('no action')
                ->onUpdate('no action');
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
