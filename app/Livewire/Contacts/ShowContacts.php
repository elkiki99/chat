<?php

namespace App\Livewire\Contacts;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ShowContacts extends Component
{
    public $search = '';
    public $contacts = [];
    public $allContacts = [];

    public function getListeners(): array
    {
        return [
            'contactCreated' => 'clearCacheAndLoadContacts',
            'contactRemoved' => 'clearCacheAndLoadContacts',
            'contactAdded' => 'clearCacheAndLoadContacts',
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
        $cacheKey = 'user_contacts_' . Auth::id();

        $this->allContacts = Cache::remember($cacheKey, now()->addMinutes(10), function () {
            return Auth::user()
                ->contacts()
                ->orderBy('name')
                ->where('contact_user_id', '!=', Auth::id())
                ->get();
        });

        $this->contacts = $this->allContacts->sortBy('name')->values();
    }

    public function clearCacheAndLoadContacts()
    {
        Cache::forget('user_contacts_' . Auth::id());
        $this->loadContacts();
    }

    public function updatedSearch($value)
    {
        if (empty($value)) {
            $this->contacts = $this->allContacts;
        } else {
            $this->contacts = $this->allContacts->filter(function ($contact) use ($value) {
                return stripos(strtolower($contact->name), $value) !== false;
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