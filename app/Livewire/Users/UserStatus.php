<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;


class UserStatus extends Component
{
    public $isTyping = false;
    public $typingUser;

    protected $listeners = [
        'userTyping' => 'handleUserTyping',
        'userStoppedTyping' => 'handleUserStoppedTyping',
    ];

    public function handleUserTyping($typingUserId)
    {
        $this->typingUser = User::find($typingUserId);
    
        if ($this->typingUser) {
            $this->isTyping = true;
        }
    }
    
    public function handleUserStoppedTyping()
    {
        $this->isTyping = false;
        $this->typingUser = null;
    }
    
    public function render()
    {
        return view('livewire.users.user-status');
    }
}
