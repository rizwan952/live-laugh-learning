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
                        "lesson_count"=>$packageData['lessonCount'],
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

            // Sync tags
            $course->tags()->sync($request->tags);

            // Arrays to track IDs for durations and packages to keep
            $durationIdsToKeep = [];
            $packageIdsToKeep = [];

            // Process durations and packages
            foreach ($request->durations as $durationData) {
                if (isset($durationData['id']) && $durationData['id']) {
                    // Update existing duration
                    $duration = CourseDuration::findOrFail($durationData['id']);
                    $duration->update([
                        'duration' => $durationData['duration'],
                    ]);
                    $durationIdsToKeep[] = $duration->id;
                } else {
                    // Create new duration
                    $duration = CourseDuration::create([
                        'course_id' => $course->id,
                        'duration' => $durationData['duration'],
                    ]);
                    $durationIdsToKeep[] = $duration->id;
                }

                // Process packages for this duration
                if (isset($durationData['packages']) && is_array($durationData['packages'])) {
                    foreach ($durationData['packages'] as $packageData) {
                        if (isset($packageData['id']) && $packageData['id']) {
                            // Update existing package
                            $package = CoursePackage::findOrFail($packageData['id']);
                            $package->update([
                                'type' => $packageData['type'],
                                'price' => $packageData['price'],
                                // Handle lesson_count if provided or derive it
                                'lesson_count' => $packageData['lessonCount'],
                            ]);
                            $packageIdsToKeep[] = $package->id;
                        } else {
                            // Create new package
                            $package = CoursePackage::create([
                                'course_duration_id' => $duration->id,
                                'type' => $packageData['type'],
                                'price' => $packageData['price'],
                                // Handle lesson_count if provided or derive it
                                'lesson_count' => $packageData['lessonCount'],
                            ]);
                            $packageIdsToKeep[] = $package->id;
                        }
                    }
                }
            }

            // Delete durations not in the updated data
            $course->durations()
                ->whereNotIn('id', $durationIdsToKeep)
                ->each(function ($duration) {
                    $duration->packages()->delete(); // Delete related packages first
                    $duration->delete(); // Then delete the duration
                });

            // Delete packages not in the updated data for remaining durations
            CoursePackage::whereIn('course_duration_id', $durationIdsToKeep)
                ->whereNotIn('id', $packageIdsToKeep)
                ->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

}
