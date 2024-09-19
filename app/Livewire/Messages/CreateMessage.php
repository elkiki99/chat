<?php

// namespace App\Livewire\Messages;

// use Livewire\Component;
// use App\Events\MessengerEvent;
// use Illuminate\Support\Facades\Auth;

// class CreateMessage extends Component
// {
//     public $chat;
//     public $body;

//     public function sendMessage()
//     {
//         MessengerEvent::dispatch($this->chat->id, Auth::id(), $this->body, 'sent');
//         $this->body = '';
//     }

//     public function render()
//     {        
//         return view('livewire.messages.create-message');
//     }
// }