<?php

namespace App\Livewire\Layouts;

use Livewire\Component;

class Sidebar extends Component
{
    public $activeComponent = 'chats';

    protected $listeners = ['groupSelected' => 'selectChats'];

    public function selectChats()
    {
        $this->dispatch('componentChanged', 'chats');
        $this->activeComponent = 'chats';
    }

    public function selectContacts()
    {
        $this->dispatch('componentChanged', 'contacts');
        $this->activeComponent = 'contacts';
    }

    public function render()
    {
        return view('livewire.layouts.sidebar');
    }
}
