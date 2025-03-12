<?php

namespace App\Http\Controllers\Admin;

use  App\Http\Controllers\Controller;
use App\Http\Services\Admin\ReviewService;
use App\Traits\ApiResponseHelper;
use Exception;

class ReviewController extends Controller
{
    use ApiResponseHelper;

    protected $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index()
    {
        try {
            $data = $this->reviewService->getReviews();
            return $this->apiResponse(true, 'Reviews fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }


}
