<?php

namespace App\View\Components;

use Closure;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class MessageBubble extends Component
{
    public $chat;
    public $message;
    public $isLastInBlock;
    public $isFirstInBlock;

    /**
     * Create a new component instance.
     */
    public function __construct(Chat $chat, Message $message, bool $isLastInBlock, bool $isFirstInBlock)
    {
        $this->chat = $chat;
        $this->message = $message;
        $this->isLastInBlock = $isLastInBlock;
        $this->isFirstInBlock = $isFirstInBlock;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.message-bubble');
    }
}