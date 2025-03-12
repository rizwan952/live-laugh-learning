<?php

namespace App\Http\Services\Admin;


use App\Http\Resources\Admin\ReviewResource;
use App\Models\Review;

class ReviewService
{

    public function getReviews()
    {
        $reviews= Review::all();
        return ReviewResource::collection($reviews);
    }

}
