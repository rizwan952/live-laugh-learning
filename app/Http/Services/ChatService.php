<?php

namespace App\Http\Services;

use App\Http\Resources\ConversationResource;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function sendMessage(Request $request)
    {
        try {
            DB::beginTransaction();

            $senderId = Auth::id();
            $receiverId = $request->receiverId;
            if (auth()->user()->role == 'student'){
                $receiverId = User::where('role', 'admin')->first()->id;
            }

            // Find or create conversation (ensure consistent user1_id < user2_id)
            $conversation = Conversation::where(function ($query) use ($senderId, $receiverId) {
                $query->where('user1_id', min($senderId, $receiverId))
                    ->where('user2_id', max($senderId, $receiverId));
            })->first();

            if (!$conversation) {
                $conversation = Conversation::create([
                    'user1_id' => min($senderId, $receiverId),
                    'user2_id' => max($senderId, $receiverId),
                    'last_message_at' => now(),
                ]);
            } else {
                $conversation->update(['last_message_at' => now()]);
            }

            // Create message
            Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $senderId,
                'content' => $request->message,
                'message_type' => $request->message_type ?? 'text',
                'is_delivered' => false, // Default, can be updated via events
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function getConversation(Request $request, Conversation $conversation)
    {
        $conversation = Conversation::where('id', $conversation->id)
            ->where(function ($query) {
                $query->where('user1_id', Auth::id())
                    ->orWhere('user2_id', Auth::id());
            })->with('messages')->firstOrFail();

        $perPage = $request->get('per_page', 15); // default 15 messages per page
        $messages = Message::where('conversation_id', $conversation->id)
            ->orderBy('created_at', 'desc') // optional: show latest first
            ->paginate($perPage);

        // Mark messages as read if receiver is current user
        Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return  [
            'conversions' => new ConversationResource($conversation),
            'messages'=> MessageResource::collection($messages),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'per_page' => $messages->perPage(),
                'total' => $messages->total(),
                'next_page_url' => $messages->nextPageUrl(),
                'prev_page_url' => $messages->previousPageUrl(),
            ]
            ];

    }

    public function getUnreadMessages(Conversation $conversation)
    {
        // Get only unread messages where the current user is the receiver
        $unreadMessages = Message::where('conversation_id', $conversation->id)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark them as read
        Message::whereIn('id', $unreadMessages->pluck('id'))
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);


        return MessageResource::collection($unreadMessages);
    }


    public function getConversations()
    {
         $conversations = Conversation::where('user1_id', Auth::id())
            ->orWhere('user2_id', Auth::id())
            ->orderBy('last_message_at', 'desc')
            ->with('messages')
            ->get();

        return ConversationResource::collection($conversations);

    }


}
