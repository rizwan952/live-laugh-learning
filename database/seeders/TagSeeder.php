<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Pronunciation'],
            ['name' => 'Grammar'],
            ['name' => 'Spelling'],
            ['name' => 'Reading'],
            ['name' => 'Listening'],
            ['name' => 'Writing'],
            ['name' => 'Speaking'],
        ];
        $tags = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $tags);
        Tag::insert($tags);
    }
}
