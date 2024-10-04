<?php

namespace App\View\Components;

use Closure;
use App\Models\User;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ProfilePicture extends Component
{
    public $user;

    /**
     * Create a new component instance.
     */
    public function __construct(?User $user = null)
    {
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.profile-picture', [
            'user' => $this->user,
        ]);
    }
}
