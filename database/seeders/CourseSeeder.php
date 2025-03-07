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
                'name' => 'Oral English (students who have completed at least 10 lessons)',
                'description' => 'This course is relaxed and organized based on the areas of your choice. After we connect, we will decide which areas you would like to focus on and what principles or topics you would like to cover. We can go over grammatical principles or various topics. I can also assist with homework, work presentations, job interviews or any other class or work assignments.

                The focus will be placed on any or all of the following:

                ∞	Listening Skills 
                ∞	Writing skills 
                ∞	Speaking skills
                ∞	Reading skills


                *Each lesson will end a few minutes early to allow sufficient transition time for the next lesson*

                *This lesson is geared towards adults and not children. If you would like to schedule a lesson for your child please send me a message for further details.*',
            ],
            [
                'category_id' => $category->id,
                'language_id' => $language->id,
                'language_level_from_id' => $levelFrom->id,
                'language_level_to_id' => $levelTo->id,
                'name' => 'Movies & Entertainment',
                'description' => 'Movies and TV series are great ways to learn English! We can do many different things for this lesson. We will focus on improving listening and comprehension skills through natural English conversation. This is a lesson for anyone, especially those who love movies or shows!

                *Each lesson can end a few minutes early to allow sufficient transition time for the next lesson*,'
            ],
            [
                'category_id' => $category->id,
                'language_id' => $language->id,
                'language_level_from_id' => $levelFrom->id,
                'language_level_to_id' => $levelTo->id,
                'name' => 'Oral English',
                'description' => 'This course is relaxed and organized based on the areas of your choice. After we connect, we will decide which areas you would like to focus on and what principles or topics you would like to cover. We can go over grammatical principles or various topics. I can also assist with homework, work presentations, job interviews or any other class or work assignments.

                The focus will be placed on any or all of the following:

                ∞	Listening Skills 
                ∞	Writing skills 
                ∞	Speaking skills
                ∞	Reading skills


                *Each lesson will end a few minutes early to allow sufficient transition time for the next lesson*

                *This lesson is geared towards adults and not children. If you would like to schedule a lesson for your child please send me a message for further details.*',
            ],
            [
                'category_id' => $category->id,
                'language_id' => $language->id,
                'language_level_from_id' => $levelFrom->id,
                'language_level_to_id' => $levelTo->id,
                'name' => 'Oral English (for students who have taken 5 lessons)',
                'description' => 'This course is relaxed and organized based on the areas of your choice. After we connect, we will decide which areas you would like to focus on and what principles or topics you would like to cover. We can go over grammatical principles or various topics. I can also assist with homework, work presentations, job interviews or any other class or work assignments.

                The focus will be placed on any or all of the following:

                ∞	Listening Skills 
                ∞	Writing skills 
                ∞	Speaking skills
                ∞	Reading skills


                *Each lesson will end a few minutes early to allow sufficient transition time for the next lesson*

                *This lesson is geared towards adults and not children. If you would like to schedule a lesson for your child please send me a message for further details.*',
            ],
        ];
        $courses = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $courses);
        Course::insert($courses);
    }
}
