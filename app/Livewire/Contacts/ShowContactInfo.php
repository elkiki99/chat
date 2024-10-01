<?php

namespace App\Livewire\Contacts;

use Livewire\Component;

class ShowContactInfo extends Component
{
    public $user;

    public function render()
    {
        return view('livewire.contacts.show-contact-info', [
            'user' => $this->user
        ]);
    }
}
