<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
                'color' => '#BACAC1',
                'icon' => 'tag'
            ],
           
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['name' => $tag['name']], 
                ['color' => $tag['color'], 'icon' => $tag['icon']]
            );
        }
    }
}
