<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Sidebar extends Component
{
    public $activeComponent = 'chats';
    public $showSidebar;

    public function mount()
    {
        $this->showSidebar = !Cache::has('user-' . Auth::id() . '-active-chat');
    }

    protected $listeners = [
        'chatSelected' => 'selectChats',
        'chatUnarchived' => 'selectChats',
        'chatArchived' => 'setChatToNull',
        'hideSidebar' => 'handleHideSidebar',
        'showSidebar' => 'handleShowSidebar',
    ];

    public function setChatToNull()
    {
        $cacheKey = "user-" . Auth::id() . "-active-chat";
        Cache::forget($cacheKey);
    }

    public function handleShowSidebar()
    {
        $this->showSidebar = true;
    }

    public function handleHideSidebar()
    {
        $this->showSidebar = false;
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
