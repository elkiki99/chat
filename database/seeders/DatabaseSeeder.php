<?php

namespace Database\Seeders;

use App\Models\Chat;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();
        $chats = Chat::factory(5)->create();

        foreach ($users as $user) {
            $user->chats()->attach(
                $chats->random(3)->pluck('id')->toArray(),
                ['joined_at' => now()]
            );
        }

        foreach ($chats as $chat) {
            Message::factory(5)->create(['chat_id' => $chat->id]);
        }

        foreach ($users as $user) {
            $contacts = $users->where('id', '!=', $user->id)->random(3);
            $user->contacts()->attach($contacts->pluck('id')->toArray());
        }
    }
}
