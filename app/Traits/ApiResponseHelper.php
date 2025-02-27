<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseHelper
{
    /*
     * @param array|string $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */

    public function apiResponse(bool $status, string $message = null, $data = [], $code = 500) : JsonResponse
    {
        if ($status) {
            return $this->apiSuccessResponse($status, $message, $data);
        } else {
            return $this->apiErrorResponse($status, $message, $data, $code);
        }
    }

    protected function apiSuccessResponse(bool $status, string $message = null, $data = [])
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => isset($data['data']) ? $data['data'] : $data,
        ], 200);
    }

    protected function apiErrorResponse(bool $status, string $message = null, $data = [], $code = 500)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => is_array($data) ? $data : ['error' => $data],
        ], $code);
    }
}
