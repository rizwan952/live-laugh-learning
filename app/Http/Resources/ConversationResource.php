<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Determine the other user relative to the authenticated user
        $authUserId = auth()->id();
        $otherUser = $this->user1_id === $authUserId ? $this->user2 : $this->user1;
        return [
            'id' => $this->id,
            'otherUser' => $this->whenLoaded('user1', fn() => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'email' => $otherUser->email ?? null, // Optional, adjust fields as needed
            ]),
            'lastMessage' =>$this->messages->isNotEmpty() ? new MessageResource($this->messages->sortByDesc('created_at')->first()) : null,
            'unread_count' => $this->whenLoaded('messages', fn() => $this->messages->where('sender_id', '!=', $authUserId)
                ->where('is_read', false)
                ->count()
            ),
            'createdAt' => $this->created_at->toDateTimeString(),
            'lastMessageAt' => $this->last_message_at,
        ];
    }
}
