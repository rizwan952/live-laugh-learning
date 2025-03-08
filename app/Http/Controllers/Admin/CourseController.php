<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Http\Services\CourseService;
use App\Models\Course;
use App\Traits\ApiResponseHelper;
use Exception;

class CourseController extends Controller
{
    use ApiResponseHelper;

    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function createCourse(CourseRequest $request)
    {
        try {
            $this->courseService->createCourse($request);
            return $this->apiResponse(true, 'Course created successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function updateCourse(CourseRequest $request, Course $course)
    {
        try {
            $this->courseService->updateCourse($request, $course);
            return $this->apiResponse(true, 'Course updated successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }
}
