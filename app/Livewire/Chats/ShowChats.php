<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShowChats extends Component
{
    public $chats;
    public $selectedChat;
    // public Chat $chat;

    public function mount()
    {
        $this->selectedChat = Auth::user()->is_active_in_chat;
    }

    // #[On('echo-private:message-sent.' . $this->chat->id, MessageSent::class)]
    #[On('echo:message-sent,MessageSent')]
    public function bubbleUpLastMessage()
    {
        $this->chats = Auth::user()->chats()
            ->with(['users', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function ($chat) {
                return optional($chat->messages->first())->created_at;
            });
    }

    public function selectChat($chatId)
    {
        $this->selectedChat = $chatId;
        Session::put('selected_chat', $chatId);
        $this->dispatch('chatSelected', $chatId);
        Auth::user()->update(['is_active_in_chat' => $chatId]);
    }

    #[On('echo:message-read,MessageRead')]
    public function render()
    {
        $this->bubbleUpLastMessage();

        return view('livewire.chats.show-chats', [
            'chats' => $this->chats,
            'selectedChat' => $this->selectedChat,
        ]);
    }
}
