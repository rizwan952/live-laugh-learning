<?php

namespace App\Http\Controllers\Admin;

use  App\Http\Controllers\Controller;
use App\Http\Requests\AdminOrderRequest;
use App\Http\Services\Admin\OrderService;
use App\Models\Order;
use App\Models\OrderPackageLesson;
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

    public function getOrders()
    {
        try {
            $data = $this->orderService->getOrders();

            return $this->apiResponse(true, 'Orders fetch successfully', $data);
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }

    }

    public function updateOrder(AdminOrderRequest $request, Order $order)
    {
        try {
            $this->orderService->updateOrder($request, $order);
            return $this->apiResponse(true, 'Order updated successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }

    public function updateOrderLesson(AdminOrderRequest $request, OrderPackageLesson $orderPackageLesson)
    {
        try {
            $this->orderService->updateOrderPackageLesson($request, $orderPackageLesson);
            return $this->apiResponse(true, 'OrderLesson updated successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }


}
