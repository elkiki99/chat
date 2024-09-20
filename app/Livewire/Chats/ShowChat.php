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
    public $body;
    public $messages;

    protected $listeners = ['chatSelected' => 'changeToSelectedChat'];

    public function mount()
    {
        $this->loadChat(Session::get('selected_chat'));
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::find($chatId);
        $this->updateChatInRealTime();
        $this->dispatch('scrollDown');
    }

    public function sendMessage()
    {
        $this->chat->messages()->create([
            'chat_id' => $this->chat->id,
            'user_id' => Auth::id(),
            'body' => $this->body,
            'status' => 'sent',
        ]);
        
        $this->body = '';
        $this->updateChatInRealTime();
        $this->dispatch('scrollDown');
        MessengerEvent::dispatch($this->chat->id);
    }

    #[On('echo:message-sent,MessengerEvent')]
    public function updateChatInRealTime()
    {
        $this->messages = $this->chat ? $this->chat->messages : [];
    }

    public function render()
    {
        return view('livewire.chats.show-chat');
    }
}
