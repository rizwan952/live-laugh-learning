<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Services\OrderService;
use App\Models\CoursePackage;
use App\Traits\ApiResponseHelper;
use Exception;
class OrderController extends Controller
{
    use ApiResponseHelper;

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    public function order(OrderRequest $request)
    {
        try {
            $this->orderService->createOrder($request);

            return $this->apiResponse(true, 'Order Created successfully');
        } catch (Exception $e) {
            $statusCode = 400;
            if ($e->getCode() > 0 && $e->getCode() < 600) {
                $statusCode = $e->getCode();
            }
            return $this->apiResponse(false, $e->getMessage(), [], $statusCode);
        }
    }


}
