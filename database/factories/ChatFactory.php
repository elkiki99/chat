<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->boolean() ? $this->faker->word() : null,
            'is_group' => $this->faker->boolean(),
            'chat_image' => $this->faker->boolean() ? $this->faker->imageUrl() : null,
        ];
    }
}
