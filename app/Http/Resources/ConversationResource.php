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
        $authUserId = auth()->id();

        // Determine the other user and which relation is loaded
        $isUser1 = $this->user1_id === $authUserId;
        $otherUser = $isUser1 ? $this->user2 : $this->user1;
        $relation = $isUser1 ? 'user2' : 'user1';

        return [
            'id' => $this->id,
            'user1'=>$this->user1_id,
            'user2'=>$this->user2_id,
            'otherUser' => $this->whenLoaded($relation, fn() => [
                'id' => $otherUser->id,
                'name' => $otherUser->name,
                'image' => $otherUser->image ? asset('storage/' . $otherUser->image) : null,
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
