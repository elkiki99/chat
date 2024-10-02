<?php

// namespace App\View\Components;

// use Closure;
// use App\Models\Chat;
// use App\Models\User;
// use Illuminate\View\Component;
// use Illuminate\Contracts\View\View;
// use Illuminate\Support\Facades\Auth;

// class ChatHeader extends Component
// {
//     public $chat;
//     public $user;

//     /**
//      * Create a new component instance.
//      */
//     public function __construct(Chat $chat)
//     {
//         $this->chat = $chat;
//         $this->user = $chat->users->where('id', '!==', Auth::id())->first();
//     }

//     /**
//      * Get the view / contents that represent the component.
//      */
//     public function render(): View|Closure|string
//     {
//         return view('components.chat-header', [
//             'chat' => $this->chat,
//             'user' => $this->user,
//         ]);
//     }
// }
