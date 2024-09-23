<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\User;
use Livewire\Component;
use App\Events\MessageSent;
use Livewire\Attributes\On;
use App\Events\UserEnteredChat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShowChat extends Component
{
    public $chat;
    public $body;
    public $message;
    public $messages;


    protected $listeners = [
        'chatSelected' => 'changeToSelectedChat',
    ];

    public function mount()
    {
        $this->loadChat(Session::get('selected_chat'));
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
        $this->dispatch('scrollDown');
        $this->userEnteredChat(Auth::user(), $this->chat);
    }

    public function userEnteredChat(User $user, Chat $chat)
    {
        UserEnteredChat::dispatch($user, $chat);
        $this->updateChatInRealTime();
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::with('users', 'messages')->find($chatId);
        $this->updateChatInRealTime();
    }

    public function sendMessage()
    {
        if (empty(trim($this->body))) {
            return;
        }

        $message = $this->chat->messages()->create([
            'chat_id' => $this->chat->id,
            'user_id' => Auth::id(),
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->updateChatInRealTime();
        $this->dispatch('scrollDown');
        MessageSent::dispatch($message);
    }

    #[On('echo:message-sent,MessageSent')]
    public function updateChatInRealTime()
    {
        $this->messages = $this->chat ? $this->chat->messages : collect();
    }

    // #[On('echo:user-entered-chat,UserEnteredChat')]
    public function render()
    {
        return view('livewire.chats.show-chat');
    }
}
