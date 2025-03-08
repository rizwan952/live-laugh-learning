<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'languages' => LanguageResource::collection($this->resource['languages']),
            'languageLevels' => LanguageLevelResource::collection($this->resource['languageLevels']),
            'categories' => CategoryResource::collection($this->resource['categories']),
            'tags' => TagResource::collection($this->resource['tags'])
        ];
    }
}
