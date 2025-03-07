<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'category' => $this->category->name,
            'language' => $this->language->name,
            'languageLevelFrom' => $this->languageLevelFrom->name,
            'languageLevelTo' => $this->languageLevelTo->name,
            'name' => $this->name,
            'status' => $this->status,
            'description' => $this->description,
            'created_at' => $this->created_at->toDateTimeString(),
            'tags' => TagResource::collection($this->tags),
            'durations'=> DurationResource::collection($this->durations)
        ];
    }
}
