<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserStatus extends Component
{
    public $user;
    public $status;

    protected $listeners = [
        'userTyping' => 'setTyping',
        'userActive' => 'setActive',
        'userLastSeen' => 'setLastSeen',
    ];

    public function mount($user)
    {
        $this->user = $user;
        $this->status = $this->getUserStatus();
    }

    public function getUserStatus()
    {
        return Cache::get('user-status-' . Auth::id(), 'Offline');
    }

    public function setTyping()
    {
        // if ($this->user->id !== Auth::id()) {
            $this->status = $this->user->name . ' is typing...';
            Cache::put('user-status-' . $this->user->id, $this->status, 5);
        // }
    }

    public function setActive()
    {
        $this->status = 'Online';
        Cache::put('user-status-' . Auth::id(), $this->status, 500);
    }

    public function setLastSeen($time)
    {
        $this->status = 'Last seen ' . $time;
        Cache::put('user-status-' . Auth::id(), $this->status, 500);
    }
    
    public function render()
    {
        return view('livewire.users.user-status');
    }
}
