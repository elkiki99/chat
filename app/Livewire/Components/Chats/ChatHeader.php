<?php

namespace App\Livewire\Components\Chats;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ChatHeader extends Component
{
    public $chat;
    public $user;
    public $chats;
    public $selectedChat;
    public $contacts;

    protected $listeners = [
        'chatSelected' => 'changeToSelectedChat',
        'archivedSelected' => 'changeToSelectedChat',
        'contactRemoved' => 'updateUserContacts',
    ];

    public function mount(Chat $chat)
    {
        $this->loadChat($chat->id);
    }

    public function updateUserContacts()
    {
        $this->contacts = Auth::user()->contacts()->get();
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
    }

    public function backToChats()
    {
        $this->dispatch('backToChats');
    }

    public function backToArchived()
    {
        $this->dispatch('goToArchived');
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::find($chatId);
        if (!$this->chat) {
            return;
        }
        $this->user = $this->chat->users->where('id', '!==', Auth::id())->first();
    }

    public function leaveGroup()
    {
        $user = Auth::user();
        $this->chat->users()->detach($user->id);;
        $this->dispatch('userLeftGroup');
    }

    public function selectChat($chatId)
    {
        $this->dispatch('chatSelected', $chatId);
        $this->selectedChat = $chatId;
        $cacheKey = "user-{$this->user->id}-active-chat";
        Cache::put($cacheKey, $chatId, 600);    
    }

    public function createChat($contactId)
    {
        $userId = Auth::id();

        $chatExists = Chat::where('is_group', false)
            ->whereHas('users', function ($query) use ($userId, $contactId) {
                $query->whereIn('users.id', [$userId, $contactId]);
            })
            ->withCount(['users' => function ($query) use ($userId, $contactId) {
                $query->whereIn('users.id', [$userId, $contactId]);
            }])
            ->where('users_count', 2)
            ->first();

        if (!$chatExists) {
            $chat = Chat::create([
                'name' => null,
                'is_group' => false,
                'chat_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $chat->users()->attach([Auth::id(), $contactId]);

            $cacheKey = "user-{$this->user->id}-active-chat";
            Cache::put($cacheKey, $chat->id, 600);            
            $this->dispatch('chatCreated', $chat->id);
            $this->dispatch('chatSelected', $chat->id);
        } else {
            $cacheKey = "user-{$this->user->id}-active-chat";
            Cache::put($cacheKey, $chatExists->id, 600);
            $this->dispatch('chatSelected', $chatExists->id);
        }
    }

    public function archiveChat($chatId)
    {
        $chat = Chat::find($chatId);
        $chat->users()->updateExistingPivot(Auth::id(), ['is_archived' => true]);
        $this->dispatch('chatArchived', $chatId);
    }

    public function deleteChat($chatId)
    {
        $chat = Chat::find($chatId);
        $chat->users()->updateExistingPivot(Auth::id(), ['is_active' => false]);
        $this->dispatch('chatDeleted', $chatId);    
    }

    public function unarchiveChat($chatId)
    {
        $chat = Chat::find($chatId);
        $chat->users()->updateExistingPivot(Auth::id(), ['is_archived' => false]);
        $this->dispatch('chatUnarchived', $chatId);
    }

    public function removeContact($userId)
    {
        Auth::user()->contacts()->detach($userId);
        $this->dispatch('contactRemoved');
    }

    public function addContact($userId)
    {
        Auth::user()->contacts()->attach($userId);
        $this->dispatch('contactAdded', $userId);
    }

    public function render()
    {
        return view('livewire.components.chats.chat-header');
    }
}
