<?php

namespace App\View\Components;

use Closure;
use App\Models\Chat;
use App\Models\User;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ChatName extends Component
{
    public $chat;
    public $user;
    
    /**
     * Create a new component instance.
     */
    public function __construct(Chat $chat, User $user)
    {
        $this->chat = $chat;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat-name');
    }
}
