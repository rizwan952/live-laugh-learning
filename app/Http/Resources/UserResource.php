<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,  // Use $this instead of $request
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'status' => $this->status,
            'emailVerifiedAt' => $this->email_verified_at,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'createdAt' => $this->created_at->toDateTimeString(),
        ];
    }
}
