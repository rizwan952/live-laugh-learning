<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Send a message to a user (creates conversation if needed)
     */
    public function sendMessage(SendMessageRequest $request)
    {
        $senderId = Auth::id();
        $receiverId = User::where('role','admin')->first()->value('id');

        // Find or create conversation
        $conversation = Conversation::where(function ($query) use ($senderId, $receiverId) {
            $query->where('user1_id', $senderId)->where('user2_id', $receiverId);
        })->orWhere(function ($query) use ($senderId, $receiverId) {
            $query->where('user1_id', $receiverId)->where('user2_id', $senderId);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user1_id' => $senderId,
                'user2_id' => $receiverId,
            ]);
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $senderId,
            'content' => $request->message,
            'message_type' => $request->message_type ?? 'text',
        ]);

        // Update last_message_at
        $conversation->update(['last_message_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => new MessageResource($message)
        ], 201);
    }

    /**
     * Get all messages in a conversation
     */
    public function getConversation($conversationId)
    {
        $conversation = Conversation::where('id', $conversationId)
            ->where(function ($query) {
                $query->where('user1_id', Auth::id())
                    ->orWhere('user2_id', Auth::id());
            })->firstOrFail();

        $messages = Message::where('conversation_id', $conversation->id)
            ->paginate(20);

        // Mark messages as read if receiver is current user
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'conversation' => new ConversationResource($conversation),
            'messages' => MessageResource::collection($messages)
        ]);
    }

    /**
     * Get all conversations for the authenticated user
     */
    public function getConversations()
    {
        $conversations = Conversation::where('user1_id', Auth::id())
            ->orWhere('user2_id', Auth::id())
            ->orderBy('last_message_at', 'desc')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'conversations' => ConversationResource::collection($conversations)
        ]);
    }
}
