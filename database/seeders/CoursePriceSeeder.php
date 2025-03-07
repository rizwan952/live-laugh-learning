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
            'single_lesson_price' => 26.00,
            'five_lessons_price' => 120.00,
            'ten_lessons_price' => 240.00,
            'fifteen_lessons_price' => 360.00,
            'twenty_lessons_price' => 380.00,
        ],
        [
            'course_id' => Course::first()->id,
            'duration' => 30, // Duration in minutes
            'single_lesson_price' => 20.00,
            'five_lessons_price' => 90.00,
            'ten_lessons_price' => 180.00,
            'fifteen_lessons_price' => 270.00,
            'twenty_lessons_price' => 360.00,
        ],
        [
            'course_id' => Course::first()->id,
            'duration' => 90, // Duration in minutes
            'single_lesson_price' => 32.00,
            'five_lessons_price' => 150.00,
            'ten_lessons_price' => 300.00,
            'fifteen_lessons_price' => 450.00,
            'twenty_lessons_price' => 600.00,
        ],
        [
            'course_id' => Course::skip(1)->first()->id,
            'duration' => 60,
            'single_lesson_price' => 24.00,
            'five_lessons_price' => 110.00,
            'ten_lessons_price' => 220.00,
            'fifteen_lessons_price' => 330.00,
            'twenty_lessons_price' => 440.00,
        ],
        [
            'course_id' => Course::skip(1)->first()->id,
            'duration' => 30,
            'single_lesson_price' => 18.00,
            'five_lessons_price' => 80.00,
            'ten_lessons_price' => 160.00,
            'fifteen_lessons_price' => 240.00,
            'twenty_lessons_price' => 320.00,
        ],
        [
            'course_id' => Course::skip(1)->first()->id,
            'duration' => 90,
            'single_lesson_price' => 30.00,
            'five_lessons_price' => 140.00,
            'ten_lessons_price' => 280.00,
            'fifteen_lessons_price' => 420.00,
            'twenty_lessons_price' => 560.00,
        ],
        [
            'course_id' => Course::skip(2)->first()->id,
            'duration' => 60,
            'single_lesson_price' => 30.00,
            'five_lessons_price' => 140.00,
            'ten_lessons_price' => 280.00,
            'fifteen_lessons_price' => 420.00,
            'twenty_lessons_price' => 560.00,
        ],
        [
            'course_id' => Course::skip(2)->first()->id,
            'duration' => 30,
            'single_lesson_price' => 24.00,
            'five_lessons_price' => 110.00,
            'ten_lessons_price' => 220.00,
            'fifteen_lessons_price' => 330.00,
            'twenty_lessons_price' => 440.00,
        ],
        [
            'course_id' => Course::skip(2)->first()->id,
            'duration' => 90,
            'single_lesson_price' => 36.00,
            'five_lessons_price' => 170.00,
            'ten_lessons_price' => 340.00,
            'fifteen_lessons_price' => 510.00,
            'twenty_lessons_price' => 680.00,
        ],
        [
            'course_id' => Course::skip(3)->first()->id,
            'duration' => 60,
            'single_lesson_price' => 28.00,
            'five_lessons_price' => 130.00,
            'ten_lessons_price' => 260.00,
            'fifteen_lessons_price' => 390.00,
            'twenty_lessons_price' => 520.00,
        ],
        [
            'course_id' => Course::skip(3)->first()->id,
            'duration' => 30,
            'single_lesson_price' => 22.00,
            'five_lessons_price' => 100.00,
            'ten_lessons_price' => 200.00,
            'fifteen_lessons_price' => 300.00,
            'twenty_lessons_price' => 400.00,
        ],
        [
            'course_id' => Course::skip(3)->first()->id,
            'duration' => 90,
            'single_lesson_price' => 34.00,
            'five_lessons_price' => 160.00,
            'ten_lessons_price' => 320.00,
            'fifteen_lessons_price' => 480.00,
            'twenty_lessons_price' => 640.00,
        ],
    ];
        $prices = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $prices);
        CoursePrice::insert($prices);

    }
}
