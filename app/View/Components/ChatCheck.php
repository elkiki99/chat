<?php

namespace App\View\Components;

use Closure;
use App\Models\Message;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ChatCheck extends Component
{
    public $message;

    /**
     * Create a new component instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chat-check');
    }
}
