<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\ReviewResource;
use App\Models\Course;
use App\Models\Review;
use App\Traits\ApiResponseHelper;
use Exception;

class CourseController extends Controller
{
    use ApiResponseHelper;

    public function getCourses()
    {
        try {
            $courses = Course::all();
            $data = CourseResource::collection($courses);
            return $this->apiResponse(true, 'Data fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function getReviews()
    {
        try {
            $reviews = Review::where('is_approved', true)->with('student')->get();
            $data = ReviewResource::collection($reviews);
            return $this->apiResponse(true, 'Data fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

}
