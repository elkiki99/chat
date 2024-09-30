<?php

namespace App\Livewire\Contacts;

use App\Models\User;
use App\Models\Contact;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateContact extends Component
{
    public $search = '';
    public $users = [];

    public function createContact($userId)
    {
        $existingUser = User::find($userId);
    
        if ($existingUser) {
            Contact::create([
                'user_id' => Auth::id(),
                'contact_user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            $this->dispatch('contactCreated', $userId);
        }
    }

    public function updatedSearch($value)
    {
        if (empty($value)) {
            $this->users = [];
        } else {
            $this->users = User::where('name', 'like', '%' . $value . '%')
                ->take(5)
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.contacts.create-contact', [
            'users' => $this->users,
        ]);
    }
}
