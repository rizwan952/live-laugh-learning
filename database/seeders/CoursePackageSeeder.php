<?php

namespace Database\Seeders;

use App\Models\CoursePackage;
use Illuminate\Database\Seeder;

class CoursePackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'course_duration_id' => 1,
                'type' => 'single_lesson_price',
                'lesson_count' => 1,
                'price' => 99.99,
            ],
            [
                'course_duration_id' => 1,
                'type' => 'five_lessons_price',
                'lesson_count' => 5,
                'price' => 149.99,
            ],
            [
                'course_duration_id' => 2,
                'type' => 'ten_lessons_price',
                'lesson_count' => 10,
                'price' => 129.99,
            ],
            [
                'course_duration_id' => 2,
                'type' => 'fifteen_lessons_price',
                'lesson_count' => 10,
                'price' => 179.99,
            ],
            [
                'course_duration_id' => 2,
                'type' => 'twenty_lessons_price',
                'lesson_count' => 20,
                'price' => 179.99,
            ]
        ];
        $packages = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $packages);
        CoursePackage::insert($packages);
    }
}
