<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Events\MessengerEvent;
use Illuminate\Broadcasting\PrivateChannel;
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
        event(new MessengerEvent($this->chat->id, Auth::id(), $this->body, 'sent'));

        $this->chat->messages()->create([
            'chat_id' => $this->chat->id,
            'user_id' => Auth::id(),
            'body' => $this->body,
            'status' => 'sent',
        ]);
        
        $this->body = '';
        $this->dispatch('scrollDown');
        $this->updateChatInRealTime();
    }

    #[On('echo:messages,MessengerEvent')]
    public function onMessengerEvent($message)
    {
        $this->updateChatInRealTime($message);
    }

    public function render()
    {
        return view('livewire.chats.show-chat');
    }
}
