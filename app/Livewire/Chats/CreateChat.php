<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateChat extends Component
{
    public $contacts = [];

    public function mount()
    {
        $this->loadContacts();
    }

    public function createChat($contactId)
    {
        $userId = Auth::id();

        $chatExists = Chat::where('is_group', false)
        ->whereHas('users', function ($query) use ($userId, $contactId) {
            $query->whereIn('users.id', [$userId, $contactId]);
        })
        ->withCount(['users' => function ($query) use ($userId, $contactId) {
            $query->whereIn('users.id', [$userId, $contactId]);
        }])
        ->where('users_count', 2)
        ->first();

        if (!$chatExists) {
            $chat = Chat::create([
                'name' => null,
                'is_group' => false,
                'chat_image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $chat->users()->attach([Auth::id(), $contactId]);
            
            Auth::user()->update(['is_active_in_chat' => $chat->id]);
            $this->dispatch('chatCreated', $chat->id);
            $this->dispatch('chatSelected', $chat->id);
        } else {
            Auth::user()->update(['is_active_in_chat' => $chatExists->id]);
            $this->dispatch('chatSelected', $chatExists->id);
        }
    }

    public function loadContacts()
    {
        $this->contacts = Auth::user()->contacts;
    }

    public function render()
    {
        return view('livewire.chats.create-chat');
    }
}
