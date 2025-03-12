<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'orderId' => $this->order_id,
            'title' => $this->title,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'isApproved'=>$this->is_approved,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
