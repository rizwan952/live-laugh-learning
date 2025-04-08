<?php

namespace App\Http\Services\Admin;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderPackageLesson;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Refund;
class OrderService
{

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

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




    public function updateOrderPackageLesson(Request $request, OrderPackageLesson $orderPackageLesson)
    {
        $orderPackageLesson->update([
            'status' => $request->status
        ]);
        if ($request->status == 'refund_approved'){
//            Start refund processing
            $this-> lessonRefund($orderPackageLesson);
        }
    }

    public function lessonRefund(OrderPackageLesson $orderPackageLesson)
    {
        $amountInCents = (int)($orderPackageLesson->amount * 100);
        $refund = Refund::create([
            'charge' => $orderPackageLesson->order->charge_id,
            'amount' => $amountInCents,
            'reason' => $request->reason ?? 'requested_by_customer',
            'metadata' => [
                'lesson_id' => $orderPackageLesson->id,
                'order_id' => $orderPackageLesson->order->id
            ]
        ]);

        // Update lesson with refund details
        $orderPackageLesson->update([
            'refund_method' => 'stripe',
            'refund_id' => $refund->id,
            'refund_initiated_at' => now(),
            'status'=>'refund_processing',
            'refund_details' => json_encode($refund)
        ]);
    }
}
