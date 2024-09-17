<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class ShowChat extends Component
{
    public $chat;
    public $messages;

    protected $listeners = ['chatSelected' => 'updateChat'];

    public function mount()
    {
        $this->loadChat(Session::get('selected_chat'));
    }

    public function updateChat($chatId)
    {
        $this->loadChat($chatId);
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::find($chatId);
        $this->messages = $this->chat ? $this->chat->messages : [];
    }

    public function render()
    {
        return view('livewire.chats.show-chat');
    }
}