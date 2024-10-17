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

    public function getListeners(): array
    {
        $listeners = [
            'chatUnarchived' => 'invalidateCacheAndFetchChats',
            'chatCreated' => 'invalidateCacheAndFetchChats',
            'chatArchived' => 'userRemoveActionOnChat',
            'userLeftGroup' => 'userRemoveActionOnChat',
            'chatDeleted' => 'userRemoveActionOnChat',
            'contactRemoved' => 'userRemoveActionOnChat',
            'contactAdded' => 'userAddActionOnChat',
        ];

        foreach ($this->allChats as $chat) {
            $listeners['echo-private:App.Models.Chat.' . $chat->id . ',MessageSent'] = 'invalidateCacheAndFetchChats';
            $listeners['echo-private:App.Models.Chat.' . $chat->id . ',MessageRead'] = 'updateChatInRealTime';
        }
        return $listeners;
    }

    public function mount(): void
    {
        $this->selectedChat = Auth::user()->is_active_in_chat;
        $this->fetchChats();
    }

    public function userRemoveActionOnChat()
    {
        $this->selectedChat = null;
        $this->invalidateCacheAndFetchChats();
    }

    public function userAddActionOnChat()
    {
        $this->invalidateCacheAndFetchChats();
    }

    public function invalidateCacheAndFetchChats()
    {
        Cache::forget('user-' . Auth::id() . '-chats');
        $this->fetchChats();
    }

    public function fetchChats()
    {
        $cacheKey = 'user-' . Auth::id() . '-chats';

        if (Cache::has($cacheKey)) {
            $this->allChats = Cache::get($cacheKey);
        } else {
            $this->allChats = Auth::user()->chats()
                ->where('chat_user.is_active', true)
                ->where('is_archived', false)
                ->with(['users', 'messages' => function ($query) {
                    $query->latest()->limit(1);
                }])
                ->get()
                ->sortByDesc(function ($chat) {
                    return optional($chat->messages->first())->created_at;
                });

            Cache::put($cacheKey, $this->allChats, 600);
        }

        $this->chats = $this->allChats->values();
    }

    public function selectChat($chatId)
    {
        $this->chat = Chat::find($chatId);
        $this->user = $this->chats->where('id', $chatId)->first()->users->where('id', '!=', Auth::id())->first();
        $this->selectedChat = $chatId;
        $this->dispatch('chatSelected', $chatId);
        Auth::user()->update(['is_active_in_chat' => $chatId]);
        $this->dispatch('scrollDown');
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
