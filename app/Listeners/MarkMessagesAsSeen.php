<?php

namespace App\Listeners;

use App\Models\Message;
use App\Events\UserEnteredChat;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MarkMessagesAsSeen
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserEnteredChat $event): void
    {
        $messages = Message::where('chat_id', $event->chatId)
            ->where('user_id', '!=', $event->userId)
            ->get();

        foreach ($messages as $message) {
            $message->seenBy()->syncWithoutDetaching([$event->userId]);
        }
    }
}
