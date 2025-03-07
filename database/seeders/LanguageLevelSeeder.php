<?php

namespace Database\Seeders;

use App\Models\LanguageLevel;
use Illuminate\Database\Seeder;

class LanguageLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languageLevels = [
            ['name' => 'A1: Beginner', 'description' => 'I just started learning or I can introduce myself and ask simple questions.'],
            ['name' => 'A2: Elementary', 'description' => 'I can describe things in simple terms and understand simple expressions.'],
            ['name' => 'B1: Intermediate', 'description' => 'I can use my language skills when traveling and talk about my hobbies, work, and family.'],
            ['name' => 'B2: Upper Intermediate', 'description' => 'I can understand the main ideas of a complicated topic and have no trouble talking to native speakers.'],
            ['name' => 'C1: Advanced', 'description' => 'I can use my language skills in social, academic, or professional situations and keep up with complex conversations.'],
            ['name' => 'C2: Proficient', 'description' => 'I can understand almost everything I hear or read and express myself well when talking about complex topics.'],
        ];
        // Add timestamps dynamically
        $languageLevels = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $languageLevels);
        LanguageLevel::insert($languageLevels);
    }
}
