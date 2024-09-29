<?php

namespace App\Livewire\Layouts;

use Livewire\Component;

class ChatLayout extends Component
{
    public $activeComponent = 'chats';

    protected $listeners = [
        'componentChanged' => 'setActiveComponent',
    ];

    public function setActiveComponent($component)
    {
        $this->activeComponent = $component;
    }

    public function render()
    {
        return view('livewire.layouts.chat-layout');
    }
}
