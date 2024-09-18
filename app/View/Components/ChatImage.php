<?php

namespace App\View\Components;

use Closure;
use App\Models\Chat;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ChatImage extends Component
{
    public $chat;

    /**
     * Create a new component instance.
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat-image', [
            'chat' => $this->chat,
        ]);
    }
}
