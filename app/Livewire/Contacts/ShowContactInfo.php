<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShowContactInfo extends Component
{
    public $user;
    public $selectedChat = null;

    public function selectChat($chatId)
    {
        $this->dispatch('close');       // Modal
        $this->dispatch('chatSelected', $chatId);
        $this->dispatch('groupSelected');
        
        $this->selectedChat = $chatId;
        Auth::user()->update(['is_active_in_chat' => $chatId]);
    }

    public function render()
    {
        return view('livewire.contacts.show-contact-info', [
            'user' => $this->user
        ]);
    }
}
