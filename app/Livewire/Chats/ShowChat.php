<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageRead;
use App\Events\MessageSent;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ShowChat extends Component
{
    public Chat $chat;
    public $body = '';
    public $messages;

    protected $listeners = [
        'chatSelected' => 'changeToSelectedChat',
    ];

    public function mount()
    {
        $this->loadChat(Auth::user()->is_active_in_chat);
    }

    private function loadChat($chatId)
    {
        if ($chatId !== null) {
            $this->chat = Chat::with('users', 'messages')->find($chatId);
            $this->updateChatInRealTime();
            $this->dispatch('scrollDown');
        }
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
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

        $this->body = ''; 
        
        $this->updateChatInRealTime();
        
        $this->dispatch('scrollDown');

        MessageSent::dispatch($message);
        $this->checkForActiveUsersAndMarkSeen();
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
        $this->messages = $this->chat->messages ?? collect();
    }

    public function render()
    {
        if ($this->chat && $this->chat->id) {
            $this->markMessagesAsSeen($this->chat->id);
        }

        $this->dispatch('scrollDown');
        $this->body = '';

        return view('livewire.chats.show-chat', [
            'messages' => $this->messages,
        ]);
    }
}