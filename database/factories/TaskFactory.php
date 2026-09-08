<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user_id = User::first()->id;
        return [
            'user_id' => $user_id,
            'tag_id' => Tag::inRandomOrder()->value('id'),
            'title' => fake()->sentence(4),
            'priority' => fake()->randomElement(Task::priorities),
            'due_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'is_done' => fake()->boolean(40),
        ];
    }

    // soft deletes
    public function trashed(): static {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
