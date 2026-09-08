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
            'description' => fake()->optional()->paragraph(),
            'priority' => fake()->randomElement(['Unlabeled', 'Low', 'Medium', 'High']),
            'due_at' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'is_done' => fake()->boolean(40),
        ];
    }

    // mark as done
    // public function done(): static {
    //     return $this->state(fn (array $attributes) => [
    //         'is_done' => true,
    //     ]);
    // }

    // soft deletes
    public function trashed(): static {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
