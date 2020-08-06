<?php


namespace App\Events;


use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestEvent implements ShouldBroadcast
{

    public $payload;
    public $userID;
    public $chatId;

    public function __construct($chatId, int $userID, $payload)
    {
        $this->chatId = $chatId;
        $this->userID = $userID;
        $this->payload = $payload;
    }

    public function broadcastOn()
    {
        // Настройте авторизацию для использования защищённого канала
        // return new PrivateChannel('App.User.' . $this->userID);

        return new Channel('chat_'.$this->chatId);
    }
}
