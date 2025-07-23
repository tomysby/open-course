<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            'Beginner', 'Intermediate', 'Advanced',
            'Theory', 'Practical', 'Research',
            'Tutorial', 'Guide', 'Reference'
        ];
    
        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag,
                'slug' => Str::slug($tag)
            ]);
        }
    }
}
