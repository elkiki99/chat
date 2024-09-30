<?php

namespace App\Livewire\Chats;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateGroup extends Component
{
    public $search = '';
    public $contacts = [];
    public $selectedContacts = [];

    public function mount()
    {
        $this->loadContacts();
    }

    public function loadContacts()
    {
        $this->contacts = Auth::user()->contacts;
    }

    public function createGroup()
    {
        
    }
    
    public function updatedSearch($search)
    {
        if (empty($search)) {
            $this->contacts = Auth::user()->contacts;
        } else {
            $contactIds = Auth::user()->contacts()->pluck('contact_user_id')->toArray();
    
            $this->contacts = User::where('name', 'like', '%' . $search . '%')
                ->whereIn('id', $contactIds)
                ->take(5)
                ->get();
        }
    }
    
    public function render()
    {
        return view('livewire.chats.create-group', [
            'contacts' => $this->contacts,
        ]);
    }
}
