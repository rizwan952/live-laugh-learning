<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;


Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::findOrFail($conversationId);
    return $user->id === $conversation->user1_id || $user->id === $conversation->user2_id;
});
