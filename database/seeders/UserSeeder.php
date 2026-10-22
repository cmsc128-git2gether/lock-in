<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        //how do we even know their passwords???? LMAO

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'pass1234!'
        ]);

        User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'password' => 'test1234!!'
        ]);
    }
}
