<?php

namespace App\Http\Services\Admin;


use App\Http\Resources\Admin\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewService
{

    public function getReviews()
    {
        $reviews = Review::all();
        return ReviewResource::collection($reviews);
    }

    public function updateReview(Request $request, Review $review)
    {
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => $request->isApproved
        ]);
    }

}
