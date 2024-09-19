<?php

namespace App\Livewire\Messages;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateMessage extends Component
{
    public $chat;
    public $body;

    public function sendMessage()
    {
        $this->chat->messages()->create([
            'chat_id' => $this->chat->id,   
            'user_id' => Auth::id(),
            'body' => $this->body,
            'status' => 'sent',
        ]);

        $this->body = '';
    }

    public function render()
    {
        return view('livewire.messages.create-message');
    }
}
