<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\Chat;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'info',
        'profile_picture',
        'password',
        'is_active_in_chat',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isActive()
    {
        return $this->updated_at->diffForHumans() === Carbon::now()->diffForHumans();
    }

    public function lastActive()
    {
        return $this->updated_at->diffForHumans();
    }
    
    public function chats()
    {
        return $this->belongsToMany(Chat::class)->withTimestamps()->withPivot('is_archived');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function contactsAddedByUser()
    {
        return $this->hasMany(Contact::class, 'contact_user_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(User::class, 'contacts', 'user_id', 'contact_user_id');
    }

    public function seenMessages()
    {
        return $this->belongsToMany(Message::class, 'message_user');
    }
}
