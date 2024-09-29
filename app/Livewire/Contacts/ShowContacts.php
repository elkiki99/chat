<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShowContacts extends Component
{
    public $contacts = [];

    protected $listeners = [
        'selectContacts' => 'loadContacts',
    ];

    public function mount()
    {
        $this->loadContacts();
    }

    public function loadContacts()
    {
        $this->contacts = Auth::user()->contacts;
    }

    public function render()
    {
        return view('livewire.contacts.show-contacts', [
            'contacts' => $this->contacts,
        ]);
    }
}
