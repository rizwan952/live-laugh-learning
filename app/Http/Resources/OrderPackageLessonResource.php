<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPackageLessonResource extends JsonResource
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
            'status' => $this->status,
            'amount' => $this->amount,
            'startAt' => $this->start_at,
            'endAt' => $this->end_at,
            'timeZone' => $this->time_zone,
            'refundableAmountPercentage' => $this->refundable_amount_percentage,
            'refundableAmount' => $this->refundable_amount,
            'refundMethod' => $this->refund_method,
            'refundId' => $this->refund_id,
            'refundReason' => $this->refund_reason,
            'refundInitiatedAt' => $this->refund_initiated_at,
            'refundedAt' => $this->refunded_at,
            'createdAt' => $this->created_at ? $this->created_at->toDateTimeString() : null,
        ];
    }
}
