<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use App\Events\UserTyping;
use App\Events\UserStoppedTyping;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class UserStatus extends Component
{
    public $isTyping = false;
    public $typingUser;
    public $chatId;

    public function getListeners(): array
    {
        $listeners = [
            'userTyping' => 'handleUserTyping',
            'userStoppedTyping' => 'handleUserStoppedTyping',
        ];
    
        if ($this->chatId) {
            $listeners["echo-private:App.Models.Chat.{$this->chatId},UserTyping"] = 'handleUserTypingInRealTime';
            $listeners["echo-private:App.Models.Chat.{$this->chatId},UserStoppedTyping"] = 'handleUserStoppedTypingInRealTime';
        }
    
        return $listeners;
    }

    public function mount($chatId)
    {
        $this->chatId = $chatId;
    }

    public function handleUserTyping($typingUserId)
    {
        $this->typingUser = User::find($typingUserId);

        if ($this->typingUser) {
            Cache::put("chat_{$this->chatId}_user_typing_{$typingUserId}", true, 5);
            $this->isTyping = true;
            $this->dispatch('userTypingStatusUpdated', $this->chatId, $this->typingUser->id);
            broadcast(new UserTyping($this->chatId, Auth::id()))->toOthers();
        }
    }

    public function handleUserStoppedTyping()
    {
        if ($this->typingUser) {
            Cache::forget("chat_{$this->chatId}_user_typing_{$this->typingUser->id}");
        }
        $this->isTyping = false;
        $this->typingUser = null;
        $this->dispatch('userTypingStatusUpdated', $this->chatId, null);
        broadcast(new UserStoppedTyping($this->chatId, Auth::id()))->toOthers();
    }

    public function render()
    {
        $currentUserId = Auth::id();

        if ($this->typingUser && $this->typingUser->id === $currentUserId) {
            $this->isTyping = false;
        }

        foreach (User::all() as $user) {
            if ($user->id !== $currentUserId && Cache::has("chat_{$this->chatId}_user_typing_{$user->id}")) {
                $this->isTyping = true;
                $this->typingUser = $user;
                break;
            }
        }

        return view('livewire.users.user-status');
    }
}
