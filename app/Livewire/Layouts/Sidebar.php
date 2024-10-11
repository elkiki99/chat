<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public $activeComponent = 'chats';

    protected $listeners = [
        'chatSelected' => 'selectChats',
        'chatUnarchived' => 'selectChats',
    ];

    public function selectChats()
    {
        $this->dispatch('componentChanged', 'chats');
        $this->activeComponent = 'chats';
    }

    public function selectContacts()
    {
        Auth::user()->is_active_in_chat = null;
        Auth::user()->save();
        $this->dispatch('componentChanged', 'contacts');
        $this->activeComponent = 'contacts';
    }

    public function selectArchived()
    {
        Auth::user()->is_active_in_chat = null;
        Auth::user()->save();
        $this->dispatch('componentChanged', 'archived');
        $this->activeComponent = 'archived';
    }

    public function render()
    {
        return view('livewire.layouts.sidebar');
    }
}
