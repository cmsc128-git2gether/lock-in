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
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'priority' => fake()->numberBetween(0, 3), // 0 - NA, 1 - Low, 2 - Med, 3 - High
            'due_at' => fake()->optional()->dateTimeBetween('now', '+2 weeks'),
            'is_done' => fake()->boolean(20),
            'is_deleted' => fake()->boolean(20),
        ];
    }
}
