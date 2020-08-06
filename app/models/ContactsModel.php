<?php


namespace App\models;


use Illuminate\Database\Eloquent\Model;

class ContactsModel extends Model {
    protected $table = 'contacts';
    public $timestamps = false;
    public $fillable = [
        'id',
        'contacts_type_id',
        'users_id',
        'text',
        'date',
        'time'
    ];

    public function type() {
        return $this->belongsTo(ContactsType::class, 'contacts_type_id');
    }

    public function users() {
        return $this->hasOne(BotUsers::class, 'id', 'users_id');
    }
}
