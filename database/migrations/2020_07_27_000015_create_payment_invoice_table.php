<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentInvoiceTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'payment_invoice';

    /**
     * Run the migrations.
     * @table payment_invoice
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->float('amount');
            $table->integer('performed')->default('0');
            $table->date('date');
            $table->time('time');
            $table->unsignedInteger('users_id');

            $table->index(["users_id"], 'fk_payment_invoice_users1_idx');


            $table->foreign('users_id', 'fk_payment_invoice_users1_idx')
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
