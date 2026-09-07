<?php

namespace Database\Seeders;


use App\Models\Task;
use App\Models\Tag;
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
        $tags = Tag::all();

        $users->each(function ($user) use ($tags) {
            Task::factor()
                ->count(rand(3,5))
                ->create(['user_id' => $user->id])
                ->each(function ($task) use ($tags) {
                    $task->tags()->attach(
                        $tags->random(rand(1,3))->pluck('id')->toArray()
                    );
                });
        });

    }
}
