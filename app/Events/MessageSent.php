<?php

namespace App\Events;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        Log::info('inside message event');
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        Log::info('broadcastOn ' . $this->message->conversation_id);
        return new PrivateChannel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastWith(): array
    {
        Log::info('broadcastWith ' . json_encode(new MessageResource($this->message)));
        return [
            'message' => new MessageResource($this->message),
        ];
    }
}
