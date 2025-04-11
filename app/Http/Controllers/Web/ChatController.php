<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendMessageRequest;
use App\Http\Services\ChatService;
use App\Models\Conversation;
use App\Traits\ApiResponseHelper;
use Exception;

class ChatController extends Controller
{
    use ApiResponseHelper;

    protected $chatService;

    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(SendMessageRequest $request)
    {
        try {
            $this->chatService->sendMessage($request);
            return $this->apiResponse(true, 'Msg sent successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function getConversation(Conversation $conversation)
    {
        try {
            $data = $this->chatService->getConversation($conversation);
            return $this->apiResponse(true, 'Conversation fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function getConversations()
    {
        try {
            $data = $this->chatService->getConversations();
            return $this->apiResponse(true, 'Conversations fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

}
