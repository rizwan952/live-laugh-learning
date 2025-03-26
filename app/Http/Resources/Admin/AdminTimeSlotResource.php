<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminTimeSlotResource extends JsonResource
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
            'startAt' => $this->start_at->format('H:i:s'),
            'endAt' => $this->end_at->format('H:i:s'),
            'createdAt' => $this->created_at->toDateTimeString(),
        ];
    }
}
