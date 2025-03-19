<?php

namespace App\Http\Services;

use App\Models\Course;
use App\Models\CourseDuration;
use App\Models\CoursePackage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseService
{

    public function createCourse(Request $request)
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

    public function updateCourse(Request $request, Course $course)
    {
        try {
            DB::beginTransaction();
            // Update course details directly from request
            $course->update([
                'category_id' => $request->categoryId,
                'language_id' => $request->languageId,
                'language_level_from_id' => $request->languageLevelFromId,
                'language_level_to_id' => $request->languageLevelToId,
                'name' => $request->name,
                'status' => $request->status,
                'description' => $request->description
            ]);

            $course->tags()->sync($request->tags);

            // Delete existing durations and packages
            $course->durations()->each(function ($duration) {
                $duration->packages()->delete(); // Delete related packages first
                $duration->delete(); // Then delete the duration
            });

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
