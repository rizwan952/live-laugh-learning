<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CoursePrice;
use Illuminate\Database\Seeder;

class CoursePriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prices = [[
            'course_id' => Course::first()->id,
            'duration' => 60, // Duration in minutes
            'single_lesson_price' => 25.00,
            'five_lessons_price' => 115.00,
            'ten_lessons_price' => 220.00,
            'fifteen_lessons_price' => 315.00,
            'twenty_lessons_price' => 400.00,
        ],
        [
            'course_id' => Course::skip(1)->first()->id,
            'duration' => 90,
            'single_lesson_price' => 35.00,
            'five_lessons_price' => 165.00,
            'ten_lessons_price' => 310.00,
            'fifteen_lessons_price' => 450.00,
            'twenty_lessons_price' => 580.00,
        ]];
        $prices = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $prices);
        CoursePrice::insert($prices);

    }
}
