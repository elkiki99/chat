<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
// Broadcast::channel('message-sent.{chatId}', function (User $user, int $chatId) {
//     return $user->id === Chat::findOrNew($chatId)->user_id;
// });
// Broadcast::channel('message-read.chats.{id}', function ($user, $id) {
//     return $user->chats->contains($id);
// });