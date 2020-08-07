<?php


namespace App\models;


use Illuminate\Database\Eloquent\Model;

class SettingsPages extends Model {
    public $table = "settings_pages";
    public $timestamps = false;
    public $fillable = [
        'name',
        'text',
        'description',
        'description_us'
    ];
}
