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
    public $chat;
    public $body;
    public $message;
    public $messages;


    protected $listeners = [
        'chatSelected' => 'changeToSelectedChat',
        'userIsActiveInChat' => 'markMessagesAsSeen'
    ];

    public function mount()
    {
        $this->loadChat(Session::get('selected_chat'));
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::with('users', 'messages')->find($chatId);
        $this->updateChatInRealTime();
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
        MessageSent::dispatch($message);
    }

    #[On('echo:message-sent,MessageSent')]
    #[On('echo:message-read,MessageRead')]
    public function updateChatInRealTime()
    {
        $this->messages = $this->chat ? $this->chat->messages : collect();
    }

    public function render()
    {
        return view('livewire.chats.show-chat', [

        ]);
    }
}
