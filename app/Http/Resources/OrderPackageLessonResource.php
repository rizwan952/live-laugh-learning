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
            'startAt' => $this->start_at,
            'endAt' => $this->end_at,
            'timeZone' => $this->time_zone,
            'createdAt' => $this->created_at ? $this->created_at->toDateTimeString() : null,
        ];
    }
}
