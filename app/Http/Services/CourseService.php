<?php

namespace App\Http\Services;

use App\Models\Course;
use App\Models\CourseDuration;
use App\Models\CoursePackage;
use Exception;
use Illuminate\Support\Facades\DB;

class CourseService
{

    public function createCourse($request)
    {
        try {
            DB::beginTransaction();
            $course = Course::create([
                'category_id' => $request->categoryId,
                'language_id' => $request->languageId,
                'language_level_from_id' => $request->languageLevelFromId,
                'language_level_to_id' => $request->languageLevelToId,
                'name' => $request->name,
                'status' => $request->status,
                'description' => $request->description
            ]);
            // Attach tags
            $course->tags()->attach($request->tags);

            // Create durations and packages
            foreach ($request->durations as $durationData) {
                $duration = CourseDuration::create([
                    "course_id" => $course->id,
                    "duration" => $durationData["duration"],
                ]);

                foreach ($durationData["packages"] as $packageData) {
                    CoursePackage::create([
                        "course_duration_id" => $duration->id,
                        "type" => $packageData["type"],
                        "price" => $packageData["price"],
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }

    }

}
