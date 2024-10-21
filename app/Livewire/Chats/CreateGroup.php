<?php

namespace App\Livewire\Chats;

use App\Models\Chat;
use App\Models\User;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CreateGroup extends Component
{
    use WithFileUploads;

    public $search = '';
    public $chat_image;
    public $name = '';
    public $contacts = [];
    public $selectedContacts;

    public function mount()
    {
        $this->loadContacts();
        $this->selectedContacts = collect();
    }

    public function loadContacts()
    {
        $this->contacts = Auth::user()->contacts;
    }

    public function toggleContactSelect($contactId)
    {
        if ($this->selectedContacts->contains($contactId)) {
            $this->selectedContacts = $this->selectedContacts->reject(function ($id) use ($contactId) {
                return $id === $contactId;
            });
        } else {
            $this->selectedContacts->push($contactId);
        }
    }

    public function createGroup()
    {
        $userId = Auth::id();

        $validated = $this->validate([
            'name' => 'required|min:3|max:45',
            'chat_image' => 'nullable|image|max:2048|mimes:png,jpeg,jpg,webp',
        ]);

        if ($this->chat_image) {
            $file = $this->chat_image;
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();

            $filePath = Storage::disk('s3')->putFileAs('chat-images', $file, $filename, 'public');
        }

        if ($this->selectedContacts->count() > 0) {
            $chat = Chat::create([
                'name' => $validated['name'],
                'is_group' => true,
                'chat_image' => $filePath ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $chat->users()->attach(Auth::id());
            $chat->users()->attach($this->selectedContacts->all());

            $cacheKey = "user-{$userId}-active-chat";
            Cache::put($cacheKey, $chat->id, 600);

            $this->dispatch('close');
            $this->dispatch('chatCreated', $chat->id);
            $this->dispatch('chatSelected', $chat->id);

            $this->selectedContacts = collect();
        }
    }

    public function updatedSearch($search)
    {
        if (empty($search)) {
            $this->contacts = Auth::user()->contacts;
        } else {
            $contactIds = Auth::user()->contacts()->pluck('contact_user_id')->toArray();

            $this->contacts = User::where('name', 'like', '%' . $search . '%')
                ->whereIn('id', $contactIds)
                ->take(10)
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
