<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ShowChats extends Component
{
    public $search = '';
    public $chat;
    public $selectedChat = null;
    public $chats = [];
    public $allChats = [];
    public $user;
    public $showAside = true;

    public function getListeners(): array
    {
        $listeners = [
            'chatUnarchived' => 'invalidateCacheAndFetchChats',
            'chatCreated' => 'invalidateCacheAndFetchChats',
            'backToChats' => 'handleBackToChats',
            'chatArchived' => 'userRemoveActionOnChat',
            'userLeftGroup' => 'userRemoveActionOnChat',
            'chatDeleted' => 'userRemoveActionOnChat',
            'contactRemoved' => 'userRemoveActionOnChat',
        ];

        foreach ($this->allChats as $chat) {
            $listeners['echo-private:App.Models.Chat.' . $chat->id . ',MessageSent'] = 'invalidateCacheAndFetchChats';
            $listeners['echo-private:App.Models.Chat.' . $chat->id . ',MessageRead'] = 'updateChatInRealTime';
        }

        return $listeners;
    }

    public function mount(): void
    {
        $this->showAside = !Cache::has('user-' . Auth::id() . '-active-chat');
        $this->fetchChats();
    }

    public function userRemoveActionOnChat()
    {
        $this->showAside = true;
        $this->invalidateCacheAndFetchChats();
    }

    public function handleBackToChats()
    {
        $this->showAside = true;
        $this->dispatch('showSidebar');
    }

    public function invalidateCacheAndFetchChats()
    {
        Cache::forget('user-' . Auth::id() . '-chats');
        $this->fetchChats();
    }

    public function fetchChats()
    {
        $cacheKey = 'user-' . Auth::id() . '-chats';

        $this->allChats = Cache::remember($cacheKey, 600, function () {
            return Auth::user()->chats()
                ->where('chat_user.is_active', true)
                ->where('is_archived', false)
                ->with(['users', 'messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->get()
                ->sortByDesc(function ($chat) {
                    return optional($chat->messages->first())->created_at;
                });
        });

        $this->chats = $this->allChats->values();
    }

    public function selectChat($chatId)
    {
        $this->showAside = false;
        $this->chat = $this->allChats->where('id', $chatId)->first();

        $this->user = $this->chat->users->where('id', '!=', Auth::id())->first();
        $this->selectedChat = $chatId;

        $this->dispatch('chatSelected', $chatId);
        $this->dispatch('refreshUserStatus', $this->user->id, $this->chat->id);
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
        return view('livewire.chats.show-chats');
    }
}