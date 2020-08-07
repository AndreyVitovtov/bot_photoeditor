<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('qiwi_token');
            $table->string('qiwi_webhook_id');
            $table->string('qiwi_public_key');
            $table->string('yandex_money_secret_key');
            $table->string('yandex_money_wallet');
            $table->string('webmoney_wallet');
            $table->string('webmoney_secret_key');
            $table->string('paypal_facilitator');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_data');
    }
}
