<?php

namespace App\Livewire\Layouts;

use Livewire\Component;

class Sidebar extends Component
{
    public $activeComponent = 'chats';

    public function selectChats()
    {
        $this->activeComponent = 'chats';
        $this->dispatch('componentChanged', 'chats');
    }

    public function selectContacts()
    {
        $this->activeComponent = 'contacts';
        $this->dispatch('componentChanged', 'contacts');
    }

    public function render()
    {
        return view('livewire.layouts.sidebar');
    }
}
