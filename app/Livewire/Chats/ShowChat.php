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
        if (Auth::user()->is_active_in_chat) {
            $this->loadChat(Auth::user()->is_active_in_chat);
        } else {
            $this->chat = null;
        }
    }

    public function loadMoreMessages()
    {
        $this->messageAmount += 100;
    }

    public function setChatToNull()
    {
        $this->chat = null;
        $this->updateChatInRealTime();
    }

    public function scrollDown()
    {
        $this->dispatch('scrollDown');
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::with('users', 'messages')->find($chatId);
        $this->markMessagesAsSeen($chatId);
        $this->scrollDown();
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
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
            if ($user->is_active_in_chat === $this->chat->id) {
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
                ->with('seenBy')
                ->latest()
                ->take($this->messageAmount)
                ->get()
                ->sortBy('created_at')
                ->values();

            Cache::put($cacheKey, $this->messages, 600);
        }

        $this->scrollDown();
    }

    public function render()
    {
        return view('livewire.chats.show-chat', [
            'user' => $this->user,
        ]);
    }
}
