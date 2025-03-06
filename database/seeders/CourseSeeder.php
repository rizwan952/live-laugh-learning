<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Language;
use App\Models\LanguageLevel;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Category::first();
        $language = Language::first();
        $levelFrom = LanguageLevel::first();
        $levelTo = LanguageLevel::skip(1)->first();

        $courses = [
            [
                'category_id' => $category->id,
                'language_id' => $language->id,
                'language_level_from_id' => $levelFrom->id,
                'language_level_to_id' => $levelTo->id,
                'name' => 'English for Beginners',
                'description' => 'An introductory course to English language.',
            ],
            [
                'category_id' => $category->id,
                'language_id' => $language->id,
                'language_level_from_id' => $levelFrom->id,
                'language_level_to_id' => $levelTo->id,
                'name' => 'Advanced English Grammar',
                'description' => 'A course covering advanced grammar topics in English.',
            ],
        ];
        $courses = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $courses);
        Course::insert($courses);
    }
}
