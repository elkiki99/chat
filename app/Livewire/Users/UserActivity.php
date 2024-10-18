<?php

// namespace App\Livewire\Users;

// use App\Models\User;
// use Livewire\Component;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Cache;

// class UserActivity extends Component
// {
//     public $activeUsers = [];

//     public function getActiveUsers()
//     {
//         $this->activeUsers = User::all()->filter(function ($user) {
//             return Cache::has('user-is-online-' . $user->id);
//         })->values();
//     }

//     public function updateActivity()
//     {
//         if (Auth::check()) {
//             $userId = Auth::id();
//             Cache::put('user-is-online-' . $userId, true, now()->addMinutes(5));
            
//             $user = Auth::user();
//             $user->last_seen = now();
//             $user->save();
//         }

//         $this->getActiveUsers();
//     }

//     public function render()
//     {
//         $this->getActiveUsers();

//         return view('livewire.users.user-activity', [
//             'activeUsers' => $this->activeUsers,
//         ]);
//     }
// }
