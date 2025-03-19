<?php

namespace App\Http\Services\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderService
{

    public function getOrders()
    {
        $orders = Order::all();
        return OrderResource::collection($orders);
    }

    public function updateOrder(Request $request, Order $order)
    {
        $order->update([
            'status' => $request->status
        ]);
    }
}
