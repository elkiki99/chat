<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageRead;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ShowChat extends Component
{
    use WithPagination;

    public ?Chat $chat = null;
    public $messages = [];
    public $messageAmount = 100;
    public $user;
    public $readMessages = [];

    public function getListeners(): array
    {
        $listeners = [
            'chatSelected' => 'changeToSelectedChat',
            'chatArchived' => 'setChatToNull',
            'backToChats' => 'setChatToNull',
            'userLeftGroup' => 'setChatToNull',
            'chatDeleted' => 'setChatToNull',
            'userTyping' => 'handleUserTyping',
            'searchActiveUsers' => 'checkForActiveUsersAndMarkSeen',
            'updateChatInRealTime' => 'updateChatInRealTime',
        ];

        if ($this->chat) {
            $listeners["echo-private:App.Models.Chat.{$this->chat->id},MessageSent"] = 'updateChatInRealTime';
            $listeners["echo-private:App.Models.Chat.{$this->chat->id},MessageRead"] = 'handleMessageRead';
        }
        return $listeners;
    }

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadChat();
    }

    private function loadChat($chatId = null)
    {
        if (!$chatId) {
            $cacheKey = "user-{$this->user->id}-active-chat";
            $chatId = Cache::get($cacheKey);
        }
    
        if ($chatId) {
            $this->chat = Chat::with(['users:id,name', 'messages' => function($query) {
                $query->select('id', 'chat_id', 'user_id', 'body', 'created_at')
                      ->latest()
                      ->take($this->messageAmount);
            }])->find($chatId);
    
            if ($this->chat) {
                $this->messages = Cache::get("chat-{$chatId}-messages", []);
                $this->markMessagesAsSeen($chatId);
                $this->updateChatInRealTime();
            } else {
                $this->chat = null;
            }
        }
    }

    public function loadMoreMessages()
    {
        $this->messageAmount += 100;
    }

    public function setChatToNull()
    {
        if ($this->chat) {
            $cacheKey = "user-{$this->user->id}-active-chat";
            Cache::forget($cacheKey);
            $this->chat = null;
        }
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
        $cacheKey = "user-{$this->user->id}-active-chat";
        Cache::put($cacheKey, $chatId, 600);
        $this->dispatch('hideSidebar');
    }

    public function markMessagesAsSeen($chatId)
    {
        $user = Auth::user();
        $cacheKey = "chat_{$chatId}_read_messages_{$user->id}";

        $readMessages = Cache::get($cacheKey, []);

        $messages = Message::where('chat_id', $chatId)
            ->whereDoesntHave('seenBy', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereNotIn('id', $readMessages)
            ->pluck('id');

        if ($messages->isNotEmpty()) {
            $messageIds = $messages->toArray();
            Message::whereIn('id', $messageIds)->each(function ($message) use ($user) {
                $message->seenBy()->syncWithoutDetaching([$user->id]);
            });

            broadcast(new MessageRead($messageIds, $user, $chatId));
            Cache::put($cacheKey, array_merge($readMessages, $messageIds), 600);
        }
        $this->updateChatInRealTime();
    }

    public function checkForActiveUsersAndMarkSeen()
    {
        if (!$this->chat) {
            return;
        }

        $activeUsers = $this->chat->users()
            ->where('users.id', '!=', Auth::id())
            ->get();

        foreach ($activeUsers as $user) {
            $cacheKey = "chat-{$this->chat->id}-user-{$user->id}-active";
            if (Cache::has($cacheKey)) {
                $this->markMessagesAsSeen($this->chat->id);
                break;
            }
        }

        $this->updateChatInRealTime();
    }

    public function handleMessageRead()
    {
        $this->markMessagesAsSeen($this->chat->id);
    }

    public function updateChatInRealTime()
    {
        if (!$this->chat) {
            return;
        }
    
        $cacheKey = "chat-{$this->chat->id}-messages";
    
        if (Cache::has($cacheKey)) {
            $this->messages = Cache::get($cacheKey);
        } else {
            $this->messages = $this->chat->messages()
                ->select('id', 'chat_id', 'user_id', 'body', 'created_at')
                ->with('seenBy:id,name')
                ->latest()
                ->take($this->messageAmount)
                ->get()
                ->sortBy('created_at')
                ->values();
    
            Cache::put($cacheKey, $this->messages, 600);
        }
        
        // Only dispatch scroll event when necessary
        $this->dispatch('scrollDown');
    }

    public function render()
    {
        $this->updateChatInRealTime();

        return view('livewire.chats.show-chat', [
            'user' => $this->user,
        ]);
    }
}
