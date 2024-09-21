<?php

namespace App\Livewire\Chats;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShowChats extends Component
{
    public $chats;
    public $selectedChat;

    public function mount()
    {
        $this->selectedChat = Session::get('selected_chat') ?? null;
    }

    public function selectChat($chatId)
    {
        $this->selectedChat = $chatId;
        Session::put('selected_chat', $chatId);
        $this->dispatch('chatSelected', $chatId);
    }

    #[On('echo:message-sent,MessageSent', 'echo:user-entered-chat,UserEnteredChat')]
    public function render()
    {
        $this->chats = Auth::user()->chats()
        ->with(['users', 'messages' => function ($query) {
            $query->latest()->limit(1);
        }])
        ->get()
        ->sortByDesc(function ($chat) {
            return optional($chat->messages->first())->created_at;
        });

        return view('livewire.chats.show-chats', [
            'chats' => $this->chats,
            'selectedChat' => $this->selectedChat
        ]);
    }
}
