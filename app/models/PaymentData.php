<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class PaymentData extends Model {
    protected $table = "payment_data";
    public $timestamps = false;
    public $fillable = [
        'id',
        'qiwi_token',
        'qiwi_webhook_id',
        'qiwi_public_key',
        'yandex_money_sacret_key',
        'yandex_money_wallet',
        'webmoney_wallet',
        'paypal_facilitator'
    ];
}
