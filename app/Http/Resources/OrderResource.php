<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'courseId' => $this->course_id,
            'courseName' => $this->course_name,
            'coursePrice' => $this->course_price,
            'refundAmount'=>$this->refund_amount,
            'finalAmount' => $this->final_amount,
            'status' => $this->status,
            'paymentMethod' => $this->payment_method,
            'paymentStatus' => $this->payment_status,
            'createdAt' => $this->created_at->toDateTimeString(),
            'package' => new OrderPackageResource($this->package),
            'student' => new UserResource($this->student),
            'review' => new ReviewResource($this->review)
        ];
    }
}
