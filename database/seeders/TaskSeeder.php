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
        $users = User::all();
        
        foreach ($users as $user) {
            Task::factory()
                ->count(2)
                ->for($user)
                ->create();

            Task::factory()
                ->count(3)
                ->for($user)
                ->create(['is_done' => true]);
            
            Task::factory()
                ->count(2)
                ->for($user)
                ->trashed()->create();
        }

    }
}
