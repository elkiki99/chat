<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Events\MessengerEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShowChat extends Component
{
    public $chat;
    public $messages;
    public $body;

    protected $listeners = ['chatSelected' => 'changeToSelectedChat'];

    public function mount()
    {
        $this->loadChat(Session::get('selected_chat'));
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
    }

    public function updateChatInRealTime()
    {
        $this->messages = $this->chat ? $this->chat->messages : [];
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::find($chatId);
        $this->updateChatInRealTime();
    }

    public function sendMessage()
    {
        MessengerEvent::dispatch($this->chat->id, Auth::id(), $this->body, 'sent');
        $this->updateChatInRealTime();
        $this->body = '';
    }

    #[On('echo:message, MessengerEvent')]
    public function refreshMessages($event)
    {
        $this->messages = $event->messages;
    }

    public function render()
    {
        // $this->updateChatInRealTime();

        return view('livewire.chats.show-chat');
    }
}