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
        return [
            'userTyping' => 'handleUserTyping',
            'userStoppedTyping' => 'handleUserStoppedTyping',
            "echo-private:App.Models.Chat.{$this->chatId},UserTyping" => '',
            "echo-private:App.Models.Chat.{$this->chatId},UserStoppedTyping" => '',
        ];
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
        broadcast(new UserStoppedTyping($this->chatId, Auth::id()))->toOthers();
    }

    public function render()
    {
        $currentUserId = Auth::id();

        if ($this->typingUser && $this->typingUser->id === $currentUserId) {
            $this->isTyping = false;
        } else {
            $this->isTyping = false;
            
            foreach (Auth::user()->contacts as $user) {
                if ($user->id !== $currentUserId && Cache::has("chat_{$this->chatId}_user_typing_{$user->id}")) {
                    $this->isTyping = true;
                    $this->typingUser = $user;
                }
            }
        }
        return view('livewire.users.user-status');
    }
}
