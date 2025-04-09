<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'conversationId' => $this->conversation_id,
            'sender' => $this->whenLoaded('sender', fn() => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
                'email' => $this->sender->email ?? null, // Optional, adjust as needed
            ], fn() => ['id' => $this->sender_id]), // Fallback if not loaded
            'content' => $this->content,
            'messageType' => $this->message_type,
            'isRead' => $this->is_read,
            'isDelivered' => $this->is_delivered,
            'isDeleted' => $this->is_deleted, // Include soft delete status
            'sentAt' => $this->created_at, // Use created_at from migration
            'deliveredAt' => $this->delivered_at,
            'readAt' => $this->read_at,
            'isMine' => $this->sender_id === auth()->id(), // Indicates if the message is from the current user
        ];
    }
}
