<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderLessonRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Services\OrderService;
use App\Models\Order;
use App\Traits\ApiResponseHelper;
use Exception;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ApiResponseHelper;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function getOrders(Request $request)
    {
        try {
            $data = $this->orderService->getOrders($request);
            return $this->apiResponse(true, 'Order fetched successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function order(OrderRequest $request)
    {
        try {
            $data = $this->orderService->createOrder($request);
            return $this->apiResponse(true, 'Order Created successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function updateOrderLessons(OrderLessonRequest $request, Order $order)
    {
        try {
            $this->orderService->updateOrderLessons($request, $order);
            return $this->apiResponse(true, 'Order updated successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function handleWebhook(Request $request)
    {
        return $this->orderService->handleWebhook($request);
    }


}
