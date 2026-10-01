<?php

namespace Database\Seeders;


use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory();

        Task::factory()
            ->count(5)
            ->for($user)
            ->create();

        Task::factory()
            ->count(3)
            ->for($user)
            ->create();
        
        Task::factory()
            ->count(8)
            ->for($user)
            ->create();

    }
}
