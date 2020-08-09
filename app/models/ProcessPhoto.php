<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ProcessPhoto extends Model {
    protected $table = 'process_photos';
    public $timestamps = false;
    public $fillable = [
        'users_id',
        'date',
        'time'
    ];
}
