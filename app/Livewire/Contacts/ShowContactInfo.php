<?php

namespace App\Livewire\Contacts;

use App\Models\Chat;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ShowContactInfo extends Component
{
    public $user;
    public $selectedChat = null;

    public function selectChat($chatId)
    {
        $this->dispatch('chatSelected', $chatId);
        $this->dispatch('groupSelected');
        
        $this->selectedChat = $chatId;
        Auth::user()->update(['is_active_in_chat' => $chatId]);
    }

    public function createChat($contactId)
    {
        $this->dispatch('groupSelected');

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

    public function removeContact($userId)
    {
        Auth::user()->contacts()->detach($userId);
        $this->dispatch('contactRemoved', $userId);
    }
    
    public function render()
    {
        return view('livewire.contacts.show-contact-info', [
            'user' => $this->user
        ]);
    }
}
