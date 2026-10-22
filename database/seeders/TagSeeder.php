<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            [
                'name' => 'School', 
                'color' => '#1DAB5D',
                'icon' => 'academic-cap'
            ],
            [
                'name' => 'Home', 
                'color' => '#94AAD1',
                'icon' => 'home'
            ],
            [
                'name' => 'Other', 
                'color' => '#CEFF72',
                'icon' => 'tag'
            ],
           
        ];

        //default tags for all users
        foreach (User::all() as $user) {
            foreach ($tags as $tag) {
                Tag::updateOrCreate(
                    ['user_id' => $user->id, 'name' => $tag['name']],
                    ['color' => $tag['color'], 'icon' => $tag['icon']]
                );
            }
        }
    }
}
