<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'body',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seenBy()
    {
        return $this->belongsToMany(User::class, 'message_user', 'message_id', 'user_id');
    }

    public function seenByAll()
    {
        $chatUsers = $this->chat->users()->where('user_id', '!=', $this->user_id)->pluck('users.id');
        $seenByUsers = $this->seenBy()->pluck('users.id');
        return $chatUsers->diff($seenByUsers)->isEmpty();
    }
}
