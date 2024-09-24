<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageRead;
use App\Events\MessageSent;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShowChat extends Component
{
    public Chat $chat;
    public $body;
    public $message;
    public $messages;

    protected $listeners = [
        'chatSelected' => 'changeToSelectedChat',
    ];

    public function mount()
    {
        $this->loadChat(Session::get('selected_chat'));
        $this->dispatch('scrollDown');
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::with('users', 'messages')->find($chatId);
        $this->updateChatInRealTime();
        $this->dispatch('scrollDown');
    }

    public function changeToSelectedChat($chatId)
    {
        $this->loadChat($chatId);
        $this->dispatch('scrollDown');
        $this->markMessagesAsSeen($chatId);
    }

    public function markMessagesAsSeen($chatId)
    {
        $user = Auth::user();
        $messages = Message::where('chat_id', $chatId)
            ->whereDoesntHave('seenBy', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();
    
        foreach ($messages as $message) {
            $message->seenBy()->syncWithoutDetaching([$user->id]);
            MessageRead::dispatch($message, $user);
        }
        $this->updateChatInRealTime();
    }

    public function sendMessage()
    {
        if (empty(trim($this->body))) {
            return;
        }

        $message = $this->chat->messages()->create([
            'chat_id' => $this->chat->id,
            'user_id' => Auth::id(),
            'body' => $this->body,
        ]);

        $this->body = '';
        $this->updateChatInRealTime();
        $this->dispatch('scrollDown');
        MessageSent::dispatch(
            // $message->chat, 
            $message
        );

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

    // #[On('echo-private:message-sent.' . $this->chat->id, MessageSent::class)]
    #[On('echo:message-sent,MessageSent')]
    #[On('echo:message-read,MessageRead')]
    public function updateChatInRealTime()
    {
        $this->messages = $this->chat ? $this->chat->messages : collect();
    }

    // #[On('echo:message-read,MessageRead')]
    public function render()
    {
        return view('livewire.chats.show-chat');
    }
}
