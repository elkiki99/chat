<?php

namespace App\Livewire\Chats;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ShowChats extends Component
{
    public $search = '';
    public $selectedChat = null;
    public $chats = [];
    public $allChats = [];

    protected $listeners = [
        'chatCreated' => 'loadChats',
        'contactSelected' => 'selectChat',
    ];
    
    public function mount()
    {
        $this->selectedChat = Auth::user()->is_active_in_chat;
        $this->fetchChats();
    }

    public function loadChats()
    {
        $this->fetchChats();
    }

    public function fetchChats()
    {
        $this->allChats = Auth::user()->chats()
            ->with(['users', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function ($chat) {
                return optional($chat->messages->first())->created_at;
            });
        
        $this->updateChatsInRealTime();
    }

    public function updateChatsInRealTime()
    {
        $this->chats = $this->allChats;
    }

    #[On('echo:message-sent,MessageSent')]
    public function bubbleUpLastMessage()
    {
        $this->fetchChats();
    }

    public function selectChat($chatId)
    {
        $this->selectedChat = $chatId;
        $this->dispatch('chatSelected', $chatId);
        Auth::user()->update(['is_active_in_chat' => $chatId]);
    }

    public function updatedSearch($value)
    {
        $this->chats = $this->allChats->filter(function ($chat) use ($value) {
            if ($chat->is_group) {
                return stripos($chat->name, $value) !== false;
            } else {
                $otherUser = $chat->users->where('id', '!=', Auth::id())->first();
                return $otherUser && stripos($otherUser->name, $value) !== false;
            }
        });
    }

    #[On('echo:message-read,MessageRead')]
    public function render()
    {
        return view('livewire.chats.show-chats', [
            'chats' => $this->chats,
        ]);
    }
}
