<?php

namespace App\Http\Resources\Admin;

use App\Http\Resources\DurationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCalendarResource extends JsonResource
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
            'date' => $this->date->format('Y-m-d'),
            'timeZone' => $this->time_zone,
            'createdAt' => $this->created_at->toDateTimeString(),
            'timeSlots'=> AdminTimeSlotResource::collection($this->timeSlots)
        ];
    }
}
