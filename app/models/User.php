<?php


namespace App\models;


use Illuminate\Database\Eloquent\Model;

class User extends Model {
    protected $table = "admin";
    public $timestamps = false;
    public $fillable = [
        'name',
        'login',
        'password'
    ];
}
