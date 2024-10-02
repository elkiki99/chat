<?php

namespace App\Livewire\Components\Chats;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatHeader extends Component
{
    public $chat;
    public $user;

    protected $listeners = [
        'chatSelected' => 'changeToSelectedChat',
    ];

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::find($chatId);
        $this->user = $this->chat->users->where('id', '!==', Auth::id())->first();
    }

    public function mount(Chat $chat)
    {
        $this->loadChat($chat->id);
    }

    public function removeContact($userId)
    {
        Auth::user()->contacts()->detach($userId);
        $this->dispatch('contactRemoved', $userId);
    }

    public function render()
    {
        return view('livewire.components.chats.chat-header', [
            'user' => $this->user,
            'chat' => $this->chat,
        ]);
    }
}
