<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model {
    protected $table = "statistics";
    public $timestamps = false;
    public $fillable = [
        'id',
        'count_add_books',
        'count_download_books'
    ];
}
