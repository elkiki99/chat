<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShowContacts extends Component
{
    public $search = '';
    public $contacts = [];
    public $allContacts = [];

    public function getListeners(): array
    {
        return [
            'contactCreated' => 'loadContacts',
            'contactRemoved' => 'loadContacts',
        ];
    }

    public function mount()
    {
        $this->loadContacts();
    }

    public function contactSelected($userId)
    {
        $this->dispatch('contactSelected', $userId);
    }

    public function loadContacts()
    {
        $this->allContacts = Auth::user()->contacts()->orderBy('name')->get();
        $this->contacts = $this->allContacts->sortBy('name')->values();
    }

    public function updatedSearch($value)
    {
        if (empty($value)) {
            $this->contacts = $this->allContacts;
        } else {
            $this->contacts = $this->allContacts->filter(function ($contact) use ($value) {
                return stripos($contact->name, $value);
            });
        }
    }

    public function render()
    {
        return view('livewire.contacts.show-contacts');
    }
}
