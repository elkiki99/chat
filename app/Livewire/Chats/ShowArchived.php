<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShowArchived extends Component
{
    public $search = '';
    public $selectedChat = null;
    public $chats = [];
    public $allChats = [];
    public $chat;
    public $user;

    public function getListeners(): array
    {
        $listeners = [
            // 'chatCreated' => 'pushLastMessage',
            'chatUnarchived' => 'unarchiveChatAndUpdate',
        ];

        foreach ($this->chats as $chat) {
            if ($chat) {
                $listeners['echo-private:App.Models.Chat.' . $chat->id . ',MessageSent'] = 'fetchChats';
                $listeners['echo-private:App.Models.Chat.' . $chat->id . ',MessageRead'] = 'updateChatInRealTime';
            }
        }

        return $listeners;
    }

    public function mount(): void
    {
        // $this->selectedChat = Auth::user()->is_active_in_chat;
        $this->fetchChats();
    }

    // public function pushLastMessage()
    // {
    //     $this->fetchChats();
    // }

    public function unarchiveChatAndUpdate()
    {
        // $this->selectedChat = null;
        $this->fetchChats();
    }

    public function fetchChats()
    {
        $this->allChats = Auth::user()->chats()
            ->where('chat_user.is_active', true)
            // ->withPivot('is_archived')
            ->where('is_archived', true)
            ->with(['users', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function ($chat) {
                return optional($chat->messages->first())->created_at;
            });

        $this->chats = $this->allChats->values();
    }

    public function selectArchived($chatId)
    {
        $this->chat = Chat::find($chatId);
        $this->user = $this->chats->where('id', $chatId)->first()->users->where('id', '!=', Auth::id())->first();
        $this->selectedChat = $chatId;
        $this->dispatch('archivedSelected', $chatId);
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

    public function render()
    {
        return view('livewire.chats.show-archived');
    }
}
