<?php

namespace Database\Seeders;

use App\Models\Chat;
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
        $users = User::factory(40)->create();

        $chats = Chat::factory(20)->create();

        foreach ($chats as $chat) {
            $isGroup = $chat->is_group;

            if ($isGroup) {
                $chat->update(['name' => 'Grupo ' . fake()->word()]);
                $usersForChat = $users->random(rand(2, 10))->pluck('id');
            } else {
                $chat->update(['name' => null]);
                $usersForChat = $users->random(2)->pluck('id');
            }

            $chat->users()->attach($usersForChat, ['joined_at' => now()]);

            foreach (range(1, 100) as $index) {
                $messageUserId = $usersForChat->random();

                Message::factory()->create([
                    'chat_id' => $chat->id,
                    'user_id' => $messageUserId,
                ]);
            }
        }

        foreach ($users as $user) {
            $contacts = $users->where('id', '!=', $user->id)->random(10);
            $user->contacts()->attach($contacts->pluck('id'));
        }
    }
}