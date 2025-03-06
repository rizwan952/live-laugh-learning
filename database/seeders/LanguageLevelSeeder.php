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
            ['name' => 'Beginner', 'description' => 'Basic understanding of the language.'],
            ['name' => 'Elementary', 'description' => 'Able to communicate in simple terms.'],
            ['name' => 'Pre-Intermediate', 'description' => 'Can handle common daily conversations.'],
            ['name' => 'Intermediate', 'description' => 'Capable of engaging in normal conversations.'],
            ['name' => 'Upper-Intermediate', 'description' => 'Fluent but not yet perfect.'],
            ['name' => 'Advanced', 'description' => 'Speaks and understands with high proficiency.'],
            ['name' => 'Proficient', 'description' => 'Near-native level of fluency.']
        ];
        // Add timestamps dynamically
        $languageLevels = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $languageLevels);
        LanguageLevel::insert($languageLevels);
    }
}
