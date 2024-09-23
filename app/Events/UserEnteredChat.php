<?php

namespace App\Events;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class UserEnteredChat implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $chat;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Chat $chat)
    {
        $this->user = $user;
        $this->chat = $chat;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('user-entered-chat');
    }

    public function broadcastWith()
    {
        return [
            'chat_id' => $this->chat->id,
            'user_id' => $this->user->id,
        ];
    }
}
