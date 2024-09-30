<?php

namespace App\Livewire\Contacts;

use App\Models\User;
use App\Models\Contact;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateContact extends Component
{
    public $search = '';
    public $contacts = [];

    public function createContact($userId)
    {
        $existingUser = User::find($userId);
        
        if ($existingUser) {
            $existingContact = Contact::where('user_id', Auth::id())
                ->where('contact_user_id', $userId)
                ->first();

            if (!$existingContact) {
                Contact::create([
                    'user_id' => Auth::id(),
                    'contact_user_id' => $userId,   
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->dispatch('contactCreated', $userId);
            }
        }
    }

    public function updatedSearch($search)
    {
        if (empty($search)) {
            $this->contacts = [];
        } else {
            $contactIds = Auth::user()->contacts()->pluck('contact_user_id')->toArray();
    
            $this->contacts = User::where('name', 'like', '%' . $search . '%')
                ->whereNotIn('id', $contactIds)
                ->take(5)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.contacts.create-contact', [
            'contacts' => $this->contacts,
        ]);
    }
}
