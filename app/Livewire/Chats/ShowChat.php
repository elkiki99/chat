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
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ShowChat extends Component
{
    use WithPagination;

    public ?Chat $chat = null;
    public $body = '';
    public $messages = [];
    public $messageAmount = 100;
    public $user;
    public $files = [];
    public $file;
    public $readMessages = [];

    public function getListeners(): array
    {
        $listeners = [
            'chatSelected' => 'changeToSelectedChat',
            'chatArchived' => 'setChatToNull',
            'userLeftGroup' => 'setChatToNull',
            'chatDeleted' => 'setChatToNull',
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

    public function sendFile()
    {
        foreach ($this->files as $file) {
            $tempPath = $file['path'];
            $extension = $file['extension'];

            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
            $newFileName = !in_array(strtolower($extension), $imageExtensions)
                ? $file['name']
                : Str::random(20) . '.' . $extension;

            $newFileName = Storage::disk('s3')->putFileAs('uploads', new HttpFile($tempPath), $newFileName);

            $message = $this->chat->messages()->create([
                'chat_id' => $this->chat->id,
                'user_id' => Auth::id(),
                'body' => $newFileName,
                'is_file' => true,
            ]);
        }
        $this->chat->users()->updateExistingPivot(Auth::id(), ['is_active' => false]);
        $this->chat->users()->updateExistingPivot(Auth::id(), ['is_active' => true]);
        $this->files = [];

        broadcast(new MessageSent($this->chat, $message));
        $this->checkForActiveUsersAndMarkSeen();
        $this->updateChatInRealTime();
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
        // $this->messages;
        $this->markMessagesAsSeen($chatId);
        $this->scrollDown();
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
        $this->reset('body');

        Cache::forget('chat-' . $this->chat->id . '-messages');

        if ($this->chat->users()->updateExistingPivot(Auth::id(), ['is_active' => false])) {
            $this->chat->users()->updateExistingPivot(Auth::id(), ['is_active' => true]);
        }

        if ($this->chat->users()->updateExistingPivot(Auth::id(), ['is_archived' => true])) {
            $this->chat->users()->updateExistingPivot(Auth::id(), ['is_archived' => false]);
            $this->dispatch('chatUnarchived', $this->chat->id);
        }

        $recipients = $this->chat->users->where('id', '!=', Auth::id());
        foreach ($recipients as $user) {
            $isArchived = $user->pivot->is_archived ?? false;
            if ($isArchived) {
                $this->chat->users()->updateExistingPivot($user->id, ['is_archived' => false]);
                $this->dispatch('chatUnarchived', $this->chat->id);
            }
        }

        broadcast(new MessageSent($this->chat, $message));
        $this->checkForActiveUsersAndMarkSeen();
        $this->updateChatInRealTime();
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
            // Message::whereIn('id', $messageIds)->each(function ($message) use ($user) {
            //     $message->seenBy()->syncWithoutDetaching([$user->id]);
            //     broadcast(new MessageRead($message, $user));
            // });
            Message::whereIn('id', $messageIds)->each(function ($message) use ($user) {
                $message->seenBy()->syncWithoutDetaching([$user->id]);
            });

            // broadcast(new MessageRead($chatId, $messageIds, $user));
            broadcast(new MessageRead($messageIds, $user, $chatId));
            
            Cache::put($cacheKey, array_merge($readMessages, $messageIds), 600);
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
        $this->body = '';
    }

    public function render()
    {
        return view('livewire.chats.show-chat', [
            'user' => $this->user,
        ]);
    }
}
