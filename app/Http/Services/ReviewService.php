<?php

namespace App\Http\Services;

use App\Http\Resources\ReviewResource;
use App\Models\Order;
use App\Models\Review;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewService
{

    public function getReviews()
    {
        $reviews = Review::all();
        return ReviewResource::collection($reviews);
    }

    public function createReview(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = Order::find(['student_id' => $request->user()->id, $request->orderId])->first();
            if ($order) {
                throw new Exception('Order is not valid');
            }
            $checkReview = Review::where(['student_id' => $request->user()->id, 'order_id' => $order->id,])->first();
            if ($checkReview) {
                throw new Exception('Review already exist');
            }

            Review::create([
                'student_id' => $request->user()->id,
                'order_id' => $order->id,
                'title' => $order->course->name,
                'rating' => $request->rating,
                'comment' => $request->comment
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

}
