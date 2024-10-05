<?php

// namespace App\Livewire\Chats;

// use App\Models\Message;
// use Livewire\Component;
// use App\Events\MessageRead;
// use App\Events\MessageSent;
// use Illuminate\Support\Str;
// use Livewire\WithFileUploads;
// use Illuminate\Support\Facades\Auth;

// class SendFile extends Component
// {
//     use WithFileUploads;

//     public $files = [];
//     public $chat;
//     public $messages;

//     protected $rules = [
//         'files.*' => 'file|max:2048|mimes:png,jpeg,jpg,webp,pdf,docx,xlsx',
//     ];

//     public function updatedFiles()
//     {
//         $this->validate();
//     }

//     public function sendFile()
//     {
//         foreach ($this->files as $file) {
//             $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
//             $file->storeAs('uploads', $filename, 'public'); 

//             $message = $this->chat->messages()->create([
//                 'chat_id' => $this->chat->id,
//                 'user_id' => Auth::id(),
//                 'body' => $filename,
//             ]);

//             broadcast(new MessageSent($this->chat, $message));
//             $this->checkForActiveUsersAndMarkSeen();
//             $this->updateChatInRealTime();
//         }


//     }

//     private function checkForActiveUsersAndMarkSeen()
//     {
//         if (!$this->chat) {
//             return;
//         }

//         $activeUsers = $this->chat->users()
//             ->where('users.id', '!=', Auth::id())
//             ->get();

//         foreach ($activeUsers as $user) {
//             if ($user->is_active_in_chat === $this->chat->id) {
//                 $this->markMessagesAsSeen($this->chat->id);
//                 break;
//             }
//         }
//     }

//     public function markMessagesAsSeen($chatId)
//     {
//         $user = Auth::user();
//         $messages = Message::where('chat_id', $chatId)
//             ->whereDoesntHave('seenBy', function ($query) use ($user) {
//                 $query->where('user_id', $user->id);
//             })
//             ->get();

//         if ($messages->isNotEmpty()) {
//             $messageIds = $messages->pluck('id')->toArray();
//             Message::whereIn('id', $messageIds)->each(function ($message) use ($user) {
//                 $message->seenBy()->syncWithoutDetaching([$user->id]);
//                 broadcast(new MessageRead($message, $user));
//             });
//         }
//         $this->updateChatInRealTime();
//     }

//     public function updateChatInRealTime()
//     {
//         if (!$this->chat) {
//             return;
//         }
        
//         $this->messages = $this->chat->messages()
//             ->with('seenBy')
//             ->latest()
//             ->take($this->messageAmount)
//             ->get()
//             ->sortBy('created_at')
//             ->values();
            
//         $this->scrollDown();
//     }

//     public function scrollDown()
//     {
//         $this->dispatch('scrollDown');
//     }

//     public function render()
//     {
//         return view('livewire.chats.send-file');
//     }
// }
