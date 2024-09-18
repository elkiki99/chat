<?php

namespace App\Livewire\Chats;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShowChats extends Component
{
    public $chats;
    public $selectedChat;

    public function mount()
    {
        $this->chats = Auth::user()->chats()->with('users')->get();
        $this->selectedChat = Session::get('selected_chat') ?? null;
    }

    public function selectChat($chatId)
    {
        $this->selectedChat = $chatId;
        Session::put('selected_chat', $chatId);
        $this->dispatch('chatSelected', $chatId);
    }

    public function render()
    {
        return view('livewire.chats.show-chats', [
            'chats' => $this->chats,
            'selectedChat' => $this->selectedChat
        ]);
    }
}
