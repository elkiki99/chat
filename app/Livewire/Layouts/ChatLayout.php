<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatLayout extends Component
{
    public $activeComponent = 'chats'; 

    protected $listeners = [
        'componentChanged' => 'setActiveComponent',
    ];

    public function setActiveComponent($component)
    {
        $this->activeComponent = $component;
        // Auth::user()->update(['is_active_in_chat' => null]);
    }

    public function render()
    {
        return view('livewire.layouts.chat-layout');
    }
}