<?php

namespace Database\Seeders;

use App\Models\CourseDuration;
use Illuminate\Database\Seeder;

class CourseDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $durations = [
            [
                'course_id' => 1,
                'duration' => 30,
            ],
            [
                'course_id' => 1,
                'duration' => 60,
            ],
            [
                'course_id' => 2,
                'duration' => 45,
            ],
            [
                'course_id' => 2,
                'duration' => 90,
            ]
        ];
        $durations = array_map(fn($level) => array_merge($level, [
            'created_at' => now(),
            'updated_at' => now()
        ]), $durations);
        CourseDuration::insert($durations);
    }
}
