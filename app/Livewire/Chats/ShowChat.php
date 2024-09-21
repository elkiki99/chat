<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Events\MessageSent;
use App\Events\UserEnteredChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShowChat extends Component
{
    public $chat;
    public $body;
    public $messages;

    protected $listeners = [
        'chatSelected' => 'changeToSelectedChat',
        // 'userEnteredChat' => 'updateSeenMessages', // Escuchar el evento aquí
    ];

    public function mount()
    {
        $this->loadChat(Session::get('selected_chat'));
    }

    #[On('echo:user-entered-chat,UserEnteredChat')]
    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
        $this->userEnteredChat($chatId, Auth::id());
    }

    public function userEnteredChat($chatId, $userId)
    {
        UserEnteredChat::dispatch($chatId, $userId);
        $this->updateChatInRealTime();
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::find($chatId);
        $this->updateChatInRealTime();
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
        MessageSent::dispatch();
    }

    #[On('echo:message-sent,MessageSent')]
    public function updateChatInRealTime()
    {
        $this->messages = $this->chat ? $this->chat->messages : [];
    }

    public function render()
    {
        return view('livewire.chats.show-chat', [
        ]);
    }
}
