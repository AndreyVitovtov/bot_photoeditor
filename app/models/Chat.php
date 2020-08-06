<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model {
    protected $table = 'chats';
    public $timestamps = false;
    public $fillable = [
        'id',
        'users_id',
        'name',
        'link',
        'date',
        'time'
    ];

    public function messages() {
        return $this->hasMany(MessangesModel::class, 'chats_id');
    }

    public function users() {
        return $this->belongsToMany(
            UsersChats::class,
            'users_chats_has_chats',
            'chats_id',
            'users_chats_id'
        );
    }

    public function creator() {
        return $this->belongsTo(BotUsers::class, 'users_id');
    }
}
