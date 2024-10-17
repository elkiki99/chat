<?php

namespace App\Livewire\Messages;

use Livewire\Component;
use App\Events\MessageSent;
use Illuminate\Http\File as HttpFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SendMessage extends Component
{
    public $files = [];
    public $file;
    public $body = '';
    public $chat;

    public function sendMessage()
    {
        $trimmedBody = trim($this->body);
        if (empty($trimmedBody)) {
            return;
        }

        $message = $this->chat->messages()->create([
            'chat_id' => $this->chat->id,
            'user_id' => Auth::id(),
            'body' => $trimmedBody,
            'is_file' => false,
        ]);
        $this->body = '';

        Cache::forget('chat-' . $this->chat->id . '-messages');

        if ($this->chat->users()->updateExistingPivot(Auth::id(), ['is_active' => false])) {
            $this->chat->users()->updateExistingPivot(Auth::id(), ['is_active' => true]);
        }

        if ($this->chat->users()->updateExistingPivot(Auth::id(), ['is_archived' => true])) {
            $this->chat->users()->updateExistingPivot(Auth::id(), ['is_archived' => false]);
            $this->dispatch('chatUnarchived', $this->chat->id);
        }

        // $recipients = $this->chat->users->where('id', '!=', Auth::id());
        // foreach ($recipients as $user) {
        //     $this->chat->users()->updateExistingPivot($user->id, ['is_archived' => false, 'is_active' => true]);
        //     $this->dispatch('chatUnarchived', $this->chat->id);
        // }
        broadcast(new MessageSent($this->chat, $message));
        $this->dispatch('searchActiveUsers');
    }

    public function sendFile()
    {
        foreach ($this->files as $file) {
            $tempPath = $file['path'];
            $extension = $file['extension'];

            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
            $newFileName = !in_array(strtolower($extension), $imageExtensions)
                ? $file['name']
                : Str::random(20) . '.' . $extension;

            $newFileName = Storage::disk('s3')->putFileAs('uploads', new HttpFile($tempPath), $newFileName);

            $message = $this->chat->messages()->create([
                'chat_id' => $this->chat->id,
                'user_id' => Auth::id(),
                'body' => $newFileName,
                'is_file' => true,
            ]);
        }

        Cache::forget('chat-' . $this->chat->id . '-messages');

        if ($this->chat->users()->updateExistingPivot(Auth::id(), ['is_active' => false])) {
            $this->chat->users()->updateExistingPivot(Auth::id(), ['is_active' => true]);
        }

        if ($this->chat->users()->updateExistingPivot(Auth::id(), ['is_archived' => true])) {
            $this->chat->users()->updateExistingPivot(Auth::id(), ['is_archived' => false]);
            $this->dispatch('chatUnarchived', $this->chat->id);
        }

        // $recipients = $this->chat->users->where('id', '!=', Auth::id());
        // foreach ($recipients as $user) {
        //     $this->chat->users()->updateExistingPivot($user->id, ['is_archived' => false, 'is_active' => true]);
        //     $this->dispatch('chatUnarchived', $this->chat->id);
        // }
        broadcast(new MessageSent($this->chat, $message));
        $this->dispatch('searchActiveUsers');
    }

    public function render()
    {
        return view('livewire.messages.send-message');
    }
}
