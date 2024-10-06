<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageRead;
use App\Events\MessageSent;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ShowChat extends Component
{
    use WithPagination;

    public ?Chat $chat = null;
    public $body = '';
    public $messages;
    public $messageAmount = 100;
    public $user;
    public $files = [];
    public $file;

    public function mount()
    {
        if (Auth::user()->is_active_in_chat) {
            $this->loadChat(Auth::user()->is_active_in_chat);
            $this->user = $this->chat->users->where('id', '!==', Auth::id())->first();
        } else {
            $this->chat = null;
        }
    }

    public function getListeners(): array
    {
        $listeners = [
            'chatSelected' => 'changeToSelectedChat',
            'archivedSelected' => 'changeToSelectedChat',
            'chatArchived' => 'setChatToNull',
        ];

        // This if this->chat line interferes with the updateChatInRealTime event when there's a new messgae being processed
        // if ($this->chat) {
            $listeners["echo-private:App.Models.Chat.{$this->chat->id},MessageSent"] = 'updateChatInRealTime';
            $listeners["echo-private:App.Models.Chat.{$this->chat->id},MessageRead"] = 'handleMessageRead';
        // }

        return $listeners;
    }

    public function sendFile()
    {
        foreach ($this->files as $file) {
            $tempPath = $file['path'];
            $destinationPath = storage_path('app/public/uploads');
            $newFileName = Str::random(20) . '.' . $file['extension'];

            File::ensureDirectoryExists($destinationPath);

            if (File::exists($tempPath)) {
                File::move($tempPath, $destinationPath . '/' . $newFileName);
            }
            $newMessage = $this->chat->messages()->create([
                'chat_id' => $this->chat->id,
                'user_id' => Auth::id(),
                'body' => $newFileName, 
                'is_file' => true,
            ]);
            // dd($newMessage);
        }
        $this->files = [];
        $this->updateChatInRealTime();
    }

    public function loadMoreMessages()
    {
        $this->messageAmount += 100;
    }

    public function setChatToNull()
    {
        $this->chat = null;
    }

    public function scrollDown()
    {
        $this->dispatch('scrollDown');
    }

    private function loadChat($chatId)
    {
        $this->chat = Chat::with('users', 'messages')->find($chatId);
        $this->markMessagesAsSeen($chatId);
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
            'is_file' => false,
        ]);

        $this->body = '';

        broadcast(new MessageSent($this->chat, $message));
        $this->checkForActiveUsersAndMarkSeen();
        $this->updateChatInRealTime();
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
                broadcast(new MessageRead($message, $user));
            });
        }
        $this->updateChatInRealTime();
    }

    private function checkForActiveUsersAndMarkSeen()
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

        $this->messages = $this->chat->messages()
            ->with('seenBy')
            ->latest()
            ->take($this->messageAmount)
            ->get()
            ->sortBy('created_at')
            ->values();

        $this->body = '';

        $this->scrollDown();
    }

    public function render()
    {
        return view('livewire.chats.show-chat', [
            'user' => $this->user,
        ]);
    }
}
