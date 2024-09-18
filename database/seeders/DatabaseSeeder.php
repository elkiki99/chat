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
        // Crear usuarios
        $users = User::factory(20)->create();

        // Crear chats
        $chats = Chat::factory(10)->create();

        foreach ($chats as $chat) {
            // Determinar si el chat es un grupo o no
            $isGroup = $chat->is_group;

            if ($isGroup) {
                // Para chats de grupo, asignar un título
                $chat->update(['name' => 'Grupo ' . fake()->word()]);

                // Asignar más de 2 usuarios
                $usersForChat = $users->random(rand(3, 5))->pluck('id')->toArray();
            } else {
                // Para chats no grupales, no asignar título
                $chat->update(['name' => null]);

                // Asignar exactamente 2 usuarios
                $usersForChat = $users->random(2)->pluck('id')->toArray();
            }

            // Adjuntar usuarios al chat
            $chat->users()->attach($usersForChat, ['joined_at' => now()]);

            // Crear mensajes para el chat, asignando mensajes a usuarios del chat
            foreach (range(1, 10) as $index) {
                $messageUserId = $usersForChat[array_rand($usersForChat)];

                Message::factory()->create([
                    'chat_id' => $chat->id,
                    'user_id' => $messageUserId,
                ]);
            }
        }

        // Crear contactos para cada usuario
        foreach ($users as $user) {
            $contacts = $users->where('id', '!=', $user->id)->random(3);
            $user->contacts()->attach($contacts->pluck('id')->toArray());
        }
    }
}
