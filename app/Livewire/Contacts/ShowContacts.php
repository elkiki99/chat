<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShowContacts extends Component
{
    public $search = '';
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

    public function updatedSearch($value)
    {
        $allContacts = Auth::user()->contacts;

        if (empty($value)) {
            $this->contacts = $allContacts;
        } else {
            $this->contacts = $allContacts->filter(function ($contact) use ($value) {
                return stripos($contact->name, $value) !== false;
            });
        }
    }

    public function render()
    {
        return view('livewire.contacts.show-contacts', [
            'contacts' => $this->contacts,
        ]);
    }
}
