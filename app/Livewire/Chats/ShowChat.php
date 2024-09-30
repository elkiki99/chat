<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageRead;
use App\Events\MessageSent;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ShowChat extends Component
{
    use WithPagination;

    public Chat $chat;
    public $body = '';
    public $messages;
    public $messageAmount = 100;

    protected $listeners = [
        'chatSelected' => 'changeToSelectedChat',
    ];

    public function mount()
    {
        if (Auth::user()->is_active_in_chat) {
            $this->loadChat(Auth::user()->is_active_in_chat);
        }
    }

    public function loadMoreMessages()
    {
        $this->messageAmount += 100;
    }

    public function scrollDown()
    {
        $this->dispatch('scrollDown');
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::with('users', 'messages')->find($chatId);
        $this->updateChatInRealTime();
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
    }

    public function sendMessage()
    {
        $trimmedBody = trim($this->body);
        if (empty($trimmedBody)) {
            return;
        }

        $message = $this->chat->messages()->create([
            'chat_id' => $this->chat->id,
            'user_id' => Auth::id(),
            'body' => $trimmedBody,
        ]);

        MessageSent::dispatch($message);
        $this->checkForActiveUsersAndMarkSeen();
    }

    public function markMessagesAsSeen($chatId)
    {
        $user = Auth::user();
        $messages = Message::where('chat_id', $chatId)
            ->whereDoesntHave('seenBy', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        if ($messages->isNotEmpty()) {
            $messageIds = $messages->pluck('id')->toArray();
            Message::whereIn('id', $messageIds)->each(function ($message) use ($user) {
                $message->seenBy()->syncWithoutDetaching([$user->id]);
                MessageRead::dispatch($message, $user);
            });
        }
        $this->updateChatInRealTime();
    }

    private function checkForActiveUsersAndMarkSeen()
    {
        $activeUsers = $this->chat->users()
            ->where('users.id', '!=', Auth::id())
            ->get();

        foreach ($activeUsers as $user) {
            if ($user->is_active_in_chat === $this->chat->id) {
                $this->markMessagesAsSeen($this->chat->id);
                break;
            }
        }
    }

    #[On('echo:message-sent,MessageSent')]
    #[On('echo:message-read,MessageRead')]
    public function updateChatInRealTime()
    {
        $this->messages = $this->chat->messages()
            ->orderBy('created_at', 'desc')
            ->take($this->messageAmount)
            ->get()
            ->sortBy('created_at')
            ->values();

        $this->scrollDown();
    }

    public function render()
    {
        if (Auth::user()->is_active_in_chat) {
            $this->markMessagesAsSeen($this->chat->id);
        }
        $this->body = '';

        return view('livewire.chats.show-chat', [
            'messages' => $this->messages,
        ]);
    }
}
