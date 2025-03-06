<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseTag;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class CourseTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        $tags = Tag::limit(3)->get();
        foreach ($courses as $course) {
            foreach ($tags as $tag) {
                    CourseTag::create([
                        'course_id' => $course->id,
                        'tag_id' => $tag->id,
                    ]);
            }
        }
    }
}
