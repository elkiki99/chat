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
        'chatArchived' => 'setChatToNull',
    ];

    public function setChatToNull()
    {
        Auth::user()->update(['is_active_in_chat' => null]);
    }

    public function selectChats()
    {
        $this->dispatch('componentChanged', 'chats');
        $this->activeComponent = 'chats';
    }

    public function selectContacts()
    {
        $this->dispatch('componentChanged', 'contacts');
        $this->dispatch('chatArchived');
        $this->activeComponent = 'contacts';
    }   

    public function selectArchived()
    {
        $this->dispatch('componentChanged', 'archived');
        $this->dispatch('chatArchived');
        $this->activeComponent = 'archived';
    }

    public function render()
    {
        return view('livewire.layouts.sidebar');
    }
}
