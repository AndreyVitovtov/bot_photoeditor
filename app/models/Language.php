<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model {
    protected $table = 'languages';
    public $timestamps = false;
    public $fillable = [
        'id',
        'name',
        'code',
        'emoji'
    ];
}
