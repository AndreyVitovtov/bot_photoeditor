<?php


namespace App\models;


use Illuminate\Database\Eloquent\Model;

class SettingsMain extends Model {
    public $table = 'settings_main';
    public $timestamps = false;
    public $fillable = [
        'prefix',
        'name',
        'name_us',
        'value',
        'type'
    ];
}
