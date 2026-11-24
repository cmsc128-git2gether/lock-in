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
                'color' => '#FFAA00',
                'icon' => 'academic-cap'
            ],
            [
                'name' => 'Home', 
                'color' => '#005EFF',
                'icon' => 'home'
            ],
            [
                'name' => 'Other', 
                'color' => '#F49EFF',
                'icon' => 'tag'
            ],
           
        ];

        //default tags for all users
        foreach (User::all() as $user) {
            foreach ($tags as $tag) {
                Tag::updateOrCreate(['name' => $tag['name']], $tag);
            }
        }
    }
}
