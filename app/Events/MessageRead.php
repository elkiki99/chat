<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class MessageRead implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageIds;  // Cambiado para almacenar IDs de mensajes
    public $user;    
    public $chatId;      // Agregado para almacenar el ID del chat

    /**
     * Create a new event instance.
     */
    public function __construct(array $messageIds, User $user, $chatId)
    {
        $this->messageIds = $messageIds;
        $this->user = $user;
        $this->chatId = $chatId; // Almacenar ID del chat
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.Chat.' . $this->chatId),
            new PrivateChannel('App.Models.User.' . $this->user->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'messageIds' => $this->messageIds, // Enviar los IDs de los mensajes
            'user' => $this->user,
        ];
    }
}